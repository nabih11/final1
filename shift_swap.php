<?php
include 'db_connection.php';
include 'nav-fot.php';
$con = OpenCon();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['username'];

// Handle the approval of a swap request
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['approve_swap'])) {
        $swap_id = $_POST['swap_id'];
        
        // Get details of the swap request
        $query = "SELECT * FROM shift_swap_requests WHERE id = ?";
        $stmt = $con->prepare($query);
        $stmt->bind_param('i', $swap_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $swap_request = $result->fetch_assoc();

        $requesting_user = $swap_request['requesting_user'];
        $target_user = $swap_request['target_user'];
        $request_day = strtolower($swap_request['request_day']);

        // Start transaction to swap shifts
        $con->begin_transaction();
        try {
            // Step 1: Get the shift value for the requesting user
            $stmt1 = $con->prepare("SELECT $request_day FROM morning_weekly WHERE username = ?");
            $stmt1->bind_param('s', $requesting_user);
            $stmt1->execute();
            $result1 = $stmt1->get_result();
            $row1 = $result1->fetch_assoc();
            $requesting_user_shift_value = $row1[$request_day];

            // Step 2: Get the shift value for the target user
            $stmt2 = $con->prepare("SELECT $request_day FROM morning_weekly WHERE username = ?");
            $stmt2->bind_param('s', $target_user);
            $stmt2->execute();
            $result2 = $stmt2->get_result();
            $row2 = $result2->fetch_assoc();
            $target_user_shift_value = $row2[$request_day];

            // Step 3: Update the shifts for both users
            $stmt3 = $con->prepare("UPDATE morning_weekly SET $request_day = ? WHERE username = ?");
            $stmt3->bind_param('is', $target_user_shift_value, $requesting_user);
            $stmt3->execute();

            $stmt4 = $con->prepare("UPDATE morning_weekly SET $request_day = ? WHERE username = ?");
            $stmt4->bind_param('is', $requesting_user_shift_value, $target_user);
            $stmt4->execute();

            // Step 4: Update the swap request status to 'completed'
            $stmt5 = $con->prepare("UPDATE shift_swap_requests SET status = 'completed' WHERE id = ?");
            $stmt5->bind_param('i', $swap_id);
            $stmt5->execute();

            $con->commit();
            $message = "Swap approved and completed successfully.";
        } catch (Exception $e) {
            $con->rollback();
            $message = "Swap failed: " . $e->getMessage();
        }
    }

    // Handle the deletion of a swap request
    if (isset($_POST['delete_swap'])) {
        $swap_id = $_POST['swap_id'];
        $stmt = $con->prepare("DELETE FROM shift_swap_requests WHERE id = ?");
        $stmt->bind_param('i', $swap_id);
        if ($stmt->execute()) {
            $message = "Swap request deleted successfully.";
        } else {
            $message = "Failed to delete the swap request.";
        }
    }
}

// Fetch all approved swap requests
$sql = "SELECT * FROM shift_swap_requests WHERE status = 'approved'";
$result = $con->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shift Swap Requests</title>
    <style>
        /* Your existing styles */
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            overflow: hidden;
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 12px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        .message {
            color: red;
            text-align: center;
            margin-bottom: 15px;
        }

        .button {
            padding: 8px 12px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            color: white;
        }

        .approve-button {
            background-color: #4CAF50;
        }

        .delete-button {
            background-color: #f44336;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Shift Swap Requests</h2>
    <?php if (isset($message)): ?>
        <div class="message"><?php echo $message; ?></div>
    <?php endif; ?>

    <?php
    if ($result->num_rows > 0) {
        echo "<table>";
        echo "<tr><th>ID</th><th>Requesting User</th><th>Target User</th><th>Request Day</th><th>Status</th><th>Actions</th></tr>";
        while($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row['id'] . "</td>";
            echo "<td>" . $row['requesting_user'] . "</td>";
            echo "<td>" . $row['target_user'] . "</td>";
            echo "<td>" . $row['request_day'] . "</td>";
            echo "<td>" . $row['status'] . "</td>";
            echo '<td>
                    <form method="post" action="' . htmlspecialchars($_SERVER["PHP_SELF"]) . '" style="display:inline;">
                        <input type="hidden" name="swap_id" value="' . $row['id'] . '">
                        <button type="submit" name="approve_swap" class="button approve-button">Approve</button>
                    </form>
                    <form method="post" action="' . htmlspecialchars($_SERVER["PHP_SELF"]) . '" style="display:inline;">
                        <input type="hidden" name="swap_id" value="' . $row['id'] . '">
                        <button type="submit" name="delete_swap" class="button delete-button">Delete</button>
                    </form>
                  </td>';
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<div>No approved swap requests found.</div>";
    }

    CloseCon($con);
    ?>
</div>

</body>
</html>
