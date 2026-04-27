<?php
$pripojenie = mysqli_connect("localhost", "root", "root", "gametracker");
if (!$pripojenie) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
