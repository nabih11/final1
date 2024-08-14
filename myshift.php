<?php
include 'db_connection.php';
include 'nav-fot.php';
$con = OpenCon();

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$loggedInUsername = $_SESSION['username'];

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['request_day_off'])) {
    $request_day = $_POST['request_day'];
    
    // הוספת הבקשה לטבלת shift_swap_requests
    $stmt = $con->prepare("INSERT INTO shift_swap_requests (requesting_user, request_day, status) VALUES (?, ?, 'pending')");
    $stmt->bind_param('ss', $loggedInUsername, $request_day);
    
    if ($stmt->execute()) {
        $message = "Day off request submitted successfully!";
    } else {
        $message = "Failed to submit day off request.";
    }
}

$query = "SELECT * FROM morning_weekly WHERE username = '$loggedInUsername'";
$result = mysqli_query($con, $query);

$daysOfWeek = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];

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

        .message {
            text-align: center;
            color: #4caf50;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <h2>Weekly Shift Schedule for <?php echo htmlspecialchars($loggedInUsername); ?></h2>
    
    <?php if (isset($message)): ?>
        <div class="message"><?php echo $message; ?></div>
    <?php endif; ?>
    
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
            <?php if ($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['username']); ?></td>
                    <?php foreach ($daysOfWeek as $day): ?>
                        <td class="<?php echo $row[($day)] == 1 ? 'morning-shift' : ($row[($day)] == 2 ? 'evening-shift' : 'no-shift'); ?>">
                            <?php echo $row[($day)] == 1 ? 'Morning' : ($row[($day)] == 2 ? 'Evening' : 'Off'); ?>
                            <br>
                            <?php if ($row[($day)] != 0): ?>
                                <form method="post" action="">
                                    <input type="hidden" name="request_day" value="<?php echo $day; ?>">
                                    <button type="submit" name="request_day_off">Request Day Off</button>
                                </form>
                            <?php endif; ?>
                        </td>
                    <?php endforeach; ?>
                </tr>
            <?php else: ?>
                <tr>
                    <td colspan="<?php echo count($daysOfWeek) + 1; ?>">No schedule found for <?php echo htmlspecialchars($loggedInUsername); ?></td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>
<?php
CloseCon($con);
?>
