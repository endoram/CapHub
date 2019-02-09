<?php
require "../includes/header.php";
session_start();
?>

<html>
  <head>
    <title>CapHub MainPage</title>
  </head>
  <body>
    <div class="userid">
      <?php echo "Logged in as: " . $_SESSION['name'];?>
      <br>
      <?php echo "Privlage Level: " . $_SESSION['privlv'];?>
  </div>
  </body>
</html>
