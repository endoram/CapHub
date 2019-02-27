<?php

if(!isset($_SESSION['password'])){
  header("Location: ../index.php");
  exit();
}
?>
