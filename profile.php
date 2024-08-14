<?php
include 'nav-fot.php';
require_once "db_connection.php";
$con = OpenCon();

if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];

    // Fetch user details
    $query = "SELECT * FROM user_table WHERE username = ?";
    $stmt = mysqli_prepare($con, $query);
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $user = mysqli_fetch_assoc($result);

    // Update password
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_password'])) {
        $newPassword = $_POST['new_password'];

        $updateQuery = "UPDATE user_table SET pass = ? WHERE username = ?";
        $stmtUpdate = mysqli_prepare($con, $updateQuery);
        mysqli_stmt_bind_param($stmtUpdate, "ss", $newPassword, $username);
        mysqli_stmt_execute($stmtUpdate);

        if (mysqli_stmt_affected_rows($stmtUpdate) > 0) {
            echo "<script>alert('Password updated successfully');</script>";
        } else {
            echo "<script>alert('Failed to update password');</script>";
        }
    }
} else {
    // Redirect to login page if not logged in
    header("Location: login.php");
    exit;
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
       body {
    font-family: 'Arial', sans-serif;
    margin: 0;
    padding: 0;
    background-color: #2c2c2c; /* רקע כהה */
    color: #ffffff;
    display: flex;
    flex-direction: column;
    align-items: center;
    min-height: 100vh;
    padding-top: var(--nav-height); /* מאפשר מרווח מתחת לניווט */
}

h1 {
    color: #ffffff;
    margin-top: 20px;
    font-size: 32px;
    border-bottom: 2px solid #2581DC;
    display: inline-block;
    padding-bottom: 10px;
}

p {
    font-size: 18px;
    margin: 5px 0;
    color: #ffffff;
}

.profile-container {
    background-color: #3e3e3e; /* רקע כהה לקונטיינר */
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.5);
    width: 50%;
    max-width: 600px;
    text-align: left;
    margin-top: 20px;
}

form {
    margin-top: 20px;
}

label {
    font-size: 16px;
    color: #ffffff;
}

input[type="password"] {
    width: 100%;
    padding: 10px;
    margin-top: 10px;
    margin-bottom: 20px;
    border: 1px solid #555;
    border-radius: 5px;
    background-color: #555;
    color: #ffffff;
    font-size: 16px;
    transition: border-color 0.3s;
}

input[type="password"]:focus {
    border-color: #2581DC;
    outline: none;
}

input[type="submit"] {
    background-color: #2581DC;
    color: #ffffff;
    padding: 10px 20px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-size: 18px;
    transition: background-color 0.3s;
}

input[type="submit"]:hover {
    background-color: #1a5fa1;
}

    </style>
</head>
<body>
    <div class="profile-container">
        <h1>Welcome <?php echo htmlspecialchars($user['first_name']); ?></h1>
        <p>Username: <?php echo htmlspecialchars($user['username']); ?></p>
        <p>Email: <?php echo htmlspecialchars($user['email']); ?></p>
        <p>Phone: <?php echo htmlspecialchars($user['phone']); ?></p>
        <p>First Name: <?php echo htmlspecialchars($user['first_name']); ?></p>
        <p>Last Name: <?php echo htmlspecialchars($user['last_name']); ?></p>
        
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <label for="new_password">New Password:</label>
            <input type="password" id="new_password" name="new_password" required>
            <input type="submit" name="update_password" value="Update Password">
        </form>
    </div>
</body>
</html>
