<?php include 'nav-fot.php';
include 'db_connection.php';
$con=OpenCon();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background: #2c2c2c; /* רקע כהה */
            color: #ffffff;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .container {
            background-color: #3e3e3e; /* רקע כהה לקונטיינר */
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.5);
            max-width: 450px;
            width: 100%;
            margin: auto;
            margin-top: 50px;
            text-align: center;
        }

        h2 {
            margin-bottom: 20px;
            font-size: 28px;
            color: #ffffff;
            border-bottom: 2px solid #2581DC;
            display: inline-block;
            padding-bottom: 10px;
        }

        input[type="text"],
        input[type="email"],
        input[type="tel"],
        input[type="date"],
        input[type="password"] {
            width: 100%;
            padding: 12px;
            margin: 15px 0;
            border-radius: 6px;
            border: 1px solid #555;
            background-color: #555;
            color: #ffffff;
            font-size: 16px;
            transition: border-color 0.3s;
        }

        input[type="text"]:focus,
        input[type="email"]:focus,
        input[type="tel"]:focus,
        input[type="date"]:focus,
        input[type="password"]:focus {
            border-color: #2581DC;
            outline: none;
        }

        input[type="submit"] {
            width: 100%;
            padding: 12px;
            margin: 20px 0;
            border-radius: 6px;
            border: none;
            background-color: #2581DC; /* צבע כפתור כחול */
            color: #ffffff;
            font-size: 18px;
            cursor: pointer;
            transition: background-color 0.3s, transform 0.2s;
        }

        input[type="submit"]:hover {
            background-color: #1a5fa1; /* צבע כהה יותר לכפתור בהובר */
            transform: translateY(-2px);
        }

        input[type="submit"]:active {
            transform: translateY(1px);
        }

        .error-field {
            border-color: #ff4d4d; /* גבול אדום במקרה של שגיאה */
        }

        .message {
            color: #ff4d4d; /* צבע הודעת שגיאה */
            margin-bottom: 20px;
            font-size: 16px;
        }
        
        .navigation {
            width: 100%;
            background: #262626;
            position: relative;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px 40px;
            box-sizing: border-box;
            z-index: 1000;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Sign Up</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <input type="text" name="username" placeholder="Username" required>
            <input type="text" name="first_name" placeholder="First Name" required>
            <input type="text" name="last_name" placeholder="Last Name" required>
            <input type="email" name="email" placeholder="Mail" required>
            <input type="tel" name="phone" placeholder="Phone Number" required>
            <input type="date" name="birthday_user" required>
            <input type="password" name="password" placeholder="Password" required class="<?php if ($_SERVER["REQUEST_METHOD"] == "POST" && $_POST['password'] !== $_POST['confirm_password']) echo 'error-field'; ?>">
            <input type="password" name="confirm_password" placeholder="Confirm Password" required class="<?php if ($_SERVER["REQUEST_METHOD"] == "POST" && $_POST['password'] !== $_POST['confirm_password']) echo 'error-field'; ?>">
            <?php


// Function to check if the username already exists
function usernameExists($con, $username) {
    $stmt = $con->prepare("SELECT username FROM user_table WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $exists = $result->num_rows > 0;
    $stmt->close();
    return $exists;
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $birthday_user = $_POST['birthday_user'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    
    // Check if the passwords match
    if ($password !== $confirm_password) {
        $message = "Passwords do not match.";
    } else if (usernameExists($con, $username)) {
        $message = "Username already exists. Choose another one.";
    } else {
        // Hash the password
        $hashed_password = $password;

        // Insert into user_table
        $stmt = $con->prepare("INSERT INTO user_table (username, pass, email, phone, first_name, last_name, birtday_user, looked, login_attempts, is_admin,salary) VALUES (?, ?, ?, ?, ?, ?, ?, 1, 0, 0,30)");
        $stmt->bind_param("sssssss", $username, $hashed_password, $email, $phone, $first_name, $last_name, $birthday_user);
        
        // Execute and check for success
        if ($stmt->execute()) {
            // Insert into morning_weekly
            $stmt2 = $con->prepare("INSERT INTO morning_weekly (username) VALUES (?)");
            $stmt2->bind_param("s", $username);
            
            if ($stmt2->execute()) {
                // Success - Redirect to login page
                $message = "Registration successful. Redirecting to login page...";
                echo "<script>alert('$message');</script>";
                echo '<meta http-equiv="refresh" content="2;url=login.php">';
            } else {
                $message = "Error: " . $stmt2->error;
            }
            $stmt2->close();
        } else {
            $message = "Error: " . $stmt->error;
        }
        $stmt->close();
    }
}

CloseCon($con);
?>
            <input type="submit" value="Send">
        </form>
    </div>
</body>
</html>
