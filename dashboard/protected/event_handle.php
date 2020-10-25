<?php
require "../includes/header.php";

if(isset($_GET["eventid"])) {
  $event_id = $_GET["eventid"];

  $query = "SELECT * FROM events WHERE event_id='$event_id'";
  require "../includes/config_m.php";
  $result = $conn->query($query);
  if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
      $event_name = $row["event_name"];
    }
  }
}


if(isset($_GET['sign_in'])) {     //If user has intered an input
  date_default_timezone_set("America/Denver");
  $time = date("H:i:s");
  $capid = $_GET['capidrm'];
  $event_id = $_GET["eventid"];

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
        $name1 = $name . ' ' . $name0;
        $membertype = $row['member_type'];
      }
      if($db_capid == $capid) {
        $date = date("Y/m/d");
        require "../includes/config_m.php";
        $query = "SELECT * FROM events WHERE cap_id=" . $db_capid . " AND event_id='" . $event_id . "'";
        //echo $query;
        $result = $conn->query($query);

        if ($result->num_rows > 0) {    //If already signed in for that day sign out
          $message = $name . " signed out";
          $date = date("Y/m/d");
          $time = date("H:i:s");
          $query = "UPDATE events SET time_out='" . $time . "' WHERE cap_id=" . $capid . " AND event_id='" . $event_id . "'";
          $conn->query($query);
        }
        else {      //If not sign you in
          $message = $name . " signed in";
          $date = date("Y/m/d");
          $time = date("H:i:s");
          $capid = $_GET['capidrm'];

          $query = "INSERT INTO events (event_id, date_in, first_name, last_name, time_in, member_type, cap_id) VALUES ($event_id, '$date', '$name', '$name0', '$time', '$membertype', $capid)";
        $conn->query($query);
        }
      }
    }
    else {$errorMsg = "CAP ID not in database Please talk to the cadet admin NCO";}
    $conn->close();
  }
}

if(isset($_GET['sent'])) {
  if($_GET['sent'] == "CheckOut Radio ID:") {
    require "../includes/config_m.php";
    $radio_id = $_POST['input'];
    $cap_id = $_POST['capid'];

    echo $_GET['sent'];

    $query = "SELECT * FROM sq_members WHERE cap_id='$cap_id'";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
      while($row = $result->fetch_assoc()) {
        $firstname = $row['first_name'];
        $lastname = $row['last_name'];
      }
      $name = $firstname . " " . $lastname;
    }

    $query = "UPDATE comms SET in_out='OUT', name='$name' WHERE radio_id='$radio_id'";
    $conn->query($query);$conn->close();
  }
  if($_GET['sent'] == "CheckIn Radio ID:") {
    require "../includes/config_m.php";
    $radio_id = $_POST['input'];
    $query = "UPDATE comms SET in_out='IN', name='' WHERE radio_id='$radio_id'";
  }
}

  if(isset($_GET['checkout'])) {$data = "CheckOut Radio ID:"; handleit($data, $event_id);}
  if(isset($_GET['checkin'])) {$data = "CheckIn Radio ID:"; handleit($data, $event_id);}

function handleit($data, $event_id) {
  require "../includes/config_m.php";
  unset($_GET['firstname, lastname, capid']);
  echo '<div class="form-popup" id="myForm">';
  echo '<form method="post" action="event_handle.php?eventid=' . $event_id . '" class="form-container">';

  if(isset($_GET['checkout'])){
    echo '<label for="input"><b>' . $data . '</b></label>';
    $query = "SELECT radio_id FROM comms WHERE in_out='IN'";
    echo '<input list="input" name="input">';
    echo '<datalist id="input">';
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
      while($row = $result->fetch_assoc()) {
        echo "<option value='" . $row['radio_id'] . "'>";
      }
      $conn->close();
    }
    else {
      echo "<script>alert('All radios checked out!');</script>";
      $conn->close();
    }
    echo '</datalist>';
    echo '<label for="input"><b>CAP ID:</b></label>';
    echo '<input type="text" name="capid" required>';
  }

  if(isset($_GET['checkin'])) {
    echo '<label for="input"><b>' . $data . '</b></label>';
    $query = "SELECT radio_id FROM comms WHERE in_out='OUT'";
    echo '<input list="input" name="input">';
    echo '<datalist id="input">';
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
      while($row = $result->fetch_assoc()) {
        echo "<option value='" . $row['radio_id'] . "'>";
      }
      $conn->close();
    }
    else {
      echo "<script>alert('No Radios checked out!');</script>";
      $conn->close();
    }
    echo '</datalist>';
  }

  echo '<button type="submit" value="' . $data . '" name="sent" class="btn">Submit</button>';
  echo '<button type="button" class="btn cancel" onclick="closeForm()">Close</button>';
  echo '</form>';
  echo '</div>';
}
?>


<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="../libs/calendar/datepicker.min.css">
    <title>Squadron Events</title>
  </head>
  <body>
    <div class="row">
      <div class="leftside">
        <div class="sqmenubar">
          <ul>
            <li><a href="?eventid=<?echo $event_id;?>&equipment_add=1">Assign Equipment</a><li>
            <li><a href="../includes/guestsign.php?event_signin=<?echo $event_id;?>">Guest Sign-In</a><li>
            <? if(isset($_GET["equipment_add"])) { ?>
              <li><a href="?eventid=<?echo $event_id;?>&checkin=1&equipment_add=1">Equipment Sign-In</a><li>
              <li><a href="?eventid=<?echo $event_id;?>&checkout=1&equipment_add=1">Equipment Sign-Out</a><li>
            <? } ?>
          </ul>
        </div>
      </div>
      <div class="middle">

          <br>
          <div class="meetingfor">
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
            if(isset($_GET["equipment_add"])) {
             } else { ?>
                <form action="event_handle.php">
                  <label>Sign In:</label><br>
                  <label>CAP ID:</label><input type="text" name="capidrm" autofocus> <!--Getting user's CAPID-->
                  <input type="submit" value="Submit" name="sign_in">
                  <input type="hidden" value="<?echo $event_id;?>" name="eventid">
                </form>
              <? } ?>
          </div>

          <div class="middle">
            <div class="radiotable">
            <?php
              if(isset($_GET["equipment_add"])) {
                $table = array("SELECT * FROM comms WHERE in_out='OUT'","SELECT * FROM comms WHERE radio_type='ISR'", "SELECT * FROM comms WHERE radio_type='VHF'", "SELECT * FROM comms WHERE radio_type='HF'");

                foreach ($table as $key => $value) {
                  require "../includes/config_m.php";
                  $result = $conn->query($value);
                  if ($result->num_rows > 0) {
                    echo '
                    <br>
                      <table>
                        <colgroup>
                          <col span="6" style="background-color:lightgrey">
                        </colgroup>
                        <tr>
                          <th>Radio ID</th>
                          <th>Type</th>
                          <th>Status</th>
                          <th>In/Out</th>
                          <th>Name</th>
                        </tr>
                    ';
                    while($row = $result->fetch_assoc()) {
                      echo "<tr>
                      <td>" . $row["radio_id"] . "</td>
                      <td>" . $row["radio_type"] . "</td>";
                    if($row["status"] == "Fully Operational") {echo '<td bgcolor="#00FF00">' . $row["status"] . "</td>";}
                    else {if($row["status"] == "Operational") {echo "<td bgcolor='#FFFF00'>" . $row["status"] . "</td>";}
                      else{  if($row["status"] == "Broken") {echo "<td bgcolor='#FF0000'>" . $row["status"] . "</td>";}
                        if($row["status"] == "Batteries") {echo "<td bgcolor='#000000'>" . $row["status"] . "</td>";}
                      }
                    }
                    echo "
                      <td>" . $row["in_out"] . "</td>
                      <td>" . $row["name"] . "</td>
                      </tr>";
                    }
                  }
                  else {
                  $conn->close();
                  }
                  echo "</table>";
                }
              }
              ?>
            </div>
          </div>

          <div class="radiotable">
          <?php
            require "../includes/config_m.php";
            $query = "SELECT event_name FROM events WHERE event_id='$event_id' AND event_unique=1";
            $result = $conn->query($query);
            if ($result->num_rows > 0) {
              while($row = $result->fetch_assoc()) {
                echo "<h2 class='eventheader'>" . $row['event_name']. "</h2>";
              }
            }

            $query = "SELECT * FROM events WHERE event_id='$event_id'";
            $result = $conn->query($query);
            if ($result->num_rows > 0) {
              echo ' <h2 class="eventheader">' . $event_name . '</h2>
              <br>
                <table>
                <colgroup>
                  <col span="6" style="background-color:lightgrey">
                </colgroup>
                  <tr>
                    <th>Name</th>
                    <th>Eqipment Out</th>
                    <th>Time In</th>
                    <th>Time Out</th>
                  </tr>
              ';
              while($row = $result->fetch_assoc()) {
                echo "<tr>
                <td>" . $row["first_name"] . " " . $row["last_name"] . "</td>
                <td>" . $row["assigned_equip"] . "</td>
                <td>" . $row["time_in"] . "</td>
                <td>" . $row["time_out"] . "</td>
                </tr>";
              }
            }
            else {
            $conn->close();
            }
            echo "</table>";
          ?>
        </div>
      </div>
  </body>
</html>
