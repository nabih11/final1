<?php
include 'db_connection.php';
$con = OpenCon();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $requestingUser = $_POST['requesting_user'];
    $requestDay = $_POST['request_day'];
    

    // Prepare an SQL statement to insert the swap request into the database
    $query = "INSERT INTO shift_swap_requests (requesting_user, request_day, status) VALUES (?, ?, ?, 'pending')";
    $stmt = $con->prepare($query);
    $stmt->bind_param("sss", $requestingUser, $requestDay);

    if ($stmt->execute()) {
        echo "Swap request submitted successfully.";
        // You can redirect the user back to the schedule page or any other page
        header("Location: shift_swap.php");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

CloseCon($con);
?>
