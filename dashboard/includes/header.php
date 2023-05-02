<?php
  session_start();
  if(!isset($_SESSION['password'])){
    header("Location: ../index.php");
    exit();
  }

  if(isset($_GET['logout'])) {
    header("Location: ../index.php");
  }

  require "../includes/legal.php";


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
<html lang="en">
  <head>
    <!-- Google Tag Manager -->
    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
    new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
    j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
    'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
    })(window,document,'script','dataLayer','GTM-5X833TX');</script>
    <!-- End Google Tag Manager -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="refresh" content="900;url=../index.php" />

    <script src="../libs/bootstrap/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js" integrity="sha384-mQ93GR66B00ZXjt0YO5KlohRA5SY2XofN4zfuZxLkoj1gXtW8ANNCe9d5Y3eG5eD" crossorigin="anonymous"></script>
    <link href="../libs/bootstrap/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="../protected/style.css">
  </head>
  <body>
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-5X833TX"
    height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->
    <div>
      <div class="row no-gutters">
        <div class="col-sm">
          <a href="../protected/main.php"><img src="../images/bannerThree.png" alt="CAPhub" title="CAPhub"></a>
        </div>
        <div class="col-sm">
          <h8>For CAP Members, by CAP Members</h8>
        </div>
        <div class="col-sm">
          <div class="userid">
            <?php echo "Logged in as: " . $_SESSION['name'];?>
            <br>
            <?php echo $_SESSION['FQSN'] . ", PL: " . $_SESSION['privlv'];?>
          </div>
        </div>
      </div>
    <div class="container-fluid p-0">
      <div class="menubar">
        <ul>
          <li><a href="../protected/sqmembers.php">Squadron</a></li>
          <li><a href="../protected/meeting_nights.php">Meetings</a></li>
          <li><a href="../protected/comms.php">Comms</a></li>
          <li><a href="../protected/physical_testing.php">PT</a></li>
      <!--    <li><a href="../protected/flights.php">Flights</a></li>  !-->
    <!--  <li><a href="events.php">Events</a></li>      !-->
    <!--  <li><a href="vehicles.php">Vehicles</a></li>  !-->
          <?php if($_SESSION['privlv'] >= 2){ ?>
            <li><a href="../protected/admin_conf.php">Settings</a></li>
          <?php } ?>
          <li><a href="../protected/help.php">Help</a></li>
          <li><a href="?logout=1">Log out</a></li>
        </ul>
      </div>
      <div class="dropdownheader">
        <button class="dropbtn">Menu</button>
        <div class="dropdown-content">
          <a href="../protected/sqmembers.php">Squadron</a>
          <a href="../protected/meeting_nights.php">Meetings</a>
          <a href="../protected/comms.php">Comms</a>
          <a href="../protected/physical_testing.php">PT</a>
        <!--  <a href="../protected/flights.php">Flights</a> !-->
    <!--      <a href="events.php">Events</a> !-->
          <?php if($_SESSION['privlv'] >= 2){ ?>
            <a href="../protected/admin_conf.php">Settings</a>
          <?php } ?>
          <a href="../protected/help.php">Help</a>
          <a href="?logout=1">Log out</a>
        </div>
      </div>
    </div>
  </body>
</html>
<script type="text/javascript">
  var bootstrap_enabled = (typeof $().modal == 'function');
  console.log(bootstrap_enabled);
</script>