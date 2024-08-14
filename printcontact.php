


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Submission Result</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #282c35;
            color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            flex-direction: column;
        }

        h1, h2 {
            padding: 10px;
            margin: 5px 0;
        }

        h1 {
            color: #61dafb;
        }
    </style>
</head>

<body>
    <?php
    session_start();
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $firstName = $_POST["name"];
        $lastName = $_POST["lname"];
        $userEmail = $_POST["email"];
        $phoneNumber = $_POST["phnumber"];
        $userContent = $_POST["content"];

        echo "<h1>thank you Form submitted youer massege successfully</h1>";
        echo "<h2>Name: $firstName</h2>";
        echo "<h2>Last name: $lastName</h2>";
        echo "<h2>Email:  $userEmail</h2>";
        echo "<h2>Phone number: $phoneNumber</h2>";
        echo "<h2>Interested content:  $userContent</h2>";
    }
 
    ?>
</body>

</html>
