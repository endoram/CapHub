<?php
//mysql configuration variables
$mysql_host = "";
$mysql_host2 = "";

$mysql_database = "";
$mysql_database2 = "";

$mysql_user = "";
$mysql_user2 = "";

$mysql_password = "";
$mysql_password2 = "";

$conn = new mysqli($mysql_host, $mysql_user, $mysql_password, $mysql_database) or die("Database Connection Failed : " . mysql_error());

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

?>
