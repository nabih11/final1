<?php
include "db_connection.php";
include 'nav-fot.php';
$con = OpenCon();



$username = $_SESSION['username'];
$user = null;

// Fetch user details
$stmt = $con->prepare("SELECT * FROM user_table WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows === 1) {
    $user = $result->fetch_assoc();
}
$stmt->close();

// Update password
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_password'])) {
    $newPassword = $_POST['new_password'];

    $stmtUpdate = $con->prepare("UPDATE user_table SET pass = ? WHERE username = ?");
    $stmtUpdate->bind_param("ss", $newPassword, $username);
    $stmtUpdate->execute();

    if ($stmtUpdate->affected_rows === 1) {
        echo "<script>alert('Password updated successfully');</script>";
        $_SESSION['newp']=0;
        echo "<script>window.location.href = 'login.php';</script>";
    } else {
        echo "<script>alert('Failed to update password');</script>";
    }
    $stmtUpdate->close();
}

CloseCon($con);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <style>
     .navbar {
            background-color: #333;
            overflow: hidden;
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 1000;
        }

        .navbar a {
            float: left;
            display: block;
            color: white;
            text-align: center;
            padding: 14px 16px;
            text-decoration: none;
        }

        .navbar a:hover {
            background-color: #ddd;
            color: black;
        }
        body, html {
            height: 120%;
            margin: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea, #764ba2);
            display: flex;
            justify-content: center;
            align-items: center;
            color: black;
        }

        .container {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        form, .user-details {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        label {
            font-weight: bold;
        }

        input[type="text"], input[type="password"], input[type="submit"] {
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
            font-size: 16px;
        }

        input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }

        .user-details p {
            margin: 5px 0;
            font-size: 16px;
        }
    </style>
</head>
<body>
    
    <div class="container">
        <?php if ($user): ?>
            <!-- User details and update password form -->
            <h1>Welcome, <?php echo htmlspecialchars($user['first_name']); ?></h1>
            <!-- Rest of the user details -->

            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <label for="new_password">New Password:</label>
                <input type="password" id="new_password" name="new_password" required>
                <input type="submit" name="update_password" value="Update Password">
            </form>
        <?php else: ?>
            <p>User data not found.</p>
        <?php endif; ?>
    </div>
</body>
</html>
