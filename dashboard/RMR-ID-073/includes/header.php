<?php
  require "control_access.php";

  if(isset($_GET['logout'])) {
    header("Location: ../index.php");
  }
?>

<html>
  <head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <img src="../images/banner.png">
    <link rel="stylesheet" type="text/css" href="style.css">
  </head>
  <body>
    <div class="menubar">
      <ul>
        <li><a href="coms.php">Radios</a></li>
        <li><a href="events.php">Events</a></li>
        <li><a href="sqmembers.php">Squadron</a></li>
        <li><a href="help.php">Help</a></li>
        <li><a href="?logout=1">Log out</a></li>
      </ul>
    </div>
    <div class="foot">
      <center>
        <p>Developed by Spencer McConnell</p>
      </center>
    </div>
  </body>
</html>
