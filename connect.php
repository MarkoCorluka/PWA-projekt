<?php
$servername = "localhost:3307";
$username = "root";
$password = "";
$basename = "PWA_projekt";

$dbc = mysqli_connect($servername, $username, $password, $basename)
    or die("Greška: " . mysqli_connect_error());

mysqli_set_charset($dbc, "utf8");
?>