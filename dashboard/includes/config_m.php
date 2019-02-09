<?php
session_start();

$something = "../" . $_SESSION['something'];
require $something;

$conn = new mysqli($mysql_host, $mysql_user, $mysql_password, $mysql_database) or die("Database Connection Failed : " . mysql_error());

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
?>
