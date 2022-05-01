<?php
require "../includes/header.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  require "../includes/config_m.php";
  $timezone = $_POST['timezone_type'];
  #echo($timezone);


  require "../includes/config_m.php";
  $query = "UPDATE squads SET time_zone='" . $timezone . "' WHERE FQSN='" . $_SESSION['FQSN'] . "'";   //Validate number is a CAPID in the database
  #echo($query);
  $conn->query($query);
  $conn->close();
}
?>



<html>
  <head>
    <title>CapHub Settings</title>
  </head>
  <body>
    <h2>Squadron Settings</h2>
    <h4>Current Timezone:</h4>
    <?php
    require "../includes/config_m.php";
    $query = "SELECT * FROM squads WHERE FQSN='" . $_SESSION['FQSN'] . "'";

    $result = $conn->query($query);
    if ($result->num_rows > 0) {
      while($row = $result->fetch_assoc()) {
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
     ?>
    <h4>Select Timezone</h4>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" accept-charset="UTF-8">
      <select name="timezone_type">
        <option value=America/Chicago>Chicago</option>
        <option value=America/Denver>Denver</option>
        <option value=America/Phoenix>Phoenix</option>
        <option value=America/Los_Angeles>Los Angeles</option>
        <option value=America/Anchorage>Anchorage</option>
        <option value=America/Honolulu>Honolulu</option>
      </select><br><br>
      <button type="submit" value="Save">Save</button>
    </form>
  </body>
</html>
