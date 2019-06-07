<?php
  require "../includes/header.php";

  if(isset($_POST['sent'])) {
    if($_POST['sent'] == "Vehicle ID:") {
      $vehicle_id = $_POST['input'];
      $vehicle_type = $_POST['vehicle_type'];

      require "../includes/config_m.php";
      $query = "SELECT * FROM vehicles WHERE vehicle_id='$vehicle_id'";
      $result = $conn->query($query);

      if ($result->num_rows > 0) {$errorMsg = "A vehicle with that ID has already been added"; $conn->close();}
      else {
        $query = "INSERT INTO vehicles (vehicle_id, vehicle_type, in_out, vehicle_status) VALUES ('$vehicle_id', '$vehicle_type', 'IN', 'Fully Operational')";
        $conn->query($query);
        $conn->close();
      }
    }

    if($_POST['sent'] == "Remove Vehicle ID:") {
      $vehicle_id = $_POST['input'];
      $query = "SELECT * FROM vehicles WHERE vehicle_id='$vehicle_id'";

      require "../includes/config_m.php";
      $result = $conn->query($query);
      if ($result->num_rows > 0) {
        $query = "DELETE FROM vehicles WHERE vehicle_id='$vehicle_id'";
        $conn->query($query);
        $conn->close();
        echo "Removed vehicle: $vehicle_id";
      }
      else{$errorMsg = "No vehicle has that ID"; $conn->close();}
    }

    if($_POST['sent'] == "CheckOut Vehicle ID:") {
      require "../includes/config_m.php";
      $vehicle_id = $_POST['input'];
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
      $query = "UPDATE vehicles SET in_out='OUT', name='$name' WHERE vehicle_id='$vehicle_id'";
      $conn->query($query);$conn->close();
    }

    if($_POST['sent'] == "CheckIn Vehicle ID:") {
      require "../includes/config_m.php";
      $vehicle_id = $_POST['input'];
      $query = "UPDATE vehicles SET in_out='IN', name='' WHERE vehicle_id='$vehicle_id'";

      $conn->query($query);$conn->close();
    }

    if($_POST['sent'] == "Vehicle ID: ") {
      require "../includes/config_m.php";
      $vehicle_id = $_POST['input'];
      $whatsbroken = $_POST['whatbroken'];
      $status = $_POST['change_status'];

      $query = "UPDATE vehicles SET vehicle_status='$status', notes='$whatsbroken' WHERE vehicle_id='$vehicle_id'";
      $conn->query($query);$conn->close();
    }
  }


  if(isset($_GET['addvehicle'])) {$data = "Vehicle ID:";  handleit($data);}
  if(isset($_GET['removevehicle'])) {$data = "Remove Vehicle ID:";  handleit($data);}
  if(isset($_GET['checkout'])) {$data = "CheckOut Vehicle ID:"; handleit($data);}
  if(isset($_GET['checkin'])) {$data = "CheckIn Vehicle ID:"; handleit($data);}
  if(isset($_GET['changestatus'])) {$data = "Vehicle ID: "; handleit($data);}


  function handleit($data) {
    require "../includes/config_m.php";
    unset($_GET['firstname, lastname, capid']);
    echo '<div class="form-popup" id="myForm">';
    echo '<form method="post" action="vehicles.php" class="form-container">';

    if(isset($_GET['checkout'])){
      echo '<label for="input"><b>' . $data . '</b></label>';
      $query = "SELECT vehicle_id FROM vehicles WHERE in_out='IN'";
      echo '<select name="input">';
      $result = $conn->query($query);

      if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
          echo "<option value='" . $row['vehicle_id'] . "'>" . $row['vehicle_id'] . "</option>";
        }
        $conn->close();
      }
      else {
        echo "<script>alert('All Vehicles checked out!');</script>";
        $conn->close();
      }
      echo '</select>';
      echo '<label for="input"><b>CAP ID:</b></label>';
      echo '<input type="text" name="capid" required>';
    }

    if(isset($_GET['checkin'])) {
      echo '<label for="input"><b>' . $data . '</b></label>';
      $query = "SELECT vehicle_id FROM vehicles WHERE in_out='OUT'";
      echo '<select name="input">';
      $result = $conn->query($query);

      if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
          echo "<option value='" . $row['vehicle_id'] . "'>" . $row['vehicle_id'] . "</option>";
        }
        $conn->close();
      }
      else {
        echo "<script>alert('No Vehicles checked out!');</script>";
        $conn->close();
      }
      echo '</select>';
    }

    if(isset($_GET['removevehicle'])) {
      echo '<label for="input"><b>' . $data . '</b></label>';
      $query = "SELECT vehicle_id FROM vehicles";
      echo '<select name="input">';
      $result = $conn->query($query);

      if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
          echo "<option value='" . $row['vehicle_id'] . "'>" . $row['vehicle_id'] . "</option>";
        }
        $conn->close();
      }
      else {
        echo "<script>alert('No Results Found');</script>";
        $conn->close();
      }
      echo '</select>';
    }

    if(isset($_GET['addvehicle'])) {
      echo '<label for="input"><b>' . $data . '</b></label>';
      echo '<input type="text" name="input" required>';
      echo '
        <select name="vehicle_type">
          <option value=Van>Van</option>
          <option value=?>?</option>
          <option value=?>?</option>
        </select>
      ';
    }

    if(isset($_GET['changestatus'])){
      echo '<label for="input"><b>' . $data . '</b></label>';
      $query = "SELECT vehicle_id FROM vehicles";
      echo '<select name="input">';
      $result = $conn->query($query);

      if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
          echo "<option value='" . $row["vehicle_id"] . "'>" . $row['vehicle_id'] . "</option>";
        }
        $conn->close();
      }
      else {
        echo "<script>alert('No Results Found');</script>";
        $conn->close();
      }
      echo '</select>';

      echo '<label for="input"><b>Notes:</b></label>';
      echo '<input type="text" name="whatbroken">';
      echo '
      <select name="change_status">
        <option value="Fully Operational">Fully Operational</option>
        <option value="Needs Gas">Needs Gas</option>
        <option value="Tire Pressure Low">Low Tire Pressure</option>
        <option value="Engine Light">Engine Light</option>
        <option value="Else">Else</option>
      </select> ';
    }
    echo '<button type="submit" value="' . $data . '" name="sent" class="btn">Submit</button>';
    echo '<button type="button" class="btn cancel" onclick="closeForm()">Close</button>';
    echo '</form>';
    echo '</div>';
  }
?>



<html>
  <head>
    <title="Vehicles CAPhub">
    <link rel="stylesheet" type="text/css" href="style.css">
  </head>
  <body>
    <div class="row">
      <div class="leftside">
        <div class="sqmenubar">
          <ul>
            <li><a href="?addvehicle">Add Vehicle</a><li>
            <li><a href="?removevechicle">Remove Vehicle</a><li>
            <li><a href="?checkout">CheckOut Vehicle</a><li>
            <li><a href="?checkin">CheckIn Vehicle</a><li>
            <li><a href="?changestatus">Change Vechicle Status</a><li>
          </ul>
        </div>
      </div>
      <div class="middle">
        <div class="radiotable">
          <br>
          <?php
          $table = array("SELECT * FROM vehicles WHERE in_out='OUT'", "SELECT * FROM vehicles");

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
                    <th>Vechicle ID</th>
                    <th>Type</th>
                    <th>In/Out</th>
                    <th>Name</th>
                    <th>Status</th>
                    <th>Notes</th>
                  </tr>
              ';
              while($row = $result->fetch_assoc()) {
                echo "<tr>
                <td>" . $row["vehicle_id"] . "</td>
                <td>" . $row["vehicle_type"] . "</td>
                <td>" . $row["in_out"] . "</td>
                <td>" . $row["name"] . "</td>";

                if($row["vehicle_status"] == "Fully Operational") {echo '<td bgcolor="#00FF00">' . $row["vehicle_status"] . "</td>";}
                else {if($row["vehicle_status"] == "Needs Gas" || $row["vehicle_status"] == "Tire Pressure Low" || $row["vehicle_status"] == "Engine Light") {echo "<td bgcolor='#FFFF00'>" . $row["vehicle_status"] . "</td>";}
                  else{  if($row["vehicle_status"] == "Else") {echo "<td bgcolor='#FF0000'>" . $row["vehicle_status"] . "</td>";}}
                }
              echo "
                <td>" . $row["notes"] . "</td>
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
  </body>
</html>
