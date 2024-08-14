<?php include 'nav-fot.php'; 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TEAM TIME ORGANIZER</title>
    <link rel="stylesheet" href="styles.css"> <!-- Ensure this points to your stylesheet -->
    <style>
/* Basic Reset */
body, html {
    margin: 0;
    padding: 0;
    width: 100%;
    height: 100%;
    overflow-x: hidden; /* Avoid horizontal scroll */
}

.background {
    background-image: url('homepage.png'); /* Confirm this is the correct image path */
    height: 100vh;
    background-size: 70%;
    background-position: center;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
}

.header h1 {
    color: white; /* Enhanced visibility against darker backgrounds */
    margin: 20px 0;
    text-align: center;
}

.box-container {
    display: flex;
    justify-content: center;
    flex-wrap: wrap;
}

.interactive-box {
    width: 300px; /* Adjusted for better visual balance */
    height: 120px; /* Adjusted height for aesthetic */
    background-color: rgba(0, 0, 0, 0.8); /* Darker background for better contrast */
    color: white;
    display: flex;
    align-items: center;
    justify-content: space-around; /* Space elements within the box */
    border-radius: 10px; /* Smoothed corners */
    margin: 10px;
    font-size: 20px; /* Larger font size for better readability */
    position: relative;
    box-shadow: 0 4px 8px rgba(0,0,0,0.5); /* Subtle shadow for 3D effect */
    transition: transform 0.3s ease;
}

.checkmark {
    color: limegreen; /* Checkmark color for visibility */
    font-size: 25px; /* Larger checkmark */
    margin-right: 10px; /* Proper spacing from text */
}

.interactive-box:hover {
    transform: scale(1.05); /* Subtle enlargement on hover */
    cursor: pointer;
}

.chat-button {
    display: inline-block;
    padding: 10px 20px;
    background-color: #4CAF50; /* Chat button color */
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-weight: bold; /* Bold font for emphasis */
    position: fixed;
    bottom: 20px;
    right: 20px;
    box-shadow: 0 4px 8px rgba(0,0,0,0.3); /* Consistency in design with a shadow */
    text-decoration: none; /* Remove underline from link */
    font-size: 16px; /* Adjust font size for readability */
    transition: background-color 0.3s ease;
}

.chat-button:hover {
    background-color: #45a049; /* Slightly darker green on hover */
}

</style>
</head>
<body>
    <div class="background">
        <div class="header">
            <h1>TEAM TIME ORGANIZER</h1>
        </div>
        <div class="box-container">
            <div class="interactive-box" id="analytics">
                <span class="checkmark">✔</span> אפשרויות מנהל מתקדמות
            </div>
            <div class="interactive-box" id="ai">
                <span class="checkmark">✔</span>הזנת משימות 
            </div>
            <div class="interactive-box" id="security">
                <span class="checkmark">✔</span>שיבוץ לוז אוטמטי לפי העדפות 
            </div>
        </div>
        <div class="box-container">
            <div class="interactive-box" id="cloud">
                <span class="checkmark">✔</span>צפייה בסטטסטיקה 
            </div>
            <!-- Add more boxes here with three per row -->
        </div>
        
    </div>
</body>
</html>
