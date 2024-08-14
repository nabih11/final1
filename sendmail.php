<html>
<head>
    <title>Sending HTML email using PHP</title>
</head>
<body>
    <?php
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

    require 'vendor/autoload.php';

    $mail = new PHPMailer(true);

    try {
        // הגדרות שרת
        $mail->SMTPDebug = 0;                      
        $mail->isSMTP();                           
        $mail->Host       = 'smtp.gmail.com';      
        $mail->SMTPAuth   = true;                  
        $mail->Username   = 'nabihmazzawi11@gmail.com'; 
        $mail->Password   = 'nabih124';  
        $mail->SMTPSecure = 'tls';                 
        $mail->Port       = 587;                   

        // נמענים
        $mail->setFrom('nabihmazzawi11@gmail.com', 'nabih');
        $mail->addAddress('nabihmazzawi11@gmail.com'); 

        // תוכן
        $mail->isHTML(true);                       
        $mail->Subject = 'This is subject';
        $mail->Body    = "<b>This is HTML message.</b><br><h1>This is headline.</h1>";
        $mail->AltBody = 'This is HTML message. This is headline.';

        $mail->send();
        echo 'Message sent successfully...';
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
    ?>
</body>
</html>
