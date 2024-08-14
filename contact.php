<?php include 'nav-fot.php'; ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us Form</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #2c2c2c; /* רקע כהה */
            color: #ffffff;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .container {
            background-color: #3e3e3e; /* רקע כהה לקונטיינר */
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.5);
            max-width: 400px;
            width: 100%;
            text-align: center;
            margin: auto; /* מרכז את הקונטיינר */
            flex: 1; /* מאפשר לקונטיינר להתפרס לגובה */
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
            font-size: 28px;
            color: #ffffff;
            border-bottom: 2px solid #2581DC;
            display: inline-block;
            padding-bottom: 10px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #ffffff;
            text-align: left;
        }

        input[type="text"] {
            width: 100%;
            padding: 12px;
            margin-bottom: 16px;
            border-radius: 6px;
            border: 1px solid #555;
            background-color: #555;
            color: #ffffff;
            font-size: 16px;
            transition: border-color 0.3s;
        }

        input[type="text"]:focus {
            border-color: #2581DC;
            outline: none;
        }

        button {
            background-color: #2581DC;
            color: #ffffff;
            padding: 12px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            width: 100%;
            font-size: 18px;
            transition: background-color 0.3s, transform 0.2s;
        }

        button:hover {
            background-color: #1a5fa1;
            transform: translateY(-2px);
        }

        button:active {
            transform: translateY(1px);
        }

        .error-message {
            color: #ff6961;
            margin-top: -10px;
            margin-bottom: 10px;
        }
    </style>
    <script>
        function validateEmailAndName() {
            var emailInput = document.getElementById('email');
            var nameInput = document.getElementById('name');
            var emailErrorDiv = document.getElementById('email-error-message');
            var nameErrorDiv = document.getElementById('name-error-message');

            var emailPattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;

            // Email validation
            if (!emailPattern.test(emailInput.value)) {
                emailErrorDiv.innerHTML = 'Email must have a valid format';
                return false;
            } else {
                emailErrorDiv.innerHTML = '';
            }

            // Name validation
            if (nameInput.value.length < 5 || nameInput.value.length > 20) {
                nameErrorDiv.innerHTML = 'Name must have between 5 and 20 characters';
                return false;
            } else {
                nameErrorDiv.innerHTML = '';
            }

            return true;
        }
    </script>
</head>

<body>

    <!-- הניווט שלך נשאר כמו שהוא -->

    <div class="container">
        <form action="printcontact.php" method="post" onsubmit="return validateEmailAndName()">
            <h2>Contact Us</h2>

            <label for="name">Name:</label>
            <input type="text" id="name" name="name" placeholder="Enter your name" required>
            <div id="name-error-message" class="error-message"></div>

            <label for="lname">Last name:</label>
            <input type="text" id="lname" name="lname" placeholder="Enter your last name" required>

            <label for="email">Email:</label>
            <input type="text" id="email" name="email" placeholder="Enter your email" required>
            <div id="email-error-message" class="error-message"></div>

            <label for="phnumber">Phone number:</label>
            <input type="text" id="phnumber" name="phnumber" placeholder="Enter your phone number" required>

            <label for="content">Content:</label>
            <input type="text" id="content" name="content" placeholder="Enter your interested content" required>

            <button type="submit">Submit</button>
        </form>
    </div>

</body>

</html>
