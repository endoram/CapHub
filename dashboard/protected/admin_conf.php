<?php
require "../includes/header.php";

if (isset($_POST['timezone_type'])) {
  require "../includes/config_m.php";
  $timezone = $_POST['timezone_type'];

  require "../includes/config_m.php";
  $query = "UPDATE squads SET time_zone='" . $timezone . "' WHERE FQSN='" . $_SESSION['FQSN'] . "'";   //Validate number is a CAPID in the database
  $conn->query($query);
  $conn->close();
}

if (isset($_POST['FQSN'])) {
  if ($_SESSION['privlv'] == 3) {
    $_SESSION['FQSN'] = $_POST['FQSN'];
  }
}

if (isset($_POST['capid0'])) {
  require "../includes/config_m.php";
  if ($_SESSION['privlv'] >= 2) {
    $x = 0;
    $query = "SELECT FQSN FROM cadet_coc WHERE FQSN='".$_SESSION['FQSN']."'";
    $result = $conn->query($query);
    while ($x <= ($result->num_rows - 1)) {
      $explo = explode(",", $_POST['capid'.$x]);
      $query = "UPDATE cadet_coc SET cap_id='".$explo[1]."' WHERE FQSN='".$_SESSION['FQSN']."' AND position='".$explo[0]."' AND flight_name='".$explo[2]."'";
      $conn->query($query);
      $x = $x + 1;
    }
    $conn->close();
  }
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
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>CapHub Settings</title>
  </head>
  <body>
    <div class="row p-3">
      <div class="container-fluid p-1">
        <div class="leftside">
          <b>Squadron Settings</b>
          <div class="sqmenubar">
            <ul>
              <li><a href="?timezone">Timezone</a><li>
              <li><a href="?edit_coc">Edit COC</a><li>
              <?php
                if ($_SESSION['privlv'] == 3){
                  echo '<li><a href="?changeFQSN"> Change FQSN</a><li>';
                }
              ?>
            </ul>
          </div>
        </div>

        <div class="middle">
          <div class="container-fluid">
            <?php
            if(isset($_GET['timezone'])){
              require "../includes/config_m.php";
              $query = "SELECT * FROM squads WHERE FQSN='" . $_SESSION['FQSN'] . "'";

              $result = $conn->query($query);
              if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                  echo "Current Timezone: <b>";
                  echo($row['time_zone']); echo(": ");
                  date_default_timezone_set($row['time_zone']);
                  echo(date("H:i:s"));
                }
                $conn->close();
              }
              else {
                echo "<script>alert('No Results Found');</script>";
                $conn->close();
              }
              echo '
              <h4>Select Timezone</h4>
              <form method="post" action="' .htmlspecialchars($_SERVER['PHP_SELF']) . '" accept-charset="UTF-8">
                <select name="timezone_type">
                  <option value=America/Chicago>Chicago</option>
                  <option value=America/Denver>Denver</option>
                  <option value=America/Phoenix>Phoenix</option>
                  <option value=America/Los_Angeles>Los Angeles</option>
                  <option value=America/Anchorage>Anchorage</option>
                  <option value=America/Honolulu>Honolulu</option>
                </select><br><br>
                <button type="submit" value="Save">Save</button>
              </form>';
            }
            if (isset($_GET['edit_coc'])) {
              require '../includes/config_m.php';
              echo '<div class="container-fluid">';
                $query = "SELECT * FROM cadet_coc WHERE FQSN='".$_SESSION['FQSN']."'";
                $result = $conn->query($query);
                if ($result->num_rows > 0) {
                  while($row = $result->fetch_assoc()) {
                  }
                  $conn->close();
                }
                else {
                  $FQSN = $_SESSION['FQSN'];
                  //echo "<script>alert('No Results Found');</script>";
                  $positions = array('Cadet Commander', 'CDC for Support', 'Finance', 'Personnel', 'Safety', 'Supply', 'Web', 'CDC for Operations', 'First Sergeant', 'Leadership Officer', 'Leadership NCO', 'Aerospace Officer', 'Aerospace NCO', 'ES Officer', 'ES NCO');
                  foreach ($positions as $key => $value) {
                    $query = "INSERT INTO cadet_coc (FQSN, position) VALUES ('$FQSN', '$value')";
                    $conn->query($query);
                  }
                  $conn->close();
                }
              echo "</div>";
              require "../includes/config_m.php";
              include "../includes/helpers.php";
              $query = "SELECT * FROM cadet_coc WHERE FQSN='".$_SESSION['FQSN']."'";
              echo '<form method="post" action="admin_conf.php" accept-charset="UTF-8">';

              $result = $conn->query($query);
              $x = 0;
              if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                  echo $row['flight_name']." ".$row['position'].": ";
                  echo "<select name=capid".$x.">";
                  if (strlen($row['cap_id']) > 5) {
                    $name = ARP($row['cap_id']);
                    echo "<option value='".$row['position'].",".$row['cap_id'].",".$row['flight_name']."'>".$name."</option>";
                  } else {
                    echo "<option value='".$row['position'].",".$row['cap_id'].",".$row['flight_name']."'>".$row['cap_id']."</option>";
                  }
                  $query1 = "SELECT first_name, last_name, cap_id FROM sq_members WHERE member_type='cadet' AND retire=0 AND FQSN='".$_SESSION['FQSN']."'";
                  echo $query1;
                  $result1 = $conn->query($query1);
                  if ($result1->num_rows > 0) {
                    while($row1 = $result1->fetch_assoc()) {
                      $name = $row1['first_name'] . " " . $row1['last_name'];
                      echo "<option value='".$row['position'].",". $row1['cap_id'] .",".$row['flight_name']. "'>" . $name . "</option>";
                    }
                  }
                  echo "</select><br>";
                  $x = $x + 1;
                }
              }
              else {
                echo "<script>alert('No Results Found');</script>";
                $conn->close();
              }

              echo '<button type="submit" value="Save">Save</button>';
              echo '</form>';

            }
            if (isset($_GET['changeFQSN'])) {
              if ($_SESSION['privlv'] == 3) {
                require "../includes/config_m.php";
                $query = "SELECT FQSN FROM squads";
                echo '<form method="post" action="admin_conf.php" accept-charset="UTF-8">';
                echo "<select name='FQSN'>";

                $result = $conn->query($query);
                if ($result->num_rows > 0) {
                  while($row = $result->fetch_assoc()) {
                    echo "<option value=" . $row['FQSN'] . ">" . $row['FQSN'] . "</option>";
                  }
                  $conn->close();
                }
                else {
                  echo "<script>alert('No Results Found');</script>";
                  $conn->close();
                }
                echo "</select>";
                echo '<button type="submit" value="Save">Save</button>';
                echo '</form>';
              }
            }
            ?>
          </div>
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