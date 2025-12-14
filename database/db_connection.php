<?php
$servername = "localhost";
$username = "jrandle4";
$password = "jrandle4";
$dbname = "jrandle4";


$conn = new mysqli($servername, $username, $password, $dbname);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>