<?php
require "../includes/header.php";

session_start();

if(isset($_POST['sent'])) {submit();}

if(isset($_GET['rmuser0'])) {
  date_default_timezone_set("America/Denver");
  $time = date("h:i:s");
  $capid = $_GET['capidrm'];

  if ($capid == "" or !is_numeric($capid)) {$errorMsg = "Invalid Cap ID";}
  else {
    require "../includes/config_m.php";
    unset($_SESSION["name"]);

    $query = "SELECT * FROM sq_members WHERE cap_id=" . $capid;
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
      while($row = $result->fetch_assoc()) {
        $db_capid = $row['cap_id'];
        $name = $row['first_name'];
        $name0 = $row['last_name'];
        $name = $name . ' ' . $name0;
      }
      if($db_capid == $capid) {
        $date = date("Y/m/d");
        require "../includes/config_m.php";
        $query = "SELECT * FROM meeting_nights WHERE cap_id=" . $db_capid . " AND date='" . $date . "'";
        $result = $conn->query($query);

        if ($result->num_rows > 0) {
          $message = $name . " signed out";
          $date = date("Y/m/d");
          $time = date("h:i:s");
          $query = "UPDATE meeting_nights SET time_out='" . $time . "' WHERE cap_id=" . $capid . " AND date='" . $date . "'";
          $conn->query($query);
        }
        else {
          $message = $name . " signed in";
          $date = date("Y/m/d");
          $time = date("h:i:s");
          $capid = $_GET['capidrm'];

          $query = "INSERT INTO meeting_nights (date, cap_id, name, time_in) VALUES ('" . $date . "', " .  $capid . ", '" . $name . "', '" . $time . "')";
          $conn->query($query);
        }
      }
    }
    else {$errorMsg = "CAP ID not in database Please talk to the cadet admin NCO";}
    $conn->close();
  }
}

if(isset($_GET['name'])) {
  $data = "Name:";  handleit($data);
}
if(isset($_GET['capid'])) {
  $data = "CAP ID:"; handleit($data);
}
if(isset($_GET['date'])) {
  $data = "Date(Y/M/D):"; handleit($data);
}

function handleit($data) {
  unset($_GET['firstname, lastname, capid']);

  echo '<div class="form-popup" id="myForm">';
  echo '<form method="post" action="meeting_nights.php" class="form-container">';

  echo '<label for="input"><b>' . $data . '</b></label>';
  echo '<input type="text" name="input" required>';

  echo '<button type="submit" value="' . $data . '" name="sent" class="btn">Submit</button>';
  echo '<button type="button" class="btn cancel" onclick="closeForm()">Close</button>';
  echo '</form>';
  echo '</div>';
}

function submit() {
  if($_POST['sent'] == "Name:") {
    $firstname = $_POST['input'];
    if (preg_match('/[^A-Z a-z]/', $firstname)) {
      echo "<p style='color: red'>Names don't have numbers in them - try again<p>";
    }
    else {
      $data = "name LIKE '" . $_POST['input'] . "%'";
      queryit($data);
    }
  }

  if($_POST['sent'] == "CAP ID:") {
    $capid = $_POST['input'];
    if(!is_numeric($capid)) {
      echo "<p style='color: red'>Invalid Cap ID<p>";
    }
    else {
      $data = "cap_id LIKE '" . $_POST['input'] . "%'";
      queryit($data);
    }
  }

  if($_POST['sent'] == "Date(Y/M/D):") {
    $priv = $_POST['input'];
    if(preg_match('/[^0-9]/', $firstname)) {
      echo "<p style='color: red'>Invalid Datel<p>";
    }
    else {
      $data = "date='" . $_POST['input'] . "'";
      queryit($data);
    }
  }
}

function queryit($data) {
  require "../includes/config_m.php";
  $query = "SELECT * FROM meeting_nights WHERE " . $data;
  $result = $conn->query($query);

  echo '<div class="sqsearch">
    <br>
    <table>
      <colgroup>
        <col span="5" style="background-color:lightgrey">
      </colgroup>
      <tr>
        <th>Date</th>
        <th>CAPID</th>
        <th>Name</th>
        <th>Time In</th>
        <th>Time Out</th>
      </tr>';

  if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        echo "<tr>
        <td>" . $row["date"] . "</td>
        <td>" . $row["cap_id"] . "</td>
        <td>" . $row["name"] . "</td>
        <td>" . $row["time_in"] . "</td>
        <td>" . $row["time_out"] . "</td>
        </tr>";
        $rm_capid = $row["cap_id"];
    }
  }
  else {
    echo "<h4 style='color: darkyellow'>No Reults found</h4>";
    $conn->close();
  }
  $conn->close();
  echo "</table></div></div>";
}
?>

<script>
function openForm() {
    document.getElementById("myForm").style.display = "block";
}

function closeForm() {
    document.getElementById("myForm").style.display = "none";
}
</script>



<html>
  <head>
    <title>CapHub MeetingNights</title>
    <link rel="stylesheet" type="text/css", href="style.css">
  </head>
  <body>
    <?php echo "Today is " . date("Y/m/d") . "<br>";?>
    <div class="dropdown">
      <button class="dropbtn">Search for</button>
      <div class="dropdown-content">
        <p>Search For:</p>
        <a href="?name=1">Name</a>
        <a href="?capid=1">CAP ID</a>
        <a href="?date=1">Date</a>
      </div>
    </div>
    <br>
    <div class="meetingform">
      <?php
      if(isset($errorMsg) && $errorMsg) {
        echo "<p style=\"color: red;\">*",htmlspecialchars($errorMsg),"</p>\n\n";
      }
      if(isset($message) && $message) {
        echo "<p style=\"color: green;\">*",htmlspecialchars($message),"</p>\n\n";
      }
      ?>
      <label>CAP ID:</label>
      <form action="meeting_nights.php">
        <input type="text" name="capidrm" autofocus>
        <br>
        <input type="submit" value="Submit" name="rmuser0">
      </form>
    </div>
  </body>
</html>
