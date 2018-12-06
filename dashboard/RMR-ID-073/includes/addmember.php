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
    $errorMsg = "Invalid cadet or senior optionasdfsadfad";
    $x = 1;
  }
  if (!in_array($cadetornot, array('cadet','senior', 'Cadet', 'Senior'), true )) {
    $errorMsg = "Invalid cadet or senior option";
    $x = 1;
  }
  if ($x == 0) {adduser($firstname, $lastname, $capid, $cadetornot);}
}

function adduser($firstname, $lastname, $capid, $cadetornot) {
  require "config_m.php";

  $query = "INSERT INTO sq_members (cap_id, first_name, last_name, cadet_senior) VALUES (" . $capid . ",'" . $firstname . "', '" . $lastname . "', '" . $cadetornot . "')";
  $conn->query($query);
  $conn->close();
  header("Location: ../protected/sqmembers.php");
}

require "header.php";
require "config_m.php";
?>


<html>
  <head>
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
    <form action="../protected/main.php">
      <input type="submit" value="Cancel" />
    </form>
  </body>
</html>
