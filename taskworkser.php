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

// Check if the user is an admin
$is_admin = false;
$query = "SELECT is_admin FROM user_table WHERE username = ?";
$stmt = $con->prepare($query);
$stmt->bind_param('s', $username);
$stmt->execute();
$result = $stmt->get_result();
if ($result && $row = $result->fetch_assoc()) {
    $is_admin = $row['is_admin'];
}

// Handle form submissions
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['request_time'])) {
        $task_id = $_POST['task_id'];
        // Update task to indicate a time extension request
        $updateTaskQuery = "UPDATE taskstable SET done = 2 WHERE id = ?";
        $stmt = $con->prepare($updateTaskQuery);
        $stmt->bind_param('i', $task_id);
        
        if ($stmt->execute()) {
            $message = "Time extension requested successfully.";
        } else {
            $message = "Failed to request time extension. Error: " . $stmt->error;
        }
    }

    if (isset($_POST['complete_task'])) {
        $task_id = $_POST['task_id'];
        // Update task to mark it as done
        $updateTaskQuery = "UPDATE taskstable SET done = 1 WHERE id = ?";
        $stmt = $con->prepare($updateTaskQuery);
        $stmt->bind_param('i', $task_id);
        
        if ($stmt->execute()) {
            $message = "Task marked as done.";
        } else {
            $message = "Failed to mark task as done. Error: " . $stmt->error;
        }
    }
}

// Fetch all approved swap requests (only if user is admin)
if ($is_admin) {
    $sql = "SELECT * FROM shift_swap_requests WHERE status = 'approved'";
    $result = $con->query($sql);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shift Swap Requests and Tasks</title>
    <style>
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
            color: black;
        }

        .approve-button {
            background-color: #4CAF50;
        }

        .complete-button {
            background-color: #f44336;
        }
    </style>
</head>
<body>

<div class="container">
<h2>Your Tasks</h2>
<?php if (isset($message)): ?>
    <div class="message"><?php echo $message; ?></div>
<?php endif; ?>

<div class="container">
    <?php
    // Fetch tasks for the logged-in user
    $taskQuery = "SELECT * FROM taskstable WHERE username = ?";
    $stmt = $con->prepare($taskQuery);
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $tasksResult = $stmt->get_result();

    if ($tasksResult->num_rows > 0) {
        echo "<table>";
        echo "<tr><th>Task</th><th>Time</th><th>Status</th><th>Actions</th></tr>";
        while ($task = $tasksResult->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($task['tasks']) . "</td>";
            echo "<td>" . htmlspecialchars($task['timetask']) . "</td>";
            echo "<td>" . ($task['done'] == 1 ? 'Completed' : ($task['done'] == 2 ? 'Time Extension Requested' : 'Pending')) . "</td>";
            echo "<td>";
            
            // Add button for requesting time extension (manager approval required)
            if ($task['done'] != 2 && $task['done'] != 1) {
                echo '<form method="post" action="' . htmlspecialchars($_SERVER["PHP_SELF"]) . '" style="display:inline;">
                        <input type="hidden" name="task_id" value="' . $task['id'] . '">
                        <button type="submit" name="request_time" class="button">Request Time Extension</button>
                      </form>';
            }
            
            // Add button for marking the task as done
            if ($task['done'] != 1) {
                echo '<form method="post" action="' . htmlspecialchars($_SERVER["PHP_SELF"]) . '" style="display:inline;">
                        <input type="hidden" name="task_id" value="' . $task['id'] . '">
                        <button type="submit" name="complete_task" class="button complete-button">Done</button>
                      </form>';
            }

            echo "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<div>No tasks found.</div>";
    }
    ?>
</div>

<!-- Show swap requests table only for admins -->
<?php if ($is_admin): ?>
    <h2>Shift Swap Requests</h2>
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
                        <button type="submit" name="delete_swap" class="button complete-button">Delete</button>
                    </form>
                  </td>';
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<div>No approved swap requests found.</div>";
    }
    ?>
<?php endif; ?>
</div>

</body>
</html>

<?php
CloseCon($con);
?>
