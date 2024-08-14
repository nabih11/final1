<?php
include 'nav-fot.php';
include 'db_connection.php';

if (!isset($_SESSION["username"])) {
    header("Location: login.php");
    exit;
}

$username = $_SESSION["username"];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Save submitted schedule data into the session
    $_SESSION['scheduleData'] = $_POST;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cart - Schedule Confirmation</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 20px; background-color: #f4f4f4; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 8px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background-color: #4CAF50; color: white; }
        tr:hover { background-color: #f5f5f5; }
        .button { background-color: #4CAF50; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; font-size: 16px; }
        .button:hover { background-color: #45a049; }
    </style>
</head>
<body>
<form id="shiftForm" method="post" action="add shifts.php">
    <h1>Review Your Schedule</h1>
    <?php if (isset($_SESSION['scheduleData']) && is_array($_SESSION['scheduleData'])): ?>
        <table>
            <thead>
                <tr>
                    <th>Day</th>
                    <th>Shift Type</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($_SESSION['scheduleData'] as $day => $shiftType): ?>
                <tr>
                    <td><?php echo htmlspecialchars($day); ?></td>
                    <td><?php echo htmlspecialchars($shiftType); ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        
            <button type="submit" class="button">Confirm Schedule</button>
        </form>
    <?php else: ?>
        <p>No schedule data to review. Please <a href="products.php">submit your schedule</a>.</p>
    <?php endif; ?>
</body>
</html>
