<?php
require "../includes/header.php";

if (isset($_POST['timezone_type'])) {
  require "../includes/config_m.php";
  $timezone = $_POST['timezone_type'];

  require "../includes/config_m.php";
  $query = "UPDATE squads SET time_zone='" . $timezone . "' WHERE FQSN='" . $_SESSION['FQSN'] . "'";   //Validate number is a CAPID in the database
  #echo($query);
  $conn->query($query);
  $conn->close();
}

if (isset($_POST['FQSN'])) {
  if ($_SESSION['privlv'] == 3) {
    $_SESSION['FQSN'] = $_POST['FQSN'];
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
    <div class="row">
      <div class="leftside">
        <b>Squadron Settings</b>
        <div class="sqmenubar">
          <ul>
            <li><a href="?timezone">Timezone</a><li>
            <?php
              if ($_SESSION['privlv'] == 3){
                echo '<li><a href="?changeFQSN"> Change FQSN</a><li>';
              }
            ?>
          </ul>
        </div>
      </div>
      <div class="middle">
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
  </body>
</html>