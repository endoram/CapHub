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
      <table style="width:50%">
          <tr>
            <td><a href="coms.php">Comunications</a></td>
            <td><a href="events.php">Events</a></td>
            <td><a href="qmembers.php">Squadron Members</a></td>
            <td><a href="help.php">Help</a></td>
            <td><a href="meeting_nights.php">Meeting Nights</a></td>
            <td><a href="?logout=1">Log out</a></td>
          </tr>
      </table>
    </div>
    <div class="foot">
      <center>
        <p>Developed by Spencer McConnell</p>
      </center>
    </div>
  </body>
</html>
