<?php
/* The below code is responsible for connecting to the database. */
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
#$something = "../squadrons/" . $_SESSION['something'] . "/config_m.php";
#require $something;
require "mysql_config.php";   // `mysql_config.php` includes the username and password detials for databse. It is excluded from GitHub


$conn = new mysqli($mysql_host2, $mysql_user2, $mysql_password2, $mysql_database2) or die("Database Connection Failed : " . mysql_error());

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
?>
