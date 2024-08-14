<?php
include "db_connection.php";
include "nav-fot.php"
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="styles.css"> <!-- Link to the external stylesheet -->
    <style>
        body {
            font-family: Arial, sans-serif;
            margin-bottom: 150px;
            bottom: 100px;
            padding: 0;
            background: gray;
            color: black;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .background-left {
            background: url('lo1.png') no-repeat center center;
            position: absolute;
            top: 200px;
            left: 0px;
            right:500px;
            width: 50%;
            height: 100%;
        }

        .background-right {
            background: url('lo2.png') no-repeat center center;
           
            position: absolute;
            top: 200px;
            right: 0;
            width: 50%;
            height: 100%;
        }

        .content-wrapper {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            position: relative; /* Ensures the login container is above the background images */
            z-index: 1; /* Ensures the login container is above the background images */
        }

        

        .login-container {
            padding: 40px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 10px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
            text-align: center;
            max-width: 300px;
            width: 50%;
            margin: auto; /* Center horizontally */
            margin-top: 20px; /* Adjust to center vertically */
        }

        .login-container h2 {
            margin: 0 0 20px 0;
            font-size: 24px;
            font-weight: 400;
        }

        form {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        input[type="text"], input[type="password"] {
            width: 90%;
            padding: 10px;
            margin-bottom: 20px;
            border: none;
            border-radius: 5px;
            box-sizing: border-box;
            font-size: 16px;
        }

        input[type="submit"] {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            background-color: #667eea;
            color: white;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        input[type="submit"]:hover {
            background-color: #5a67d8;
        }

        a {
            color: #ffffff;
            text-decoration: none;
            margin-top: 20px;
            display: inline-block;
        }

       
    </style>
</head>

<body>
    <div class="background-left"></div>
    <div class="background-right"></div>

    

    
    <div class="content-wrapper">
        <div class="login-container">
            <h2>Login</h2>
            <form method="post" action="checkpass.php">
                <input type="text" id="username" name="username" required placeholder="Username"><br>
                <input type="password" id="password" name="password" required placeholder="Password"><br>
                <input type="submit" value="Login">
                <div class="form-footer">
                    <a href="password_reset.php">Forgot Password?</a>
                    <br>
                    <a href="http://localhost/project/signin.php">New? Register now</a>
                </div>
            </form>
        </div>
    </div>

    <footer class="footer">
        <p>© 2024 TEAM TIME ORGANIZER</p>
    </footer>
</body>

</html>
