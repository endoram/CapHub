<?php
require "../includes/header.php";

if(isset($_POST['sent'])) {
  if($_POST['sent'] == "Radio ID:") {
    $radio_id = $_POST['input'];
    $radio_type = $_POST['radio_type'];

    require "../includes/config_m.php";
    $query = "SELECT * FROM comms WHERE radio_id='$radio_id'";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {$errorMsg = "A radio with that ID has already been added"; $conn->close();}
    else {
      $query = "INSERT INTO comms (radio_id, radio_type, in_out, status) VALUES ('$radio_id', '$radio_type', 'IN', 'Fully Operational')";
      $conn->query($query);
      $conn->close();
    }
  }
  if($_POST['sent'] == "Remove Radio ID:") {
    $radio_id = $_POST['input'];
    $query = "SELECT * FROM comms WHERE radio_id='$radio_id'";

    require "../includes/config_m.php";
    $result = $conn->query($query);
    if ($result->num_rows > 0) {
      $query = "DELETE FROM comms WHERE radio_id='$radio_id'";
      $conn->query($query);
      $conn->close();
      echo "Removed radio: $radio_id";
    }
    else{$errorMsg = "No radio has that ID"; $conn->close();}
  }
  if($_POST['sent'] == "CheckOut Radio ID:") {
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

    $query = "UPDATE comms SET in_out='OUT', name='$name' WHERE radio_id='$radio_id'";
    $conn->query($query);$conn->close();
  }
  if($_POST['sent'] == "CheckIn Radio ID:") {
    require "../includes/config_m.php";
    $radio_id = $_POST['input'];
    $query = "UPDATE comms SET in_out='IN', name='' WHERE radio_id='$radio_id'";

    $conn->query($query);$conn->close();
  }
  if($_POST['sent'] == "Radio ID: ") {
    require "../includes/config_m.php";
    $radio_id = $_POST['input'];
    $whatsbroken = $_POST["whatbroken"];
    $status = $_POST['change_status'];

    $query = "UPDATE comms SET status='$status' WHERE radio_id='$radio_id'";
    $conn->query($query);$conn->close();
  }
}

if(isset($_GET['addradio'])) {$data = "Radio ID:";  handleit($data);}
if(isset($_GET['removeradio'])) {$data = "Remove Radio ID:";  handleit($data);}
if(isset($_GET['checkout'])) {$data = "CheckOut Radio ID:"; handleit($data);}
if(isset($_GET['checkin'])) {$data = "CheckIn Radio ID:"; handleit($data);}
if(isset($_GET['changestatus'])) {$data = "Radio ID: "; handleit($data);}

function handleit($data) {
  require "../includes/config_m.php";
  unset($_GET['firstname, lastname, capid']);
  echo '<div class="form-popup" id="myForm">';
  echo '<form method="post" action="comms.php" class="form-container">';

  if(isset($_GET['checkout'])){
    echo '<label for="input"><b>' . $data . '</b></label>';
    $query = "SELECT radio_id FROM comms WHERE in_out='IN'";
    echo '<select name="input">';
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
      while($row = $result->fetch_assoc()) {
        echo "<option value=" . $row['radio_id'] . ">" . $row['radio_id'] . "</option>";
      }
      $conn->close();
    }
    else {
      echo "<script>alert('No Results Found');</script>";
      $conn->close();
    }
    echo '</select>';
    echo '<label for="input"><b>CAP ID:</b></label>';
    echo '<input type="text" name="capid" required>';
  }

  if(isset($_GET['checkin'])) {
    echo '<label for="input"><b>' . $data . '</b></label>';
    $query = "SELECT radio_id FROM comms WHERE in_out='OUT'";
    echo '<select name="input">';
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
      while($row = $result->fetch_assoc()) {
        echo "<option value=" . $row['radio_id'] . ">" . $row['radio_id'] . "</option>";
      }
      $conn->close();
    }
    else {
      echo "<script>alert('No Results Found');</script>";
      $conn->close();
    }
    echo '</select>';
  }

  if(isset($_GET['removeradio'])) {
    echo '<label for="input"><b>' . $data . '</b></label>';
    $query = "SELECT radio_id FROM comms";
    echo '<select name="input">';
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
      while($row = $result->fetch_assoc()) {
        echo "<option value=" . $row['radio_id'] . ">" . $row['radio_id'] . "</option>";
      }
      $conn->close();
    }
    else {
      echo "<script>alert('No Results Found');</script>";
      $conn->close();
    }
    echo '</select>';
  }

  if(isset($_GET['addradio'])) {
    echo '<label for="input"><b>' . $data . '</b></label>';
    echo '<input type="text" name="input" required>';
    echo '
      <select name="radio_type">
        <option value=ISR>ISR</option>
        <option value=VHF>VHF</option>
        <option value=HF>HF</option>
      </select>
    ';
  }

  if(isset($_GET['changestatus'])){
    echo '<label for="input"><b>' . $data . '</b></label>';
    $query = "SELECT radio_id FROM comms";
    echo '<select name="input">';
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
      while($row = $result->fetch_assoc()) {
        echo "<option value=" . $row['radio_id'] . ">" . $row['radio_id'] . "</option>";
      }
      $conn->close();
    }
    else {
      echo "<script>alert('No Results Found');</script>";
      $conn->close();
    }
    echo '</select>';

    echo '<label for="input"><b>Whats Broken:</b></label>';
    echo '<input type="text" name="whatbroken">';
    echo '
    <select name="change_status">
      <option value="Fully Operational">Fully Operational</option>
      <option value=Operational>Operational</option>
      <option value=Broken>Broken</option>
      <option value=Batteries>Out of Batteries</option>
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
            <li><a href="?addradio">Add Radio</a><li>
            <li><a href="?removeradio">Remove Radio</a><li>
            <li><a href="?checkout">CheckOut Radio</a><li>
            <li><a href="?checkin">CheckIn Radio</a><li>
            <li><a href="?changestatus">Change Radio Status</a><li>
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
          $table = array("SELECT * FROM comms WHERE in_out='OUT'","SELECT * FROM comms WHERE radio_type='ISR'", "SELECT * FROM comms WHERE radio_type='VHF'", "SELECT * FROM comms WHERE radio_type='HF'");

          foreach ($table as $key => $value) {
            require "../includes/config_m.php";
            $result = $conn->query($value);
              //<th>Radio Name</th>
              //<td>" . $row["radio_name"] . "</td>
            if ($result->num_rows > 0) {
              echo '
              <br>
                <table>
                  <colgroup>
                    <col span="5" style="background-color:lightgrey">
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
              //  <td>" . $row["status"] . "</td>
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
          }?>
        </div>
      </div>
    </div>
  </body>
</html>
