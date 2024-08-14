<?php
include 'nav-fot.php';
include 'db_connection.php';
$con = OpenCon(); // פתח חיבור לבסיס הנתונים

// בדיקה שיש משתמש מחובר
if (isset($_SESSION['username'])) {
    $username = $_SESSION['username']; // שימוש בשם המשתמש מהסשן

    // קבלת השכר והיעד הנוכחי של המשתמש
    $query = "SELECT salary, gool FROM user_table WHERE username = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $salary = $row['salary'];
    $current_gool = isset($row['gool']) ? intval($row['gool']) : 160 * $salary;

    // בדיקת יעד נבחר ועדכון בדאטאבייס
    if (isset($_POST['target_earnings'])) {
        $target_earnings = intval($_POST['target_earnings']);
        $update_query = "UPDATE user_table SET gool = ? WHERE username = ?";
        $update_stmt = $con->prepare($update_query);
        $update_stmt->bind_param("is", $target_earnings, $username);
        $update_stmt->execute();
        $current_gool = $target_earnings;
    }

    // תחילת חודש וסוף החודש
    $start_date = date("Y-m-01");
    $end_date = date("Y-m-t"); // היום האחרון בחודש
    $current_date = date("Y-m-d"); // התאריך הנוכחי

    // יצירת מערך של כל התאריכים מהיום הראשון של החודש ועד היום האחרון
    $period = new DatePeriod(
        new DateTime($start_date),
        new DateInterval('P1D'),
        new DateTime($end_date . ' +1 day')
    );

    // חישוב השעות שהמשתמש עבד וההכנסות המצטברות
    $query = "SELECT ActivityDate, SUM(8 * u.salary) AS daily_earnings
              FROM usershifts s
              JOIN user_table u ON s.Username COLLATE utf8mb4_general_ci = u.username COLLATE utf8mb4_general_ci
              WHERE s.Username = ? AND s.ActivityDate BETWEEN ? AND ?
              GROUP BY ActivityDate";
    $stmt = $con->prepare($query);
    $stmt->bind_param("sss", $username, $start_date, $current_date);
    $stmt->execute();
    $result = $stmt->get_result();

    // מערכים לאחסון נתונים
    $dates = [];
    $daily_earnings_map = [];
    $cumulative_earnings = [];
    $total = 0;

    // שמירת התוצאות במערך
    while ($row = $result->fetch_assoc()) {
        $daily_earnings_map[$row['ActivityDate']] = $row['daily_earnings'];
    }

    // מילוי מערך התאריכים והכנסות מצטברות
    foreach ($period as $date) {
        $formatted_date = $date->format("Y-m-d");
        $dates[] = $formatted_date;
        if ($formatted_date <= $current_date) {
            $daily_earnings = $daily_earnings_map[$formatted_date] ?? 0;
            $total += $daily_earnings;
            $cumulative_earnings[] = $total;
        } else {
            $cumulative_earnings[] = null; // שאר התאריכים אחרי היום הנוכחי מקבלים null
        }
    }

    // חישוב המקסימום לגרף
    $max_earnings = 224 * $salary;

    // חישוב כמה חסר להגיע ליעד
    $missing_amount = $current_gool - $total;
    $remaining_shifts = ceil($missing_amount / ($salary * 8)); // חישוב מספר המשמרות הנותרות

} else {
    echo "אין משתמש מחובר.";
    exit();
}

CloseCon($con); // סגור את החיבור לבסיס הנתונים
?>

<!DOCTYPE html>
<html lang="he">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>גרף הכנסות מצטברות</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-annotation@1.0.2/dist/chartjs-plugin-annotation.min.js"></script>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background: #2c2c2c;
            color: #ffffff;
        }

        .header-container {
            background-color: #1e1e1e;
            padding: 20px;
            text-align: center;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.5);
            margin-bottom: 20px;
        }

        .header-container h2 {
            margin-bottom: 10px;
            color: #ffffff;
            font-size: 36px;
            font-weight: bold;
            border-bottom: 2px solid #2581DC;
            display: inline-block;
            padding-bottom: 10px;
        }

        .header-container .info {
            font-size: 1.2em;
            margin-bottom: 20px;
        }

        .header-container .info span {
            font-weight: bold;
            color: #4caf50;
        }

        .header-container form {
            display: inline-block;
            margin-top: 10px;
        }

        .header-container select {
            padding: 10px;
            font-size: 1.1em;
            border: 2px solid #2581DC;
            background-color: #333;
            color: #ffffff;
            border-radius: 5px;
            cursor: pointer;
            outline: none;
        }

        .header-container button {
            padding: 10px 20px;
            font-size: 1.1em;
            background-color: #2581DC;
            color: #ffffff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-left: 10px;
            outline: none;
        }

        .header-container button:hover {
            background-color: #1a6ab7;
        }
    </style>
</head>
<body>

    <div class="header-container">
        <h2> Welcome <?php echo htmlspecialchars($username); ?></h2>
        <div class="info">
            <p>Your current income: <span><?php echo number_format($total); ?> ₪</span></p>
            <p>You are short of your destination: <span><?php echo number_format($missing_amount); ?> ₪</span></p>
            <p>Number of shifts required: <span><?php echo $remaining_shifts; ?></span></p>
        </div>

        <!-- טופס לבחירת יעד -->
        <form method="POST" id="targetForm">
            <label for="target_earnings">Select a destination:</label>
            <select name="target_earnings" id="target_earnings" onchange="document.getElementById('targetForm').submit();">
                <?php
                for ($hours = 160; $hours <= 224; $hours += 8) {
                    $earnings = $hours * $salary;
                    echo "<option value=\"$earnings\"" . ($earnings == $current_gool ? " selected" : "") . ">$earnings ₪</option>";
                }
                ?>
            </select>
        </form>
    </div>

    <canvas id="earningsChart" width="400" height="200"></canvas>

    <script>
        // קבלת הנתונים מהשרת
        const labels = <?php echo json_encode($dates); ?>;
        const data = <?php echo json_encode($cumulative_earnings); ?>;
        const maxEarnings = <?php echo json_encode($max_earnings); ?>;
        const targetEarnings = <?php echo json_encode($current_gool); ?>;

        // יצירת הגרף באמצעות Chart.js
        const ctx = document.getElementById('earningsChart').getContext('2d');
        const earningsChart = new Chart(ctx, {
            type: 'line',
           
            
            data: {
                labels: labels,
                datasets: [{
                    label: 'Cumulative Earnings (₪)',
                    data: data,
                    borderColor: 'blue',
                    backgroundColor: 'rgba(0, 0, 255, 0.1)',
                    pointBackgroundColor: 'blue',
                    pointBorderColor: 'blue',
                    fill: true,
                    tension: 0.1
                }]
            },
            options: {
                scales: {
                    x: {
                        title: {
                            display: true,
                            text: 'Date'
                        }
                    },
                    y: {
                        title: {
                            display: true,
                            text: 'Cumulative Earnings (₪)'
                        },
                        suggestedMax: maxEarnings
                    }
                },
                plugins: {
                    annotation: {
                        annotations: {
                            line1: {
                                type: 'line',
                                yMin: targetEarnings,
                                yMax: targetEarnings,
                                borderColor: 'green',
                                borderWidth: 2,
                                label: {
                                    content: 'Goal',
                                    enabled: true,
                                    position: 'start',
                                    backgroundColor: 'rgba(0, 255, 0, 0.25)',
                                    color: '#ffffff',
                                    padding: {
                                        top: 6,
                                        left: 6,
                                        right: 6,
                                        bottom: 6
                                    }
                                }
                            }
                        }
                    }
                }
            }
        });
    </script>

</body>
    </br>
    </br>  </br>      </br>
</html>
