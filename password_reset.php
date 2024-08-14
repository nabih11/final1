<?php
include 'nav-fot.php'; 
include "db_connection.php";
$con = OpenCon();

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email']; 
    $newPassword = bin2hex(random_bytes(2)); 

    if (mysqli_connect_errno()) {
        $message = "Failed to connect to MySQL: " . mysqli_connect_error();
    } else {
        $query = "UPDATE user_table SET pass='$newPassword' WHERE email='$email'";
        if (mysqli_query($con, $query) && mysqli_affected_rows($con) > 0) {
            $to = $email;
            $subject = "Password Reset";
            $messageHTML = "<b>Your new password is:</b> $newPassword";
            $messageHTML .= "<p>Please change it after your next login.</p>";
            $header = "From:nabihmazzawi11@gmail.com \r\n";
            $header .= "MIME-Version: 1.0\r\n";
            $header .= "Content-type: text/html\r\n";

            if (mail($to, $subject, $messageHTML, $header)) {
                $message = "A new password has been sent to your email.";
                echo "<script>setTimeout(function(){ window.location.href = 'login.php'; }, 2000);</script>"; 
            } else {
                $message = "Failed to send the email with the new password.";
            }
        } else {
            $message = "Failed to reset password or email not found.";
        }
    }
    CloseCon($con);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <style>
      body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    background: #333;
    color: #ffffff;
    min-height: 100vh;
    display: flex;
    flex-direction: column;
}



.reset-password-container {
    background-color: #444;
    padding: 30px;
    border-radius: 10px;
    box-shadow: 0 0 15px rgba(0, 0, 0, 0.3);
    max-width: 400px;
    width: 100%;
    text-align: center;
    margin: auto;
}

.content {
    flex: 1;
    padding: 20px;
    display: flex;
    justify-content: center;
    align-items: center;
}

.footer {
    background-color: black;
    color: antiquewhite;
    padding: 5px;
    text-align: center;
    width: 100%;
}

    </style>
</head>
<body>
    <div class="reset-password-container">    
        <h2>Reset Your Password</h2>
        <?php if (!empty($message)): ?>
            <p class="message"><?= $message; ?></p>
        <?php endif; ?>
        <form method="post" action="password_reset.php">
            <input type="email" id="email" name="email" required placeholder="Enter your email">
            <input type="submit" value="Reset Password">
        </form>
    </div>
</body>
</html>
