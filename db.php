<?php
$host = "localhost";
$user = "root";
$pass = "root";
$db   = "gametracker";

$pripojenie = mysqli_connect($host, $user, $pass, $db);
if (!$pripojenie) {
    die("Connection failed: " . mysqli_connect_error());
}

$conn = $pripojenie;
$pripojenie->set_charset("utf8mb4");
?>
