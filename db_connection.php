<?php
if (!function_exists('OpenCon')) {
    function OpenCon() {
    $dbhost = "localhost";
    $dbuser = "root";
    $dbpass = "1234";
    $db = "team time orginazer project"; 
    $conn = new mysqli($dbhost, $dbuser, $dbpass, $db) or die("Connect failed: %s\n" . $conn->error);

    return $conn;   
}
}

if (!function_exists('CloseCon')) {
    function CloseCon($conn) {
}
}
?>
