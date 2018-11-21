<?php
require "../config_m.php";

$conn = new mysqli($mysql_host, $mysql_user, $mysql_password, $mysql_database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

#$sql = "INSERT INTO users ('user_id', 'first_name', 'last_name') VALUES (" . $_POST["cap_id"] . ", " . $_POST["first_name"] . "', '" . $_POST["last_name"] . "')";

$CAPID = $_POST["cap_id"];
$NAME = $_POST["first_name"];
$LNAME = $_POST["last_name"];

$sql = "INSERT INTO `users`(`user_id`, `first_name`, `last_name`) VALUES ($CAPID, '$NAME', '$LNAME')";

#echo $sql;

$conn->query($sql);
$conn->close();
header('Location:../db_test.php');
?>
