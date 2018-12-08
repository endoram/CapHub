<?php
require "../includes/header.php";
session_start();
?>

<html>
  <head>
    <title>CapHub MainPage</title>
  </head>
  <body>
    <h4>Logged in as:</h4> <?php echo $_SESSION['name'];?>
    <br> <h4>Privlage Level at <h4> <?php echo $_SESSION['privlv'];?>
  </body>
</html>
