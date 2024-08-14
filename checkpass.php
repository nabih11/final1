<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

$con = mysqli_connect("localhost", "root", "1234", "team time orginazer project");
if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
    exit();
}

session_start();
if (!isset($_SESSION['newp'])) {
    $_SESSION['newp'] = 0;
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $enteredUsername = mysqli_real_escape_string($con, $_POST['username']);
    $enteredPassword = mysqli_real_escape_string($con, $_POST['password']);
    $_SESSION["username"] = $enteredUsername;
    $checkLock = "SELECT email, looked, login_attempts FROM user_table WHERE username = '$enteredUsername'";
    $lockResult = mysqli_query($con, $checkLock);

    if ($row = mysqli_fetch_array($lockResult)) {
        if ($row['looked'] == 0) {
            echo "<div class='error-message'>Your account has been locked.</div>";
        } else if ($row['login_attempts'] >= 3) {
            // Update the account to be locked
            $lockAccount = "UPDATE user_table SET looked = 0, lastlog='locked' WHERE username = '$enteredUsername'";
            mysqli_query($con, $lockAccount);
            $newPassword = bin2hex(random_bytes(2)); // צור סיסמא רנדומלית

            $query = "UPDATE user_table SET pass='$newPassword' WHERE username='$enteredUsername'";
            if (mysqli_query($con, $query) && mysqli_affected_rows($con) > 0) {
                $to = $row['email'];
                $subject = "Account Locked";
                $message = "<b>Your account has been locked due to multiple failed login attempts.</b><br><b>Your new password is:</b> $newPassword";
                $headers = "From: nabihmazzawi11@gmail.com \r\n";
                $headers .= "MIME-Version: 1.0\r\n";
                $headers .= "Content-type: text/html\r\n";

                $mail = new PHPMailer(true);

                try {
                    // Server settings
                    $mail->SMTPDebug = 0;                      // Enable verbose debug output
                    $mail->isSMTP();                           // Set mailer to use SMTP
                    $mail->Host       = 'smtp.gmail.com';      // Specify main and backup SMTP servers
                    $mail->SMTPAuth   = true;                  // Enable SMTP authentication
                    $mail->Username   = 'nabihmazzawi11@gmail.com'; // SMTP username
                    $mail->Password   = 'nabih124$';  // SMTP password
                    $mail->SMTPSecure = 'tls';                 // Enable TLS encryption, `ssl` also accepted
                    $mail->Port       = 587;                   // TCP port to connect to

                    // Recipients
                    $mail->setFrom('nabihmazzawi11@gmail.com', 'Your Name');
                    $mail->addAddress($to); // Add a recipient

                    // Content
                    $mail->isHTML(true);                       // Set email format to HTML
                    $mail->Subject = $subject;
                    $mail->Body    = $message;
                    $mail->AltBody = strip_tags($message);

                    $mail->send();
                    $_SESSION['newp'] = 1;

                    $loginMessage = "Your account has been locked. Enter your new password that we sent.";
                    header('Refresh:2;url=login.php');
                    $resetAttempts = "UPDATE user_table SET login_attempts = 0, looked = 1 WHERE username = '$enteredUsername'";
                    mysqli_query($con, $resetAttempts);
                } catch (Exception $e) {
                    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
                }
            }
        } else {
            $query = "SELECT * FROM user_table WHERE username = '$enteredUsername' AND pass = '$enteredPassword'";
            $result = mysqli_query($con, $query);

            if (mysqli_num_rows($result) > 0) {
                $row = mysqli_fetch_assoc($result);
                if ($row['is_admin'] == 1) {
                    if ($_SESSION['newp'] == 1) {
                        echo '<script>alert("you must change your password");</script>';
                        echo '<meta http-equiv="refresh" content="2;url= must.php">';
                        exit;
                    } else {
                        $_SESSION["loggedin"] = true;
                        $_SESSION["username"] = $enteredUsername;
                        $resetAttempts = "UPDATE user_table SET login_attempts = 0, secc = secc + 1, lastlog='correct', cdate= CURRENT_DATE() WHERE username = '$enteredUsername'";
                        $_SESSION["user"] = $enteredUsername;
                        mysqli_query($con, $resetAttempts);
                        echo '<script>alert("you entered as an admin");</script>';
                        echo '<meta http-equiv="refresh" content="2;url=myshift.php">';
                        exit;
                    }
                } else {
                    if ($_SESSION['newp'] == 1) {
                        echo '<script>alert("you must change your password");</script>';
                        echo '<meta http-equiv="refresh" content="2;url= must.php">';
                        exit;
                    } else {
                        $_SESSION["loggedin"] = true;
                        $_SESSION["username"] = $enteredUsername;
                        $resetAttempts = "UPDATE user_table SET login_attempts = 0, secc = secc + 1, cnt = cnt + 1, lastlog='correct', cdate= CURRENT_DATE() WHERE username = '$enteredUsername'";
                        $_SESSION["user"] = $enteredUsername;
                        mysqli_query($con, $resetAttempts);
                        echo '<script>alert("you entered as a user");</script>';
                        echo '<meta http-equiv="refresh" content="2;url=myshift.php">';
                        exit;
                    }
                }
            } else {
                $loginMessage = "Invalid username or password.";
                $updateAttempts = "UPDATE user_table SET login_attempts = login_attempts + 1, err = err + 1, cnt = cnt + 1, lastlog='correct' WHERE username = '$enteredUsername'";
                echo '<script>alert("wrong password")</script>';
                header('Refresh:1;url=login.php');
                mysqli_query($con, $updateAttempts);
                echo "<div class='error-message'>Invalid username or password.</div>";
            }
        }
    } else {
        echo "<div class='error-message'>Invalid username or password.</div>";
    }
}

mysqli_close($con);
?>
