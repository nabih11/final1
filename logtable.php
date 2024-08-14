<?php
include 'nav-fot.php';
include 'db_connection.php';

$con = OpenCon();

// Fetch user login attempt data
$query = "SELECT username, secc, err, cnt, cdate, lastlog FROM user_table";
$result = mysqli_query($con, $query);

// Check if we have results
if (!$result) {
    die('Error fetching data: ' . mysqli_error($con));
}

$usersData = mysqli_fetch_all($result, MYSQLI_ASSOC);

CloseCon($con);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login Attempts Table</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #4CAF50;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <h2>User Login Attempts</h2>
    <table>
        <tr>
            <th>Username</th>
            <th>Successful Logins</th>
            <th>Unsuccessful Logins</th>
            <th>Total Attempts</th>
            <th>last login</th>
            <th>last try</th>
        </tr>
        <?php foreach ($usersData as $user): ?>
            <tr>
                <td><?= htmlspecialchars($user['username']); ?></td>
                <td><?= htmlspecialchars($user['secc']); ?></td>
                <td><?= htmlspecialchars($user['err']); ?></td>
                <td><?= htmlspecialchars($user['cnt']); ?></td>
                <td><?= htmlspecialchars($user['cdate']); ?></td>
                <td><?= htmlspecialchars($user['lastlog']); ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>
