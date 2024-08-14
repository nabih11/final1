<?php
session_start();
include 'db_connection.php';
$con = OpenCon();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $requesting_user = $_POST['requesting_user'];
    $request_day = $_POST['request_day'];

    // Insert the day off request into the shift_swap_requests table
    $stmt = $con->prepare("INSERT INTO shift_swap_requests (requesting_user, request_day, status) VALUES (?, ?, 'pending')");
    $stmt->bind_param('ss', $requesting_user, $request_day);

    if ($stmt->execute()) {
        echo "Day off request submitted successfully!";
    } else {
        echo "Failed to submit day off request.";
    }

    header("Location: .php");
    exit;
}

CloseCon($con);
?>
