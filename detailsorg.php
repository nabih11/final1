<?php

include 'nav-fot.php';
include "db_connection.php";

if (!isset($_SESSION["username"])) {
    header("Location: login.php");
    exit;
}

$username = $_SESSION["username"];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $_SESSION['scheduleData'] = $_POST; // Assuming $_POST contains the schedule data
} elseif (!isset($_SESSION['scheduleData'])) {
    echo "No schedule data to review.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Review and Confirm Schedule</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 20px; background-color: #f4f4f4; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { text-align: left; padding: 8px; border: 1px solid #ddd; }
        th { background-color: #4CAF50; color: white; }
        tr:nth-child(even) { background-color: #f2f2f2; }
        .button, .details-link { background-color: #4CAF50; color: white; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer; font-size: 16px; text-decoration: none; }
        .button:hover, .details-link:hover { background-color: #45a049; }
        #additionalDetails { display: none; }
    </style>
</head>
<body>

<h2>Review Your Schedule</h2>
<form action="add_shifts.php" method="post">
    <table>
        <thead>
            <tr>
                <th>Day</th>
                <th>Shift</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($_SESSION['scheduleData'] as $day => $shift): ?>
            <tr>
                <td><?= htmlspecialchars($day); ?></td>
                <td><?= htmlspecialchars($shift) == 'morning' ? 'Morning' : 'Evening'; ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <button type="submit" class="button">Confirm Schedule</button>
    <a href="#" class="details-link" onclick="showAdditionalDetails()">Organizer Details</a>
</form>

<div id="additionalDetails"></div>

<script>
function showAdditionalDetails() {
    const detailsDiv = document.getElementById('additionalDetails');
    let detailsHtml = '<h3>Next Week Schedule:</h3><table><tr><th>Date</th><th>Day</th><th>Shift Time</th></tr>';
    
    const days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
    const shiftTimes = {'morning': '8:30 - 16:00', 'evening': '16:00 - 23:30'};
    let startDate = new Date();
    startDate.setDate(startDate.getDate() + (7 - startDate.getDay()) % 7); // Adjust to the next Sunday
    
    for (let i = 0; i < days.length; i++) {
        const currentDate = new Date(startDate);
        currentDate.setDate(startDate.getDate() + i);
        const dayName = days[i];
        const shift = $_SESSION['scheduleData'][dayName.toLowerCase()] || 'morning';
        const shiftTime = shiftTimes[shift];
        
        detailsHtml += `<tr><td>${currentDate.toLocaleDateString()}</td><td>${dayName}</td><td>${shiftTime}</td></tr>`;
    }

    detailsHtml += '</table>';
    detailsDiv.innerHTML = detailsHtml;
    detailsDiv.style.display = 'block';
}
</script>

</body>
</html>
