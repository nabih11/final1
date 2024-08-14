<?php include 'nav-fot.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Schedule Submission</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background: #2c2c2c; /* רקע כהה */
            color: #ffffff;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        h1 {
            text-align: center;
            margin-top: 20px;
            color: #ffffff;
            font-size: 28px;
            border-bottom: 2px solid #2581DC;
            display: inline-block;
            padding-bottom: 10px;
        }

        table {
            width: 80%;
            margin: 40px auto;
            border-collapse: collapse;
            background-color: #3e3e3e; /* רקע כהה לטבלה */
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.5);
        }

        th, td {
            border: 1px solid #555;
            padding: 12px;
            text-align: center;
            color: #ffffff;
        }

        th {
            background-color: #2581DC; /* צבע רקע לכותרות */
            color: #ffffff;
            font-weight: bold;
            font-size: 18px;
        }

        td {
            font-size: 16px;
        }

        tr:hover {
            background-color: #4caf50; /* צבע רקע בהובר */
            color: #ffffff;
        }

        .submit-btn {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 20px;
            display: none; /* הכפתור מופיע רק כאשר כל התנאים מתקיימים */
            font-size: 18px;
        }

        .submit-btn:hover {
            background-color: #45a049;
        }

    </style>
</head>
<body>
<h1>You must select 5-7 shifts: 2 morning and 3 evening</h1>
<form id="shiftForm" method="post" action="cart.php">
    <table>
        <thead>
            <tr>
                <th>Day</th>
                <th>Morning Shift</th>
                <th>Evening Shift</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Sunday</td>
                <td><input type="radio" name="Sunday" value="morning"></td>
                <td><input type="radio" name="Sunday" value="evening"></td>
            </tr>
            <tr>
                <td>Monday</td>
                <td><input type="radio" name="Monday" value="morning"></td>
                <td><input type="radio" name="Monday" value="evening"></td>
            </tr>
            <tr>
                <td>Tuesday</td>
                <td><input type="radio" name="Tuesday" value="morning"></td>
                <td><input type="radio" name="Tuesday" value="evening"></td>
            </tr>
            <tr>
                <td>Wednesday</td>
                <td><input type="radio" name="Wednesday" value="morning"></td>
                <td><input type="radio" name="Wednesday" value="evening"></td>
            </tr>
            <tr>
                <td>Thursday</td>
                <td><input type="radio" name="Thursday" value="morning"></td>
                <td><input type="radio" name="Thursday" value="evening"></td>
            </tr>
            <tr>
                <td>Friday</td>
                <td><input type="radio" name="Friday" value="morning"></td>
                <td><input type="radio" name="Friday" value="evening"></td>
            </tr>
            <tr>
                <td>Saturday</td>
                <td><input type="radio" name="Saturday" value="morning"></td>
                <td><input type="radio" name="Saturday" value="evening"></td>
            </tr>
        </tbody>
    </table>
    <button type="submit" class="submit-btn">Submit</button>
</form>

<script>
    const form = document.getElementById('shiftForm');
    const submitBtn = document.querySelector('.submit-btn');
    const daysOfWeek = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];

    form.addEventListener('change', function() {
        let morningCount = 0;
        let eveningCount = 0;
        
        daysOfWeek.forEach(day => {
            const morningSelected = document.querySelector(`input[name="${day}"][value="morning"]:checked`);
            const eveningSelected = document.querySelector(`input[name="${day}"][value="evening"]:checked`);

            if (morningSelected) morningCount++;
            if (eveningSelected) eveningCount++;
        });

        const allDaysSelected = morningCount + eveningCount >= daysOfWeek.length-2;
        const validShiftCounts = morningCount >= 2 && eveningCount >= 3;

        if (allDaysSelected && validShiftCounts) {
            submitBtn.style.display = 'block';
        } else {
            submitBtn.style.display = 'none';
        }
    });
</script>
</body>
</br>
</br>
</br></br>
</html>
