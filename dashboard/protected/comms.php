<?php
require "../includes/header.php";

if(isset($_POST['sent'])) {
  if($_POST['sent'] == "Add Equipment ID:") {
    $radio_id = $_POST['input'];
    $radio_type = $_POST['radio_type'];
    $description = $_POST['description'];
    $date = date("Y-m-d");
    $time = date('H:i:s');

    require "../includes/config_m.php";
    $query = "SELECT * FROM comms WHERE radio_id='$radio_id' && FQSN='" . $_SESSION['FQSN'] . "'";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {$errorMsg = "A radio with that ID has already been added"; $conn->close();}
    else {
      $query = "INSERT INTO comms (radio_id, radio_type, in_out, status, description, FQSN) VALUES ('$radio_id', '$radio_type', 'IN', 'Fully Operational', '$description', '" .  $_SESSION['FQSN'] . "')";
      $query1 = "INSERT INTO comms_log (`FQSN`, `updated_by`, `radio_id`, `status`, `date`, `time`) VALUES ('" . $_SESSION['FQSN'] . "','".$_SESSION["capid"]."', '$radio_id', 'ADDED ITEM', '$date', '$time')";


      $conn->query($query);
      $conn->query($query1);$conn->close();
    }
  }
  if($_POST['sent'] == "Remove Equipment ID:") {
    $radio_id = $_POST['input'];
    $query = "SELECT * FROM comms WHERE radio_id='$radio_id' && FQSN='" . $_SESSION['FQSN'] . "'";

    require "../includes/config_m.php";
    $result = $conn->query($query);
    if ($result->num_rows > 0) {
      $date = date("Y-m-d");
      $time = date('H:i:s');
      $query = "DELETE FROM comms WHERE radio_id='$radio_id' && FQSN='" . $_SESSION['FQSN'] . "'";
      $query1 = "INSERT INTO comms_log (`FQSN`, `updated_by`, `radio_id`, `status`, `date`, `time`) VALUES ('" . $_SESSION['FQSN'] . "','".$_SESSION["capid"]."', '$radio_id', 'DELETED ITEM', '$date', '$time')";

      echo $query1;

      $conn->query($query);
      $conn->query($query1);$conn->close();
      echo "Removed radio: $radio_id";
    }
    else{$errorMsg = "No radio has that ID"; $conn->close();}
  }
  if($_POST['sent'] == "Check Out Equipment ID:") {
    require "../includes/config_m.php";
    $radio_id = $_POST['input'];
    $cap_id = $_POST['capid'];

    $query = "SELECT * FROM sq_members WHERE cap_id='$cap_id'";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
      while($row = $result->fetch_assoc()) {
        $firstname = $row['first_name'];
        $lastname = $row['last_name'];
      }
      $name = $firstname . " " . $lastname;
    }
    $date = date("Y-m-d");
    $time = date('H:i:s');

    $query = "UPDATE comms SET in_out='OUT', name='$name', out_date='$date' WHERE radio_id='$radio_id' && FQSN='" . $_SESSION['FQSN'] . "'";
    $query1 = "INSERT INTO comms_log (`FQSN`, `updated_by`, `radio_id`, `name`, `in_out`, `date`, `time`) VALUES ('" . $_SESSION['FQSN'] . "','".$_SESSION["capid"]."', '$radio_id', '$name', 'OUT', '$date', '$time')";

    $conn->query($query);
    $conn->query($query1);$conn->close();
  }
  if($_POST['sent'] == "Check In Equipment ID:") {
    require "../includes/config_m.php";
    $radio_id = $_POST['input'];
    $date = date('Y-m-d');
    $time = date('H:i:s');
    $query = "UPDATE comms SET in_out='IN', name='' WHERE radio_id='$radio_id' && FQSN='" . $_SESSION['FQSN'] . "'";
    $query1 = "INSERT INTO comms_log (`FQSN`, `updated_by`, `radio_id`, `in_out`, `date`, `time`) VALUES ('" . $_SESSION['FQSN'] . "','".$_SESSION["capid"]."', '$radio_id', 'IN', '$date', '$time')";

    $conn->query($query);
    $conn->query($query1);$conn->close();
  }
  if($_POST['sent'] == "Equipment ID: ") {
    require "../includes/config_m.php";
    $radio_id = $_POST['input'];
    $whatsbroken = $_POST["whatbroken"];
    $status = $_POST['change_status'];
    $date = date('Y-m-d');
    $time = date('H:i:s');

    $query = "UPDATE comms SET status='$status' WHERE radio_id='$radio_id' && FQSN='" . $_SESSION['FQSN'] . "'";
    $query1 = "INSERT INTO comms_log (`FQSN`, `updated_by`, `radio_id`, `status`, `date`, `time`) VALUES ('" . $_SESSION['FQSN'] . "','".$_SESSION["capid"]."', '$radio_id', '$status', '$date', '$time')";

    $conn->query($query);
    $conn->query($query1);$conn->close();
  }
}

if(isset($_GET['addradio'])) {$data = "Add Equipment ID:";  handleit($data);}
if(isset($_GET['removeradio'])) {$data = "Remove Equipment ID:";  handleit($data);}
if(isset($_GET['checkout'])) {$data = "Check Out Equipment ID:"; handleit($data);}
if(isset($_GET['checkin'])) {$data = "Check In Equipment ID:"; handleit($data);}
if(isset($_GET['changestatus'])) {$data = "Equipment ID: "; handleit($data);}

function handleit($data) {
  require "../includes/config_m.php";
  unset($_GET['firstname, lastname, capid']);
  echo '<div class="form-popup" id="myForm">';
  echo '<form method="post" action="comms.php" class="form-container">';

  if(isset($_GET['checkout'])){
    echo '<label for="input"><b>' . $data . '</b></label>';
    $query = "SELECT radio_id FROM comms WHERE in_out='IN' && FQSN='" . $_SESSION['FQSN'] . "'";
    echo '<input list="input" name="input" autofocus>';
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
    $query = "SELECT radio_id FROM comms WHERE in_out='OUT' && FQSN='" . $_SESSION['FQSN'] . "'";
    echo '<input list="input" name="input" autofocus>';
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

  if(isset($_GET['removeradio'])) {
    echo '<label for="input"><b>' . $data . '</b></label>';
    $query = "SELECT radio_id FROM comms WHERE FQSN='" . $_SESSION['FQSN'] . "'";
    echo '<input list="input" name="input" autofocus>';
    echo '<datalist id="input">';
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
      while($row = $result->fetch_assoc()) {
        echo "<option value='" . $row['radio_id'] . "'>";
      }
      $conn->close();
    }
    else {
      echo "<script>alert('No Results Found');</script>";
      $conn->close();
    }
    echo '</datalist>';
  }

  if(isset($_GET['addradio'])) {
    echo '<label for="input"><b>' . $data . ' </b></label>';
    echo '<input type="text" name="input" required autofocus>';
    echo ' ';
    echo '<label for="input"><b>Description: </b></label>';
    echo '<input type="text" name="description" required>';
    echo ' ';
    echo '<label><b>Type: </b></label>';
    echo '
      <select name="radio_type">
       <!-- <option value=ISR>ISR</option> -->
        <option value=VHF>VHF</option>
        <option value=HF>HF</option>
        <option value=Equipment>Equipment</option>
      </select>
    ';
  }

  if(isset($_GET['changestatus'])){
    echo '<label for="input"><b>' . $data . '</b></label>';
    $query = "SELECT radio_id FROM comms WHERE FQSN='" . $_SESSION['FQSN'] . "'";
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
      echo "<script>alert('No Results Found');</script>";
      $conn->close();
    }
    echo '</datalist>';

    echo '<label for="input"><b>Whats Broken:</b></label>';
    echo '<input type="text" name="whatbroken">';
    echo '
    <select name="change_status">
      <option value="Fully Operational">Fully Operational</option>
      <option value="Operational">Operational</option>
      <option value="Broken">Broken</option>
      <option value="Batteries">Out of Batteries</option>
    </select> ';
  }
  echo '<button type="submit" value="' . $data . '" name="sent" class="btn">Submit</button>';
  echo '<button type="button" class="btn cancel" onclick="closeForm()">Close</button>';
  echo '</form>';
  echo '</div>';
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

<html>
  <head>
    <title>Coms CAPhub</title>
    <link rel="stylesheet" type="text/css" href="style.css">
  </head>
  </body>
    <div class="row">
      <div class="leftside">
        <div class="sqmenubar">
          <ul>
            <li><a href="?addradio">Add Equipment</a><li>
            <li><a href="?removeradio">Remove Equipment</a><li>
            <li><a href="?checkout">Check Out Equipment</a><li>
            <li><a href="?checkin">Check In Equipment</a><li>
            <li><a href="?changestatus">Change Equipment Status</a><li>
            <li><a href="../includes/comm_log.php">Comm Log</a><li>
          </ul>
        </div>
      </div>
      <?php
        if(isset($errorMsg) && $errorMsg) {
          echo "<p style=\"color: red;\">*",htmlspecialchars($errorMsg),"</p>\n\n";
        }
      ?>
      <div class="middle">
        <div class="radiotable">
          <br>
          <?php
          # Old But Working... Just you know... IDK
          $table = array(
            array("SELECT * FROM comms WHERE in_out='OUT' && FQSN='" . $_SESSION['FQSN'] . "'", "Equipment Out"),
           # array("SELECT * FROM comms WHERE radio_type='ISR'", "ISR Radios"),
            array("SELECT * FROM comms WHERE radio_type='VHF' && FQSN='" . $_SESSION['FQSN'] . "'", "VHF Radios"),
            array("SELECT * FROM comms WHERE radio_type='HF' && FQSN='" . $_SESSION['FQSN'] . "'", "HF Radios"),
            array("SELECT * FROM comms WHERE radio_type='Equipment' && FQSN='" . $_SESSION['FQSN'] . "'", "Misc Equipment")
          );  
       /*   $table = array(
            array("SELECT * FROM comms WHERE in_out='OUT' && FQSN='" . $_SESSION['FQSN'] . "'", "Equipment Out"),
            array("SELECT * FROM comms WHERE radio_type='ISR' && FQSN='" . $_SESSION['FQSN'] . "'", "ISR Radios"),
          ); */
          #for ($x = 0; $x <= 4; $x++) {
          for ($x = 0; $x <= 3; $x++) {
            $value = $table[$x][0];
            require "../includes/config_m.php";
            $result = $conn->query($value);
            if ($result->num_rows > 0) {
              echo "<h4>" . $table[$x] [1] . "</h4>";
              echo '
                <table>
                  <colgroup>
                    <col span="7" style="background-color:lightgrey">
                  </colgroup>
                  <tr>
                    <th>Equipment ID</th>
                    <th>Type</th>
                    <th>Status</th>
                    <th>In/Out</th>
                    <th>Date Out</th>
                    <th>Name</th>
                    <th>Description</th>
                  </tr>
              ';
              while($row = $result->fetch_assoc()) {
                echo "<tr>
                <td>" . $row["radio_id"] . "</td>
                <td>" . $row["radio_type"] . "</td>";
              if($row["status"] == "Fully Operational") {echo '<td bgcolor="#00FF00">' . $row["status"] . "</td>";}
              else {
                if($row["status"] == "Operational") {echo "<td bgcolor='#FFFF00'>" . $row["status"] . "</td>";}
                else{
                  if($row["status"] == "Broken") {echo "<td bgcolor='#FF0000'>" . $row["status"] . "</td>";}
                  else {
                    if($row["status"] == "Batteries") {echo "<td bgcolor='#000000'>" . $row["status"] . "</td>";}
                  }
                }
              }
              echo "
                <td>" . $row["in_out"] . "</td>
                <td>" . $row["out_date"] . "</td>
                <td>" . $row["name"] . "</td>
                <td>" . $row["description"] . "</td>
                </tr>";
              }
            }
            else {
            $conn->close();
            }
            echo "</table>";
          }?>
        </div>
      </div>
    </div>
  </body>
    <div class="container-fluid p-0">
    <?php
      require "../includes/footer.php";
    ?>
    </div>
</html>
