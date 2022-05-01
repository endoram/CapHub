<?php
  session_start();
  require "control_access.php";

  if(isset($_GET['logout'])) {
    header("Location: ../index.php");
  }



  require "../includes/mysql_config.php";
  $conn = new mysqli($mysql_host2, $mysql_user2, $mysql_password2, $mysql_database2) or die("Database Connection Failed : " . mysql_error());

  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }
  $query = "SELECT * FROM squads WHERE sq_name='" . $_SESSION['something'] . "'";

  $result = $conn->query($query);
  if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
      date_default_timezone_set($row['time_zone']);
    }
    $conn->close();
  }
  else {
    echo "<script>alert('No Results Found');</script>";
    $conn->close();
  }
?>


<html>
  <head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <a href="../protected/main.php"><img src="../images/banner.png"></a>
    <div class="userid">
      <?php echo "Logged in as: " . $_SESSION['name'];?>
      <br>
      <?php echo "Privlage Level: " . $_SESSION['privlv'];?>
    </div>
    <link rel="stylesheet" type="text/css" href="style.css">
  </head>
  <body>
    <div class="menubar">
      <ul>
        <li><a href="sqmembers.php">Squadron</a></li>
        <li><a href="meeting_nights.php">Meetings</a></li>
        <li><a href="comms.php">Comms</a></li>
  <!--  <li><a href="physical_testing.php">PT</a></li>!-->
  <!--  <li><a href="events.php">Events</a></li>      !-->
  <!--  <li><a href="vehicles.php">Vehicles</a></li>  !-->
        <?php if($_SESSION['privlv'] >= 2){ ?>
          <li><a href="admin_conf.php">Settings</a></li>
        <?php } ?>
        <li><a href="help.php">Help</a></li>
        <li><a href="?logout=1">Log out</a></li>
      </ul>
    </div>
    <div class="dropdownheader">
      <button class="dropbtn">Menu</button>
      <div class="dropdown-content">
        <a href="sqmembers.php">Squadron</a>
        <a href="meeting_nights.php">Meetings</a>
        <a href="comms.php">Comms</a>
  <!--      <a href="physical_testing.php">PT</a> !-->
  <!--      <a href="events.php">Events</a> !-->
        <?php if($_SESSION['privlv'] >= 2){ ?>
          <li><a href="admin_conf.php">Settings</a></li>
        <?php } ?>
        <a href="help.php">Help</a>
        <a href="?logout=1">Log out</a>
      </div>
    </div>
  </body>
</html>
