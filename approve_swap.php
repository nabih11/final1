<?php
session_start();
include 'db_connection.php';
$con = OpenCon();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['approve_swap'])) {
    $swap_id = $_POST['swap_id'];
    $username = $_SESSION['username'];

    // Fetch the swap request details
    $query = "SELECT * FROM shift_swap_requests WHERE id = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param('i', $swap_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $swap_request = $result->fetch_assoc();

    if (!$swap_request) {
        echo "Invalid swap request.";
        exit;
    }

    $requesting_user = $swap_request['requesting_user'];
    $request_day = $swap_request['request_day'];
    

    // Start the transaction
    $con->begin_transaction();
    try {
        // Update the logged-in user's shifts
        $stmt1 = $con->prepare("UPDATE morning_weekly SET $request_day = 0 WHERE username = ?");
        $stmt1->bind_param('s', $username);
        $stmt1->execute();

        // Update the requesting user's shifts
        $stmt2 = $con->prepare("UPDATE morning_weekly SET $request_day = 1 WHERE username = ?");
        $stmt2->bind_param('s', $requesting_user);
        $stmt2->execute();

        // Update the status of the swap request and save the approving user's name in the target_user field
        $stmt3 = $con->prepare("UPDATE shift_swap_requests SET status = 'approved', target_user = ? WHERE id = ?");
        $stmt3->bind_param('si', $username, $swap_id);
        $stmt3->execute();

        // Commit the transaction
        $con->commit();
        header("Location: view_swap_requests.php");
        exit;
    } catch (Exception $e) {
        // Rollback the transaction in case of an error
        $con->rollback();
        echo "Swap failed: " . $e->getMessage();
    }
}

CloseCon($con);
?>
