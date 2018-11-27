<?php
  require "control_access.php";

  if(isset($_GET['logout'])) {
    header("Location: ../index.php");
  }

  echo '<meta name="viewport" content="width=device-width, initial-scale=1.0">';
  echo '<img src="../images/banner.png">';
?>


<style>
table {
  text-align: center;
  border: 1px solid black;
}

th, td {
  border: 1px solid black;
  padding: 5px;
  text-align: left;
}
</style>

<html>
  <body>
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
  </body>
</html>
