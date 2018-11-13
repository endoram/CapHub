<?php
/*
Auther Spencer McConnell
*/

//Set up mysql database connections
define("mysql_host", "localhost");
define("mysql_database", "BoiseCAP073");
define("mysql_user", "root");
define("mysql_password", "nacer#1");

define("CAN_REGISTER", "any");
define("DEFAULT_ROLE", "member");

define("SECURE", "FALSE");

$mysqli = new mysqli(mysql_host, mysql_user, mysql_password, mysql_database);
if ($mysqli->connect_error){
	die("Connection failed: " . $mysqli->connect_error);

}

?>
