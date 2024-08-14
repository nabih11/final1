<?php
include 'db_connection.php';
include 'nav-fot.php'; 
$con = OpenCon();

$loggedInUsername = $_SESSION['username']; // Get the logged-in user's username

$daysOfWeek = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday']; // Define days of the week

// Get the days off for the logged-in user
$queryOffDays = "SELECT * FROM morning_weekly WHERE username = '$loggedInUsername'";
$resultOffDays = mysqli_query($con, $queryOffDays);
$offDays = [];
if ($row = mysqli_fetch_assoc($resultOffDays)) {
    foreach ($daysOfWeek as $day) {
        if ($row[($day)] == 0) { // 0 means day off
            $offDays[] = $day;
        }
    }
}

// Convert array to a format suitable for SQL IN clause
$offDaysString = "'" . implode("','", $offDays) . "'";

// Query to get swap requests where the requested day matches the user's off days
$query = "SELECT * FROM shift_swap_requests WHERE status = 'pending' AND request_day IN ($offDaysString)";
$result = mysqli_query($con, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Swap Requests</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 20px; background-color: #2c2c2c; color: #ffffff; }
        table { width: 80%; margin: 40px auto; border-collapse: collapse; background-color: #3e3e3e; box-shadow: 0 8px 16px rgba(0, 0, 0, 0.5); }
        th, td { border: 1px solid #555; padding: 12px; text-align: center; color: #ffffff; }
        th { background-color: #2581DC; color: #ffffff; font-weight: bold; font-size: 18px; }
        .approve-btn { background-color: #4caf50; color: #ffffff; padding: 10px; border: none; cursor: pointer; }
        .approve-btn:hover { background-color: #45a049; }
    </style>
</head>
<body>
    <h2>Open Swap Requests</h2>
    <table>
        <thead>
            <tr>
                <th>Requesting User</th>
                <th>Request Day</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['requesting_user']); ?></td>
                    <td><?php echo htmlspecialchars($row['request_day']); ?></td>
                    <td>
                        <form method="post" action="approve_swap.php">
                            <input type="hidden" name="swap_id" value="<?php echo $row['id']; ?>">
                            <button type="submit" name="approve_swap" class="approve-btn">Approve</button>
                        </form>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</body>
</html>
<?php
CloseCon($con);
?>
