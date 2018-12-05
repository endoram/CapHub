<?php
#require "header.php";
#require "config_m.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $firstname = $_POST['firstname'];
  $lastname = $_POST['lastname'];
  $capid = $_POST['capid'];
  $cadetornot = $_POST['cadetornot'];

  if (!is_numeric($capid) or null) {
    $errorMsg = "Invalid Cap ID";
  }
    if (preg_match('/[^A-Za-z]/', $firstname) or null) {
      $errorMsg = "Invalid Firstname";
    }
      if (preg_match('/[^A-Za-z]/', $lastname)) {
        $errorMsg = "Invalid lastname";
      }
        if ($cadetornot == "senior" or "cadet"){
          adduser($capid, $firstname, $lastname, $capid);
        }
        else{$errorMsg = "Invalid cadet or senior option";}
}

function adduser() {
  require "config_m.php";

  $query = "INSERT INTO sq_members (cap_id, first_name, last_name )";
  $result = $conn->query($query);

  echo "Added Member";

  #Also add check for null
  #Might need a commit function here
  $conn->close();
}
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
  </body>
</html>
