<?php
include 'nav-fot.php';
include 'db_connection.php';

// בדוק אם המשתמש מחובר, אם לא - הפנה לדף ההתחברות
if (!isset($_SESSION["username"])) {
    header("Location: login.php");
    exit;
}

$username = $_SESSION["username"];

// פונקציה לקבלת תאריך יום ראשון של השבוע הנוכחי
function getStartOfWeek($date) {
    $timestamp = strtotime($date);
    $startOfWeek = strtotime('last Sunday', $timestamp);
    if (date('l', $timestamp) === 'Sunday') {
        $startOfWeek = $timestamp;
    }
    return date('Y-m-d', $startOfWeek);
}

// פונקציה להוספת מספר ימים לתאריך
function addDays($date, $days) {
    return date('Y-m-d', strtotime("+$days days", strtotime($date)));
}

// בדוק אם יש נתוני לו"ז לעדכן
if (isset($_SESSION['scheduleData']) && $_SERVER["REQUEST_METHOD"] == "POST") {
    $con = OpenCon();
    if ($con->connect_error) {
        die("Connection failed: " . $con->connect_error);
    }

    // התחל טרנזקציה
    $con->begin_transaction();
    try {
        $daysOfWeek = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
        $startOfWeek = getStartOfWeek(date('Y-m-d'));

        foreach ($daysOfWeek as $index => $day) {
            $dayColumn = strtolower($day);
            $value = 0; // ברירת מחדל - אם היום לא נבחר, ערך יהיה 0
            if (isset($_SESSION['scheduleData'][$day])) {
                $shiftType = $_SESSION['scheduleData'][$day];
                $value = ($shiftType === 'morning') ? 1 : 2;
            }

            // עדכון טבלת morning_weekly
            $stmt = $con->prepare("UPDATE morning_weekly SET `$dayColumn` = ? WHERE username = ?");
            if (!$stmt) {
                throw new Exception("Prepare failed: " . $con->error);
            }

            $stmt->bind_param('is', $value, $username);
            if (!$stmt->execute()) {
                throw new Exception("Execute failed: " . $stmt->error);
            }
            $stmt->close();

            // הוספת המשמרות לטבלת usershifts
            if ($value > 0) {
                $activityDate = addDays($startOfWeek, $index);

                // בדיקת הכפלה
                $checkQuery = "SELECT * FROM usershifts WHERE username = ? AND ActivityDate = ?";
                $checkStmt = $con->prepare($checkQuery);
                $checkStmt->bind_param('ss', $username, $activityDate);
                $checkStmt->execute();
                $result = $checkStmt->get_result();

                if ($result->num_rows == 0) {
                    $stmt = $con->prepare("INSERT INTO usershifts (username, ActivityDate, shiftType) VALUES (?, ?, ?)");
                    if (!$stmt) {
                        throw new Exception("Prepare failed: " . $con->error);
                    }

                    $stmt->bind_param('ssi', $username, $activityDate, $value);
                    if (!$stmt->execute()) {
                        throw new Exception("Execute failed: " . $stmt->error);
                    }
                    $stmt->close();
                }

                $checkStmt->close();
            }
        }

        // אם הכל תקין, בצע commit לטרנזקציה
        $con->commit();
        echo "Shifts updated successfully for $username.";

        // אחרי העדכון, נקה את הנתונים מהסשן
        unset($_SESSION['scheduleData']);
    } catch (Exception $e) {
        // אם יש שגיאה, בצע rollback לטרנזקציה
        $con->rollback();
        echo "An error occurred: " . $e->getMessage();
    }
    // סגור את החיבור לדאטאבייס
    CloseCon($con);

    //  header("Location: homepage.php");
    //  exit;
} else {
    // אם אין נתוני לו"ז לעדכן, הפנה לטופס הזנת הלו"ז
    // header("Location: homepage.php");
    // exit;
}
?>
