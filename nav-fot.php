<?php
session_start();
include "db_connection.php"; 
$con = OpenCon();
?>
<!DOCTYPE html>
<html lang="he">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Navbar/Footer</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        /* styles.css */
        /* Navigation Variables */
        :root {
            --content-width: 100%;
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
            padding-top:40px;
            padding-bottom:20px;
        }

        .logo-container {
            text-align: left; /* Align logo to the left */
            padding: 20px;
            background-color: var(--nav-background);
            display: flex;
            align-items: center;
        }

        .logo {
            height: 70px; /* Adjust height as needed */
            width: 70px;  /* Adjust width as needed */
            border-radius: 50%;
            background-color: #ffffff;
            margin-right: 20px; /* Space between logo and brand name */
        }

        .navigation {
            height: var(--nav-height);
            background: var(--nav-background);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 20px 40px;
            padding-bottom:20px;
        }

        .nav-container {
            max-width: var(--content-width);
            margin: 0 auto;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        nav ul {
            list-style: none;
            margin: 0;
            padding: 0;
            display: flex;
            align-items: center;
        }

        nav ul li {
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

        .nav-dropdown {
            display: none;
            position: absolute;
            top: 100%;
            left: 0;
            background-color: var(--nav-background);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.3);
            z-index: 1;
            flex-direction: column;
        }

        .nav-dropdown ul {
            flex-direction: column;
        }

        .nav-dropdown ul li a {
            padding: 10px 20px;
            width: 100%;
        }

        nav ul li:hover .nav-dropdown {
            display: flex;
        }

        .nav-mobile {
            display: none;
        }

        .footer {
            background-color: black;
            color: antiquewhite;
            padding: 5px;
            text-align: center;
            position: fixed;
            bottom: 0px;
            width: 100%;
            padding-bottom:10px;
            z-index: 1000; /* כדי לוודא שה-footer נמצא מעל התוכן */

        }

        article {
            max-width: var(--content-width);
            margin: 0 auto;
            padding: 10px;
        }
    </style>
</head>
<body>
<div class="navigation">
    <div class="nav-container">
        <div class="logo-container">
        <a href="http://localhost/project/homepage.php"><img src="logo.png.webp" alt="Logo" class="logo"></a>
        </div>
        <nav>
            <div class="nav-mobile"><a id="nav-toggle" href="#!"><span></span></a></div>
            <ul class="nav-list">
                <li><a href="http://localhost/project/homepage.php">home</a></li>
                <li><a href="http://localhost/project/contact.php">Contact</a></li>
                <li><a href="http://localhost/project/static.php">static</a></li>

              
                <?php 
                if (isset($_SESSION['username'])) {
                    echo '<li><a href="#!">My</a>
                            <div class="nav-dropdown">
                                <ul>
                                    <li><a href="http://localhost/project/products.php">Organizer</a></li>
                                    <li><a href="http://localhost/project/profile.php">Profile</a></li>
                                    <li><a href="http://localhost/project/taskworkser.php">Task</a></li>
                                    <li><a href="http://localhost/project/myshift.php">Shifts</a></li>
                                    <li><a href="http://localhost/project/view_swap_requests.php">swap</a></li>
                                    
                                    
                                </ul>
                            </div>
                          </li>';

                    $query = "SELECT is_admin FROM user_table WHERE username = ?";
                    $stmt = $con->prepare($query);
                    $stmt->bind_param("s", $_SESSION['username']);
                    $stmt->execute();
                    $result = $stmt->get_result();

                    if ($result && $row = $result->fetch_assoc()) {
                        if ($row['is_admin']) {
                            echo '<li><a href="#!">Admin</a>
                                    <div class="nav-dropdown">
                                        <ul>
                                            <li><a href="http://localhost/project/team_orginaizer.php">Team Organizer</a></li>
                                            <li><a href="http://localhost/project/add_removeUser.php">Add/Remove Users</a></li>
                                            <li><a href="http://localhost/project/tasks.php">Add Tasks</a></li>
                                        </ul>
                                    </div>
                                  </li>';
                        }

                    }
                    echo '<li><a href="http://localhost/project/logout.php">Log-out</a></li>';
                    $stmt->close();
                } else {
                    echo '<li><a href="http://localhost/project/login.php">Log-in</a></li>';
                }
                CloseCon($con);
                ?>
            </ul>
        </nav>
        
    </div>
    
</div>



<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>


</body>
<footer class="footer">
    <p>© 2024 TEAM TIME ORGANIZER</p>
</footer>

</html>