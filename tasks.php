<?php
include 'db_connection.php';
include 'nav-fot.php';
$con = OpenCon();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['delete_all'])) {
        $sql = "DELETE FROM taskstable";
        if ($con->query($sql) === TRUE) {
            $_SESSION['message'] = "All tasks deleted successfully";
        } else {
            $_SESSION['message'] = "Error: " . $sql . "<br>" . $con->error;
        }
    } elseif (isset($_POST['extend_time'])) {
        $task_id = $con->real_escape_string($_POST['id']);
        $sql = "UPDATE taskstable SET timetask = ADDTIME(timetask, '02:00:00') WHERE id = $task_id";
        if ($con->query($sql) === TRUE) {
            $_SESSION['message'] = "Task time extended successfully";
        } else {
            $_SESSION['message'] = "Error: " . $sql . "<br>" . $con->error;
        }
    } elseif (isset($_POST['delete_task'])) {
        $task_id = $con->real_escape_string($_POST['id']);
        $sql = "DELETE FROM taskstable WHERE id = $task_id";
        if ($con->query($sql) === TRUE) {
            $_SESSION['message'] = "Task deleted successfully";
        } else {
            $_SESSION['message'] = "Error: " . $sql . "<br>" . $con->error;
        }
    } else {
        if (!empty($_POST['username']) && !empty($_POST['time']) && !empty($_POST['task'])) {
            $username = $con->real_escape_string($_POST['username']);
            $time = $con->real_escape_string($_POST['time']);
            $task = $con->real_escape_string($_POST['task']);
            $done = isset($_POST['done']) ? 1 : 0;

            $sql = "INSERT INTO taskstable (username, timetask, tasks, done) VALUES ('$username', '$time', '$task', $done)";
            
            if ($con->query($sql) === TRUE) {
                $_SESSION['message'] = "New task added successfully";
                exit();
            } else {
                $_SESSION['message'] = "Error: " . $sql . "<br>" . $con->error;
            }
        } else {
            $_SESSION['message'] = "All fields except 'Done' are required.";
        }
    }
}

$message = isset($_SESSION['message']) ? $_SESSION['message'] : '';
unset($_SESSION['message']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task Form</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background: #2c2c2c;
            color: #ffffff;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .container {
            background-color: #3e3e3e;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.5);
            width: 90%;
            max-width: 600px;
            margin: 40px auto;
            text-align: center;
            color: #ffffff;
        }

        .container h2 {
            color: #ffffff;
            font-size: 28px;
            border-bottom: 2px solid #2581DC;
            padding-bottom: 10px;
        }

        .form-group {
            margin-bottom: 15px;
            text-align: left;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        .form-group input,
        .form-group textarea {
            width: calc(100% - 22px);
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
            background: #555;
            color: #ffffff;
        }

        .form-group textarea {
            resize: vertical;
            height: 100px;
        }

        .form-group button {
            background-color: #2581DC;
            color: white;
            border: none;
            padding: 10px 20px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            margin: 4px 2px;
            cursor: pointer;
            border-radius: 4px;
            width: 100%;
        }

        table {
            width: 90%;
            max-width: 800px;
            border-collapse: collapse;
            margin: 20px auto;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.5);
            border-radius: 8px;
            overflow: hidden;
            background-color: #3e3e3e;
            color: #ffffff;
        }

        table, th, td {
            border: 1px solid #555;
        }

        th, td {
            padding: 12px;
            text-align: left;
        }

        th {
            background-color: #2581DC;
        }

        tr:nth-child(even) {
            background-color: #555;
        }

        tr:hover {
            background-color: #666;
        }

        .message {
            color: #ff4c4c;
            text-align: center;
            margin-bottom: 15px;
        }

        .delete-all-container {
            text-align: center;
            margin: 20px;
        }

        .delete-all-container button {
            background-color: #ff4c4c;
            color: white;
            border: none;
            padding: 10px 20px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            margin: 4px 2px;
            cursor: pointer;
            border-radius: 4px;
        }

        .delete-button {
            background-color: #ff4c4c;
            color: white;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
            border-radius: 4px;
            margin-top: 5px;
        }

        .extend-button {
            background-color: #2581DC;
            color: white;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
            border-radius: 4px;
            margin-top: 5px;
            margin-right: 5px;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Task Form</h2>
    <?php if ($message): ?>
        <div class="message"><?php echo $message; ?></div>
    <?php endif; ?>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <div class="form-group">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>
        </div>
        
        <div class="form-group">
            <label for="time">Time:</label>
            <input type="time" id="time" name="time" required>
        </div>
        
        <div class="form-group">
            <label for="task">Task:</label>
            <textarea id="task" name="task" required></textarea>
        </div>
        
        <div class="form-group">
            <button type="submit">Submit</button>
        </div>
    </form>
</div>

<div class="delete-all-container">
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <button type="submit" name="delete_all">Delete All Tasks</button>
    </form>
</div>

<?php
$sql = "SELECT id, username, timetask, tasks, done FROM taskstable";
$result = $con->query($sql);

if ($result->num_rows > 0) {
    echo "<table>";
    echo "<tr><th>Username</th><th>Time</th><th>Task</th><th>Done</th><th>Action</th></tr>";
    while($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row['username'] . "</td>";
        echo "<td>" . $row['timetask'] . "</td>";
        echo "<td>" . $row['tasks'] . "</td>";
        echo "<td>" . ($row['done']==1 ? "Yes" : "No") . "</td>";
        echo '<td>
                <form action="' . htmlspecialchars($_SERVER["PHP_SELF"]) . '" method="post" style="display:inline;">
                    <input type="hidden" name="id" value="' . $row['id'] . '">';
        
            echo '<button type="submit" name="delete_task" class="delete-button">Delete Task</button>';
         if ($row['done']==2) {
            echo '<button type="submit" name="extend_time" class="extend-button">Extend Time</button>';
           
        }
        echo '</form>
              </td>';
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "<div class='container'>No tasks found.</div>";
}

CloseCon($con);
?>

<footer class="footer">
    <p>© 2024 TEAM TIME ORGANIZER</p>
</footer>

</body>
</br></br></br></br>
</html>
