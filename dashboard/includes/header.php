<?php
  session_start();
  require "control_access.php";

  if(isset($_GET['logout'])) {
    header("Location: ../index.php");
  }
?>

<html>
  <head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <a href="../protected/main.php"><img src="../images/banner.png"></a>
    <link rel="stylesheet" type="text/css" href="style.css">
  </head>
  <body>
    <div class="menubar">
      <ul>
        <?php if($_SESSION['privlv'] >= 2){ //Allows Seniors and Seniors that need cadet stuff ?>
          <li><a href="coms.php">Radios</a></li>
          <li><a href="events.php">Events</a></li>
        <?php } ?>
        <li><a href="sqmembers.php">Squadron</a></li>
        <li><a href="meeting_nights.php">Meetings</a></li>
        <?php if($_SESSION['privlv'] <= 2 || $_SESSION['privlv'] == 4){ //Only shows up for cadets and Senior working with cadets?>
          <li><a href="physical_testing.php">PT</a></li>
        <?php } ?>
        <?php if($_SESSION['privlv'] >= 2){ ?>
          <li><a href="vehicles.php">Vehicles</a></li>
        <?php } ?>
        <li><a href="help.php">Help</a></li>
        <li><a href="?logout=1">Log out</a></li>
      </ul>
    </div>
  </body>
</html>
