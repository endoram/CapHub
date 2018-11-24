<?php
session_start();

if(!isset($_SESSION['password'])){
  header("Location: index.php");
}
else {
  echo "WE LOGGED IN";
}
?>
