<?php
  session_start();
  if(!isset($_SESSION['password'])){
    header("Location: ../index.php");
    exit();
  }

  if(isset($_GET['logout'])) {
    header("Location: ../index.php");
  }


  require "../includes/config_m.php";
  $query = "SELECT time_zone FROM squads WHERE FQSN='" . $_SESSION['FQSN'] . "'";

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
    <!-- Google Tag Manager -->
    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
    new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
    j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
    'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
    })(window,document,'script','dataLayer','GTM-5X833TX');</script>
    <!-- End Google Tag Manager -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <a href="../protected/main.php"><img src="../images/bannerThree.png" alt="CAPhub" title="CAPhub"></a>
    <div class="userid">
      <?php echo "Logged in as: " . $_SESSION['name'];?>
      <br>
      <?php echo $_SESSION['FQSN'] . ", PL: " . $_SESSION['privlv'];?>
    </div>
    <link rel="stylesheet" type="text/css" href="style.css">
  </head>
  <body>
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-5X833TX"
    height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->
    <div class="menubar">
      <ul>
        <li><a href="sqmembers.php">Squadron</a></li>
        <li><a href="meeting_nights.php">Meetings</a></li>
        <li><a href="comms.php">Comms</a></li>
    <li><a href="physical_testing.php">PT</a></li>
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
        <a href="physical_testing.php">PT</a>
  <!--      <a href="events.php">Events</a> !-->
        <?php if($_SESSION['privlv'] >= 2){ ?>
          <a href="admin_conf.php">Settings</a>
        <?php } ?>
        <a href="help.php">Help</a>
        <a href="?logout=1">Log out</a>
      </div>
    </div>
  </body>
</html>