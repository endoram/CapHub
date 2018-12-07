<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $firstname = $_POST['firstname'];
  $lastname = $_POST['lastname'];
  $capid = $_POST['capid'];
  $cadetornot = $_POST['cadetornot'];

  $x = 0;

  if ($capid == "" or !is_numeric($capid)) {
    $errorMsg = "Invalid Cap ID";
    $x = 1;
  }
  if ($firstname == NULL or preg_match('/[^A-Za-z]/', $firstname)) {
    $errorMsg = "Invalid Firstname";
    $x = 1;
  }
  if ($lastname == NULL or preg_match('/[^A-Za-z]/', $lastname)) {
    $errorMsg = "Invalid lastname";
    $x = 1;
  }
  if ($cadetornot == ""){
    $errorMsg = "Invalid cadet or senior option";
    $x = 1;
  }
  if (!in_array($cadetornot, array('cadet','senior', 'Cadet', 'Senior'), true )) {
    $errorMsg = "Invalid cadet or senior option";
    $x = 1;
  }
  if ($x == 0) {adduser($firstname, $lastname, $capid, $cadetornot, $errorMsg);}
}

function adduser($firstname, $lastname, $capid, $cadetornot, $errorMsg) {
  require "config_m.php";

  $y = 0;

  $query = "SELECT cap_id FROM sq_members WHERE cap_id=" . $capid;
  $result = $conn->query($query);

  if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
      if ($row['cap_id'] == $capid) {
        $GLOBALS['errorMsg'] = "A member already is asigned that CAP ID";
        $y = 1;
      }
    }
  }

  if ($y == 0) {
    $query = "INSERT INTO sq_members (cap_id, first_name, last_name, cadet_senior) VALUES (" . $capid . ",'" . $firstname . "', '" . $lastname . "', '" . $cadetornot . "')";
    $conn->query($query);
    $conn->close();
    header("Location: ../protected/sqmembers.php");
  }
  $conn->close();
}

require "config_m.php";
?>


<html>
  <head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <img src="../images/banner.png">
    <link rel="stylesheet" type="text/css" href="../protected/style.css">
    <title>CapHub Add Member</title>
  </head>
  <body>
    <div class="addmemberform">
      <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" accept-charset="UTF-8">
        <?php
        if(isset($errorMsg) && $errorMsg) {
          echo "<p style=\"color: red;\">*",htmlspecialchars($errorMsg),"</p>\n\n";
        }?>
        <label for="firstname">First name:</label> <input type="text" name="firstname" align="right" value=<?PHP if(isset($_POST['capid'])) echo htmlspecialchars($_POST['firstname']); ?>><br>
        <label for="firstname">Last name:</label> <input type="text" name="lastname" align="right" value=<?PHP if(isset($_POST['capid'])) echo htmlspecialchars($_POST['lastname']); ?>><br>
        <label for="capid">CAP ID:</label> <input type="text" name="capid" align="right" value="<?PHP if(isset($_POST['capid'])) echo htmlspecialchars($_POST['capid']); ?>"><br>
        <label for="cadetornot">Cadet Or Senior:</label> <input type="cadetornot" name="cadetornot" align="right" value="<?PHP if(isset($_POST['senior'])) echo htmlspecialchars($_POST['senior']); ?>"><br>
        <input type="submit" value="Add Member">
      </form>
    </div>
    <div class="cancelbutton">
      <form action="../protected/sqmembers.php">
        <input type="submit" value="Cancel">
      </form>
    </div>
  </body>
</html>
