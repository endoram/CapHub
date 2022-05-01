<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
#$something = "../squadrons/" . $_SESSION['something'] . "/config_m.php";
#require $something;
require "mysql_config.php";


$conn = new mysqli($mysql_host2, $mysql_user2, $mysql_password2, $mysql_database2) or die("Database Connection Failed : " . mysql_error());

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
?>
