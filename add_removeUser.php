<?php
include 'db_connection.php';
include 'nav-fot.php';
$con = OpenCon();

// Handle user removal, admin setting, or salary update request
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['remove_user'])) {
        $username_to_remove = mysqli_real_escape_string($con, $_POST['remove_user']);
        $sql = "DELETE FROM user_table WHERE username='$username_to_remove'";
        $sql1 = "DELETE FROM morning_weekly WHERE username='$username_to_remove'";
        if (mysqli_query($con, $sql)) {
            if (mysqli_query($con, $sql1)) {
                echo "<script>alert('User $username_to_remove has been successfully removed.');</script>";
                echo "<script>window.location = 'add_removeUser.php';</script>";
            }
        } else {
            echo "<p>Error removing user: " . mysqli_error($con) . "</p>";
        }
    }
    
    if (isset($_POST['make_admin'])) {
        $username_to_admin = mysqli_real_escape_string($con, $_POST['make_admin']);
        $sql = "UPDATE user_table SET is_admin='1' WHERE username='$username_to_admin'";

        if (mysqli_query($con, $sql)) {
            echo "<script>alert('User $username_to_admin has been set as an admin.');</script>";
            echo "<script>window.location = 'add_removeUser.php';</script>";
        } else {
            echo "<p>Error setting user as admin: " . mysqli_error($con) . "</p>";
        }
    }

    if (isset($_POST['update_salary'])) {
        $username_to_update = mysqli_real_escape_string($con, $_POST['username']);
        $new_salary = mysqli_real_escape_string($con, $_POST['new_salary']);
        $sql = "UPDATE user_table SET salary='$new_salary' WHERE username='$username_to_update'";

        if (mysqli_query($con, $sql)) {
            echo "<script>alert('Salary for $username_to_update has been updated to $new_salary.');</script>";
            echo "<script>window.location = 'add_removeUser.php';</script>";
        } else {
            echo "<p>Error updating salary: " . mysqli_error($con) . "</p>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management</title>
    <style>
        .user-table {
            width: 100%;
            border-collapse: collapse;
        }
        .user-table th, .user-table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }
        .user-table th {
            background-color: #f2f2f2;
        }
        .action-btn {
            background-color: #ff4747;
            color: white;
            border: none;
            border-radius: 5px;
            padding: 10px 20px;
            cursor: pointer;
            font-size: 14px;
            margin-left: 10px;
        }
        .action-btn.make-admin {
            background-color: #4CAF50;
        }
        .action-btn:hover {
            opacity: 0.9;
        }
        .salary-input {
            width: 100px;
            padding: 5px;
            font-size: 14px;
            margin-left: 10px;
        }
    </style>
</head>
<body>
    <table class="user-table">
        <thead>
            <tr>
                <th>Username</th>
                <th>Salary</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $sql = "SELECT username, is_admin, salary FROM user_table ORDER BY is_admin DESC";
            $result = mysqli_query($con, $sql);

            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    $username = htmlspecialchars($row['username']);
                    $salary = htmlspecialchars($row['salary']);
                    echo "<tr>";
                    echo "<td>$username</td>";
                    echo "<td>Salary: $salary Per/h </td>";
                    echo "<td>";

                    // Form for removing user
                    echo "<form action='' method='POST' style='display:inline;'>";
                    echo "<input type='hidden' name='remove_user' value='$username'>";
                    echo "<button type='submit' class='action-btn remove-btn'>Remove</button>";
                    echo "</form>";
                    
                    // Form for making user admin
                    if ($row['is_admin'] != '1') {
                        echo "<form action='' method='POST' style='display:inline;'>";
                        echo "<input type='hidden' name='make_admin' value='$username'>";
                        echo "<button type='submit' class='action-btn make-admin'>Make Admin</button>";
                        echo "</form>";
                    }

                    // Form for updating salary
                    echo "<form action='' method='POST' style='display:inline;'>";
                    echo "<input type='hidden' name='username' value='$username'>";
                    echo "<input type='number' name='new_salary' class='salary-input' placeholder='New Salary' required>";
                    echo "<button type='submit' name='update_salary' class='action-btn'>Update Salary</button>";
                    echo "</form>";

                    echo "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='3'>No users found.</td></tr>";
            }

            CloseCon($con);
            ?>
        </tbody>
    </table>
</body>
</html>
