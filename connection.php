<?php
    $dbservername = "localhost";
    $dbUsername = "root";
    $dbpassword = "";
    $dbname = "crawler";
    $conn = mysqli_connect($dbservername,$dbUsername,$dbpassword,$dbname) or die("Couldn't connect to server");
    global $conn;
?>