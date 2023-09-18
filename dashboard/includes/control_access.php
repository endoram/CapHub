<?php
// This page is included to verify a user is logged in when the header isn't included
if(!isset($_SESSION['password'])){
  header("Location: ../index.php");
  exit();
}
?>
