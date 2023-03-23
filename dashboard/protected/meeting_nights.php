<?php
if(isset($_POST['export'])){
  $query = $_POST['exportData'];
  $rowHeaders = array("DATE", "CAPID", "Name", "Time In", "Time Out", "membertype", "Squadron", "Visited", "Phone Number", "Email");
  include "../includes/helpers.php";
  exportMe($query, $rowHeaders);
}

if (isset($_GET['kiosk'])) {
  session_start();
  unset($_SESSION["capid"]);
  unset($_SESSION["password"]);
  unset($_SESSION["privlv"]);
  unset($_SESSION["name"]);
  echo '
    <script src="../libs/bootstrap/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js" integrity="sha384-mQ93GR66B00ZXjt0YO5KlohRA5SY2XofN4zfuZxLkoj1gXtW8ANNCe9d5Y3eG5eD" crossorigin="anonymous"></script>
    <link href="../libs/bootstrap/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="style.css">';
} else {require "../includes/header.php";}
if (isset($_GET['logout'])) {header("Location: ../index.php");}

if(isset($_POST['guestlogin'])) {
  header("Location: /includes/guestsign.php");
}

if(isset($_GET['rmuser0'])) {     //If user has intered an input
  if (strlen($_SESSION['FQSN']) <= 1) {
    header("Location: ../index.php");
  }
  require "../includes/helpers.php";
  timeZone();
  $time = date("H:i:s");
  $capid = $_GET['capidrm'];

  if ($capid == "" or !is_numeric($capid)) {$errorMsg = "Invalid Cap ID";}    //Validate all numbers
  else {
    require "../includes/config_m.php";
    unset($_SESSION["name"]);

    $query = "SELECT * FROM sq_members WHERE cap_id=" . $capid;   //Validate number is a CAPID in the database
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
      while($row = $result->fetch_assoc()) {  //Get first and last name from database
        $db_capid = $row['cap_id'];
        $name = $row['first_name'];
        $name0 = $row['last_name'];
        $name = $name0 . ' ' . $name;
        $membertype = $row['member_type'];
        $FQSN = $row['FQSN'];
      }
      if($db_capid == $capid) {
        $date = date("Y/m/d");
        require "../includes/config_m.php";
        $query = "SELECT * FROM meeting_nights WHERE cap_id=" . $db_capid . " AND date='" . $date . "'";
        $result = $conn->query($query);

        if ($result->num_rows > 0) {    //If already signed in for that day sign out
          $message = $name . " signed out";
          $date = date("Y/m/d");
          $time = date("H:i:s");
          $query = "UPDATE meeting_nights SET time_out='" . $time . "' WHERE cap_id=" . $capid . " AND date='" . $date . "'";
          $conn->query($query);
        }
        else {      //If not sign you in
          $message = "Thank you " . $name . " for signing into " . $_SESSION['FQSN'] . "'s meeting!";
          $date = date("Y/m/d");
          $time = date("H:i:s");
          $capid = $_GET['capidrm'];

        //  if($FQSN == $_SESSION['FQSN']) {
        //    echo'<script>alert("Hey $name are you visisting $_SESSION["FQSN"]?");</script>';
        //  }
          $query = "INSERT INTO meeting_nights (date, cap_id, name, time_in, member_type, FQSN, visited) VALUES ('" . $date . "', " .  $capid . ", '" . $name . "', '" . $time . "','" . $membertype . "','" . $FQSN . "','" . $_SESSION['FQSN'] . "')";
           $conn->query($query);
          }
        }
      }
    else {$errorMsg = "CAP ID not in database Please talk to the cadet admin NCO";}
    $conn->close();
  }
}

if(isset($_POST['sent'])) {
  $queryHeaders = array("date", "cap_id", "name", "time_in", "time_out", "FQSN", "visited", "phone_number", "email");
  $displayHeaders = array("ID", "Date","CAPID", "Name (Last, First)", "Time In", "Time Out", "Squadron", "Visited", "Phone Number", "Email");
  require "../includes/helpers.php";
  queryCreate($_POST['sent'], $_POST['query'], $_POST['page'], $displayHeaders, $queryHeaders);
}

$page = "../protected/meeting_nights.php";
$queryFirst = "SELECT date, cap_id, name, time_in, time_out, member_type, FQSN, visited, phone_number, email FROM meeting_nights WHERE visited='".$_SESSION['FQSN']."' && ";
//Detects whitch one to display when searching
if(isset($_GET['name'])) {
  $data = "Name:";
  require "../includes/helpers.php";
  searchMe($data, $queryFirst, $page);
}
if(isset($_GET['capid'])) {
  $data = "CAP ID:";
  require "../includes/helpers.php";
  searchMe($data, $queryFirst, $page);
}

if(isset($_GET['date'])) {
  $data = "date";
  require "../includes/helpers.php";
  dateSearch($data, $queryFirst, $page);
}

if(isset($_GET['date_range'])) {
  $data = "date_range";
  require "../includes/helpers.php";
  dateRange($data, $queryFirst, $page);
}
?>

<!--Script to handle opeing and closing of search box-->
<script>
function openForm() {
    document.getElementById("myForm").style.display = "block";
}

function closeForm() {
    document.getElementById("myForm").style.display = "none";
}
</script>


<!--Start of main html code -->
<html>
  <head>
    <title>CapHub MeetingNights</title>
    <link rel="stylesheet" type="text/css", href="style.css">
    <link rel="stylesheet" href="../libs/calendar/datepicker.min.css">
  </head>
  <body>
    <?php
      if (isset($_GET['kiosk'])) {
        echo '<a href="../protected/main.php"><img src="../images/bannerThree.png"></a><br>';

      }
    ?>
    <?php echo "Today is " . date("Y/m/d") . "<br>";?>
    <div class="row">
      <div class="leftside">
        <div class="sqmenubar">
          <ul>
            <?php 
            if (isset($_GET['kiosk'])) {
              echo '<li><a href="?kiosk">Kiosk Mode enabled</a></li>';
              echo '<li><a href="?logout">Logout</a></li>';
            } else { echo '
            <li><a href="?kiosk">Kiosk Mode</a></li>
          </ul>
          <div class="dropdown">
            <ul><li><button class="sqmenubutton">Search</button></li></ul>
            <div class="dropdown-content">
              <p>Search by:</p>
              <a href="?name=1">Name</a>
              <a href="?capid=1">CAP ID</a>
              <a href="?date=1">Date</a>
              <a href="?date_range=1">Date Range</a>
            </div>
          </div>
          ';} ?>
        </div>
      </div>
    <br>
    <div class="meetingform">
      <?php       //Handles promting any error messages
      if(isset($errorMsg) && $errorMsg) {
        echo "<p style=\"color: red;\">*",htmlspecialchars($errorMsg),"</p>\n\n";
      }
      if(isset($message) && $message) {
        echo "<p style=\"color: green;\">*",htmlspecialchars($message),"</p>\n\n";
      }
      if(isset($_SESSION['message']) && $_SESSION['message']) {
        echo "<p style=\"color: green;\">*",htmlspecialchars($_SESSION['message']),"</p>\n\n";
        unset($_SESSION['message']);
      }
      ?>
      <label>CAP ID:</label>
      <form action="meeting_nights.php">
        <input type="text" name="capidrm" autofocus> <!--Getting user's CAPID-->
        <?php if (isset($_GET['kiosk'])) {
          echo '<input type="hidden" name="kiosk">';
        }?>
        <br>
        <input type="submit" value="Submit" name="rmuser0">
      </form>
      <form action="../includes/guestsign.php">
        <br>
        <input type="submit" value="GuestSignin" name="guestlogin">
        <?php if (isset($_GET['kiosk'])) {
          echo '<input type="hidden" name="kiosk" value=1>';
        }?>
      </form>
    </div>
  </div>
  </body>

  <div class="navbar fixed-bottom">
    <div class="container-fluid p-0">
    <?php
      require "../includes/footer.php";
    ?>
    </div>
  </div>
</html>
