<?php
include 'nav-fot.php'; 
include "db_connection.php";

$con=OpenCon();

$username=$_SESSION['user'];
$x ="SELECT * FROM user_table WHERE username='$username'";
$result = mysqli_query($con, $x);
echo "<br>";
?>

<!DOCTYPE html>
<html lang="en">
<head>
   
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Details</title>
    <style>
        /* styles.css */
        /* Navigation Variables */
        :root {
            --content-width: 1000px;
            --breakpoint: 799px;
            --nav-height: 70px;
            --nav-background: #262626;
            --nav-font-color: #ffffff;
            --link-hover-color: #2581DC;
        }

        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background: gray;
            color: black;
        }

        .logo-container {
            text-align: center;
            padding: 20px;
            background-color: var(--nav-background);
        }

        .logo {
            height: 100px;
            width: 100px;
            border-radius: 50%;
            background-color: #ffffff;
        }

        .navigation {
            height: var(--nav-height);
            background: var(--nav-background);
            position: relative;
        }

        .brand {
            position: absolute;
            padding-left: 20px;
            line-height: var(--nav-height);
            text-transform: uppercase;
            font-size: 1.4em;
        }

        .brand a,
        .brand a:visited {
            color: var(--nav-font-color);
            text-decoration: none;
        }

        .nav-container {
            max-width: var(--content-width);
            margin: 0 auto;
        }

        nav {
            float: right;
        }

        nav ul {
            list-style: none;
            margin: 0;
            padding: 0;
        }

        nav ul li {
            float: left;
            position: relative;
        }

        nav ul li a,
        nav ul li a:visited {
            display: block;
            padding: 0 20px;
            line-height: var(--nav-height);
            background: var(--nav-background);
            color: var(--nav-font-color);
            text-decoration: none;
            transition: background-color 0.3s, color 0.3s;
        }

        nav ul li a:hover {
            background: var(--link-hover-color);
            color: var(--nav-font-color);
        }

        nav ul li a:not(:only-child):after {
            padding-left: 4px;
            content: ' ▾';
        }

        .nav-dropdown {
            position: absolute;
            display: none;
            z-index: 1;
            box-shadow: 0 3px 12px rgba(0, 0, 0, 0.15);
        }

        .nav-dropdown ul li {
            min-width: 190px;
        }

        .nav-dropdown ul li a {
            padding: 15px;
            line-height: 20px;
        }

        .nav-mobile {
            display: none;
            position: absolute;
            top: 0;
            right: 0;
            background: var(--nav-background);
            height: var(--nav-height);
            width: var(--nav-height);
        }

        @media only screen and (max-width: 798px) {
            .nav-mobile {
                display: block;
            }

            nav {
                width: 100%;
                padding: var(--nav-height) 0 15px;
            }

            nav ul {
                display: none;
            }

            nav ul li {
                float: none;
            }

            nav ul li a {
                padding: 15px;
                line-height: 20px;
            }

            nav ul li ul li a {
                padding-left: 30px;
            }

            .nav-dropdown {
                position: static;
            }
        }

        @media screen and (min-width: var(--breakpoint)) {
            .nav-list {
                display: block !important;
            }
        }

        #nav-toggle {
            position: absolute;
            left: 18px;
            top: 22px;
            cursor: pointer;
            padding: 10px 35px 16px 0px;
        }

        #nav-toggle span,
        #nav-toggle span:before,
        #nav-toggle span:after {
            cursor: pointer;
            border-radius: 1px;
            height: 5px;
            width: 35px;
            background: var(--nav-font-color);
            position: absolute;
            display: block;
            content: '';
            transition: all 300ms ease-in-out;
        }

        #nav-toggle span:before {
            top: -10px;
        }

        #nav-toggle span:after {
            bottom: -10px;
        }

        #nav-toggle.active span {
            background-color: transparent;
        }

        #nav-toggle.active span:before,
        #nav-toggle.active span:after {
            top: 0;
        }

        #nav-toggle.active span:before {
            transform: rotate(45deg);
        }

        #nav-toggle.active span:after {
            transform: rotate(-45deg);
        }

        .footer {
            background-color: black;
            color: antiquewhite;
            padding: 10px;
            text-align: center;
            position: fixed;
            bottom: 0;
            width: 100%;
        }

        article {
            max-width: var(--content-width);
            margin: 0 auto;
            padding: 10px;
        }


           /* Existing CSS */
    /* ... */
    
    /* Enhanced table styling */
    .container {
        margin: 20px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
        background-color: #ffffff;
        color: #333333;
    }

    table, th, td {
        border: 1px solid #dddddd;
    }

    th, td {
        padding: 15px;
        text-align: left;
    }

    th {
        background-color: #333333;
        color: white;
        text-transform: uppercase;
    }

    tr:nth-child(even) {
        background-color: #f2f2f2;
    }

    tr:hover {
        background-color: #eaeaea;
    }
    </style>
</head>
<body>
<div class="container">
    <table>
        <tr>
            <th>Username</th>
            <th>Password</th>
            <th>Firstname</th>
            <th>Lastname</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Birthday</th>
       
        </tr>
        <?php
        while($row = mysqli_fetch_array($result))
        {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row['username']) . "</td>";
            echo "<td>" . htmlspecialchars($row['pass']) . "</td>";
            echo "<td>" . htmlspecialchars($row['first_name']) . "</td>";
            echo "<td>" . htmlspecialchars($row['last_name']) . "</td>";
            echo "<td>" . htmlspecialchars($row['email']) . "</td>";
            echo "<td>" . htmlspecialchars($row['phone']) . "</td>";
            echo "<td>" . htmlspecialchars($row['birtday_user']) . "</td>";
        
            echo "</tr>";
        }
        ?>
    </table>
</div>

<?php
CloseCon($con); // Close database connection
?>
</body>
</html>
