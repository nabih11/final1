<?php
session_start();
include 'db_connection.php';
$con = OpenCon();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['request_swap'])) {
    $requesting_user = $_SESSION['username'];
    $request_day = $_POST['request_day'];
    $target_day = $_POST['target_day'];

    $query = "INSERT INTO shift_swap_requests (requesting_user, request_day, target_day, status) VALUES (?, ?, ?, 'pending')";
    $stmt = $con->prepare($query);
    $stmt->bind_param('sss', $requesting_user, $request_day, $target_day);
    if ($stmt->execute()) {
        header("Location: shift_swap.php");
        exit;
    } else {
        echo "Error: Could not submit the swap request.";
    }
}

CloseCon($con);
?>
