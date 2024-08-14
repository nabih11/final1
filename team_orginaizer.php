<?php
include 'db_connection.php';
include 'nav-fot.php'; 
$con = OpenCon();

$query = "SELECT * FROM morning_weekly";
$result = mysqli_query($con, $query);

$daysOfWeek = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];

// פונקציה לקבלת תאריך יום ראשון של השבוע הנוכחי
function getStartOfWeek($date) {
    $timestamp = strtotime($date);
    $startOfWeek = strtotime('last Sunday', $timestamp);
    if (date('l', $timestamp) === 'Sunday') {
        $startOfWeek = $timestamp;
    }
    return date('Y-m-d', $startOfWeek);
}

// פונקציה להוספת מספר ימים לתאריך
function addDays($date, $days) {
    return date('Y-m-d', strtotime("+$days days", strtotime($date)));
}

// חישוב תאריך תחילת השבוע הנוכחי
$startOfWeek = getStartOfWeek(date('Y-m-d'));

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $day = $_POST['day']; // keep day case as it is in the database
    $dayIndex = array_search($day, $daysOfWeek);
    $activityDate = addDays($startOfWeek, $dayIndex);

    if (isset($_POST['addShift'])) {
        $shiftType = $_POST['shiftType'];
        $value = ($shiftType === 'morning') ? 1 : 2;

        // Update morning_weekly table
        $stmt = $con->prepare("UPDATE morning_weekly SET `$day` = ? WHERE username = ?");
        $stmt->bind_param('is', $value, $username);
        $stmt->execute();

        // Insert into usershifts table
        $stmt = $con->prepare("INSERT INTO usershifts (username, ActivityDate, shiftType) VALUES (?, ?, ?)");
        $stmt->bind_param('ssi', $username, $activityDate, $value);
        $stmt->execute();
    } elseif (isset($_POST['deleteShift'])) {
        // Update morning_weekly table
        $stmt = $con->prepare("UPDATE morning_weekly SET `$day` = 0 WHERE username = ?");
        $stmt->bind_param('s', $username);
        $stmt->execute();

        // Delete from usershifts table
        $stmt = $con->prepare("DELETE FROM usershifts WHERE username = ? AND ActivityDate = ?");
        $stmt->bind_param('ss', $username, $activityDate);
        $stmt->execute();
    }
}

// Calculate the number of shifts each user has
$shiftCounts = [];
$queryShiftCounts = "SELECT username, (Sunday + Monday + Tuesday + Wednesday + Thursday + Friday + Saturday) as total_shifts FROM morning_weekly";
$resultShiftCounts = mysqli_query($con, $queryShiftCounts);

while ($row = mysqli_fetch_assoc($resultShiftCounts)) {
    $shiftCounts[$row['username']] = $row['total_shifts'];
}

// Calculate the number of employees working each day
$dayCounts = [];
foreach ($daysOfWeek as $day) {
    $queryDayCounts = "SELECT COUNT(*) as count FROM morning_weekly WHERE `$day` != 0";
    $resultDayCounts = mysqli_query($con, $queryDayCounts);
    $row = mysqli_fetch_assoc($resultDayCounts);
    $dayCounts[$day] = $row['count'];
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Weekly Shift Schedule</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background: #2c2c2c;
            color: #ffffff;
        }

        h2 {
            text-align: center;
            margin-top: 20px;
            color: #ffffff;
            font-size: 28px;
            border-bottom: 2px solid #2581DC;
            display: inline-block;
            padding-bottom: 10px;
        }

        table {
            width: 80%;
            margin: 40px auto;
            border-collapse: collapse;
            background-color: #3e3e3e;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.5);
        }

        th, td {
            border: 1px solid #555;
            padding: 12px;
            text-align: center;
            color: #ffffff;
        }

        th {
            background-color: #2581DC;
            color: #ffffff;
            font-weight: bold;
            font-size: 18px;
        }

        td {
            font-size: 16px;
        }

        .morning-shift { background-color: #4caf50; color: #ffffff; }
        .evening-shift { background-color: #ff9800; color: #ffffff; }
        .no-shift { background-color: #3e3e3e; color: #ffffff; }

        .shift-action-buttons form {
            display: inline-block;
            margin-top: 5px;
        }

        .shift-action-buttons button {
            padding: 5px 10px;
            margin: 2px;
            border: none;
            background-color: #2581DC;
            color: white;
            cursor: pointer;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .shift-action-buttons button.delete-btn {
            background-color: #ff4c4c;
        }
    </style>
</head>
<body>
    <h2>Weekly Shift Schedule</h2>
    <table>
        <thead>
            <tr>
                <th>User</th>
                <?php foreach ($daysOfWeek as $day): ?>
                    <th><?php echo $day; ?></th>
                <?php endforeach; ?>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['username']); ?></td>
                    <?php foreach ($daysOfWeek as $day): ?>
                        <td class="<?php echo $row[$day] == 1 ? 'morning-shift' : ($row[$day] == 2 ? 'evening-shift' : 'no-shift'); ?>">
                            <?php if ($row[$day] == 1): ?>
                                Morning
                            <?php elseif ($row[$day] == 2): ?>
                                Evening
                            <?php else: ?>
                                Off
                            <?php endif; ?>
                            
                            <div class="shift-action-buttons">
                                <?php if ($row[$day] == 0): ?>
                                    <form method="post" action="">
                                        <input type="hidden" name="username" value="<?php echo htmlspecialchars($row['username']); ?>">
                                        <input type="hidden" name="day" value="<?php echo $day; ?>">
                                        <select name="shiftType" required>
                                            <option value="morning">Morning</option>
                                            <option value="evening">Evening</option>
                                        </select>
                                        <button type="submit" name="addShift">Add</button>
                                    </form>
                                <?php endif; ?>
                                
                                <?php if ($row[$day] != 0): ?>
                                    <form method="post" action="">
                                        <input type="hidden" name="username" value="<?php echo htmlspecialchars($row['username']); ?>">
                                        <input type="hidden" name="day" value="<?php echo $day; ?>">
                                        <button type="submit" name="deleteShift" class="delete-btn">Delete</button>
                                    </form>
                                <?php endif; ?>
                            </div>
                        </td>
                    <?php endforeach; ?>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <!-- New Table for Shift Balancing -->
    <h2>Shift Balancing Recommendations</h2>
    <table>
        <thead>
            <tr>
                <th>Day</th>
                <th>Status</th>
                <th>Suggested Action</th>
                <th>User</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($daysOfWeek as $day): ?>
                <tr>
                    <td><?php echo $day; ?></td>
                    <td>
                        <?php
                                                $queryTotalUsers = "SELECT COUNT(DISTINCT username) AS total_users FROM morning_weekly";
                                                $resultTotalUsers = mysqli_query($con, $queryTotalUsers);
                                                $totalUsersRow = mysqli_fetch_assoc($resultTotalUsers);
                                                $totalUsers = $totalUsersRow['total_users'];
                                        
                                                if (isset($dayCounts[$day])) {
                                                    if ($dayCounts[$day] > ceil($totalUsers * 0.85)) {
                                                        echo "Overstaffed";
                                                    } elseif ($dayCounts[$day] < ceil($totalUsers * 0.7)) {
                                                        echo "Understaffed";
                                                    } else {
                                                        echo "Optimal";
                                                    }
                                                } else {
                                                    echo "Unknown";
                                                }
                                                ?>
                                            </td>
                                            <td>
                                            <?php
                                                $suggestedUser = null;
                        
                                                if (isset($dayCounts[$day])) {
                                                    if ($dayCounts[$day] > ceil($totalUsers * 0.85)) {
                                                        // Overstaffed: Find the user with the most shifts to remove
                                                        $maxShifts = max($shiftCounts);
                                                        $maxUsers = array_filter($shiftCounts, function($count) use ($maxShifts) {
                                                            return $count == $maxShifts;
                                                        });
                                                        $maxUser = array_key_first($maxUsers);
                                                        $suggestedUser = $maxUser;
                                                        echo "Remove";
                                                    } elseif ($dayCounts[$day] < ceil($totalUsers * 0.7)) {
                                                        // Understaffed: Check which shift type is most needed
                                                        $morningNeeded = ceil($totalUsers * 0.7 * 0.4); // 40% of 70% of total users
                                                        $eveningNeeded = ceil($totalUsers * 0.7 * 0.6); // 60% of 70% of total users
                        
                                                        $currentMorningShifts = 0;
                                                        $currentEveningShifts = 0;
                                                        $resultShifts = mysqli_query($con, "SELECT `$day` FROM morning_weekly");
                                                        while ($row = mysqli_fetch_assoc($resultShifts)) {
                                                            if ($row[$day] == 1) $currentMorningShifts++;
                                                            if ($row[$day] == 2) $currentEveningShifts++;
                                                        }
                        
                                                        $shiftTypeNeeded = ($currentMorningShifts < $morningNeeded) ? 1 : (($currentEveningShifts < $eveningNeeded) ? 2 : null);
                        
                                                        if ($shiftTypeNeeded !== null) {
                                                            // Find the user with the fewest shifts who does not work on that day
                                                            $minShifts = min($shiftCounts);
                                                            foreach ($shiftCounts as $user => $count) {
                                                                if ($count == $minShifts) {
                                                                    $userShiftQuery = "SELECT `$day` FROM morning_weekly WHERE username = ?";
                                                                    $stmt = $con->prepare($userShiftQuery);
                                                                    $stmt->bind_param('s', $user);
                                                                    $stmt->execute();
                                                                    $userShiftResult = $stmt->get_result();
                                                                    $userShiftRow = $userShiftResult->fetch_assoc();
                                                                    
                                                                    if ($userShiftRow[$day] == 0) {
                                                                        $suggestedUser = $user;
                                                                        echo "Add " . ($shiftTypeNeeded == 1 ? "Morning" : "Evening") . " Shift";
                                                                        break;
                                                                    }
                                                                }
                                                            }
                                                        } else {
                                                            echo "None Needed";
                                                        }
                                                    } else {
                                                        echo "None";
                                                    }
                                                } else {
                                                    echo "Unknown";
                                                }
                                            ?>
                                            </td>
                                            <td>
                                                <?php
                                                echo $suggestedUser ? htmlspecialchars($suggestedUser) : "No suitable user";
                                                ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </body>
                                            </br>                                            </br>
                                            </br>
                                            </br>

                        </html>
                        <?php
                        CloseCon($con); // Close the database connection
                        ?>
                        