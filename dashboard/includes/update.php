<?php
session_start();
require "control_access.php";
require "config_m.php";
$y = 0;
$x = 0;


if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $firstname = $_POST['firstname'];
  $lastname = $_POST['lastname'];
  $capid = $_POST['capid'];
  $cadetornot = $_POST['cadetornot'];
  $priv = $_POST['privlage_level'];
  $password_password = $_POST['password'];
  $cap_ID = $capid;

  $querys = array();
  if (isset($_POST['cap-id'])) {
    if ($capid == "" or !is_numeric($capid)) {
      $errorMsg = "Invalid Cap ID";
    }
    else {
      $querys[] = $query0 = "UPDATE sq_members SET cap_id = $cap_ID WHERE cap_id = " . $_SESSION['cap_id'] ;
    }
  }

  if (isset($_POST['fname'])) {
    if ($firstname == NULL or preg_match('/[^A-Za-z]/', $firstname)) {
      $errorMsg = "Invalid Firstname";
    }
    else {
      $querys[] = $query1 = "UPDATE sq_members SET first_name = '$firstname' WHERE cap_id = " . $_SESSION['cap_id'] ;
    }
  }

  if (isset($_POST['lname'])) {
    if ($lastname == NULL or preg_match('/[^A-Za-z]/', $lastname)) {
      $errorMsg = "Invalid lastname";
    }
    else {
      $querys[] = $query2 = "UPDATE sq_members SET last_name = '$lastname' WHERE cap_id = " . $_SESSION['cap_id'] ;
    }
  }

  if (isset($_POST['cadet'])) {
    if ($cadetornot == ""){
      $errorMsg = "Invalid cadet or senior option";
    }
    if (!in_array($cadetornot, array('cadet','senior', 'Cadet', 'Senior'), true )) {
      $errorMsg = "Invalid cadet or senior option";
    }
    else {
      $querys[] = $query3 = "UPDATE sq_members SET member_type =" . "$cadetornot" . " WHERE cap_id = " . $_SESSION['cap_id'] ;
    }
  }
  if (isset($_POST['priv'])){
    if(isset($_POST['privlage_level'])){
      if ($priv == "" or !in_array($priv, array('0','1', '2', '3'), true )) {
        $errorMsg = "Invalid privlage level";
      }
      else {
        $querys[] = $query4 = "UPDATE sq_members SET privlage_level = $priv WHERE cap_id = " . $_SESSION['cap_id'] ;
      }
    }
  }

  if (isset($_POST['pass'])) {
    if (isset($_POST['password'])){
      if ($password_password == "") {
        $errorMsg = "Invalid password";
      }
      else {
        $bytes = random_bytes(20);
        $hash = bin2hex($bytes);
        $pass = $hash . $password_password;
        $hashedPassSHA = hash('sha256', $pass);
        $querys[] = $query5 = "UPDATE sq_members SET user_passSHA='$hashedPassSHA', hash='$hash' WHERE cap_id='". $_SESSION['cap_id'] . "'";
      }
    }
  }

  foreach ($querys as $value) {
    require "config_m.php";
    $conn->query($value);
    $y = 1;
    if($_SESSION['privlv'] >= 3){
      echo $value;
    }
  }
  if ($y == 1) {
    if ($cap_ID != '') {
      $data = "WHERE cap_id LIKE '" . $cap_ID . "%' && FQSN=" . $_SESSION['FQSN'];
    }
    else {
      $data = "WHERE cap_id LIKE " . $_SESSION['cap_id'];
    }
    queryit($data);
  }
}

function queryit($data) {
  require "config_m.php";
  $query = "SELECT * FROM sq_members " . $data;
  $result = $conn->query($query);

  $today = date("D M j G:i:s T Y");
  $log = $today . ": Added user " . $firstname . " " . $lastname . " By " . $_SESSION['name'];
  $logfile = "../squadrons/" . $_SESSION['something'] . "/log.txt";
  file_put_contents($logfile, $log, FILE_APPEND);

  echo '<div class="sqsearch">
    <br>
    <table>
      <colgroup>
        <col span="3" style="background-color:lightgrey">
        <col style="background-color:red">
      </colgroup>
      <tr>
        <th>First Name</th>
        <th>Last Name</th>
        <th>CAPID</th>
        <th>Priv</th>
      </tr>';

  if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        echo "<tr>
        <td>" . $row["first_name"] . "</td>
        <td>" . $row["last_name"] . "</td>
        <td>" . $row["cap_id"] . "</td>
        <td>" . $row["privlage_level"] . "</td>
        </tr>";
    }
    echo "</table></div></div>";
    $conn->close();
  }
  else {
    echo "<h4 style='color: darkyellow'>No Reults found</h4>";
    $conn->close();
  }
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
  if (isset($_POST['CAPid'])) {
    $CAPid = $_POST['CAPid'];
    $x = 1;

    if (!$CAPid == "" or is_numeric($CAPid)) {
      $data = "WHERE cap_id LIKE '" . $CAPid . "%'";
      $_SESSION['cap_id'] = $CAPid;
      queryit($data);
    }
    else { $errorMsg = "Invaild CAPID";}
  }
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
  if (isset($_POST['submit'])) {
    ?>
    <style>
      .hideme {
        display: none;
      }
    </style>
    <img src="../images/banner.png">
    <div class="addmemberform">
      <p>Select the checkbox next to the item you wish to update.</p>
      <p>Type in the information you want to set to it.</p>
      <p>Finish by clicking "Update Member"</p>
      <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" accept-charset="UTF-8">
        <?php
        if(isset($errorMsg) && $errorMsg) {
          echo "<p style=\"color: red;\">*",htmlspecialchars($errorMsg),"</p>\n\n";
        }?>
        <label for="firstname">First name:</label> <input type="text" name="firstname" align="right" value=<?PHP if(isset($_POST['firstname'])) echo htmlspecialchars($_POST['firstname']); ?>>
        <input type=checkbox value="fname" name="fname"><br>
        <label for="firstname">Last name:</label> <input type="text" name="lastname" align="right" value=<?PHP if(isset($_POST['lastname'])) echo htmlspecialchars($_POST['lastname']); ?>>
        <input type=checkbox value="lname" name="lname"><br>
        <label for="capid">CAP ID:</label> <input type="text" name="capid" align="right" value="<?PHP if(isset($_POST['capid'])) echo htmlspecialchars($_POST['capid']); ?>">
        <input type=checkbox value="cap-id" name="cap-id"><br>
        <?php if($_SESSION['privlv'] >= 2){ ?>
          <label for="privlage_level">Privlage Level:</label> <input type="text" name="privlage_level" align="right" value="<?PHP if(isset($_POST['privlage_level'])) echo htmlspecialchars($_POST['privlage_level']); ?>">
          <input type=checkbox value="priv" name="priv"><br>
          <label for="passsword">Password:</label> <input type="password" name="password" align="right" value="<?PHP if(isset($_POST['password'])) echo htmlspecialchars($_POST['password']); ?>">
          <input type=checkbox value="pass" name="pass"><br>
        <?php } ?>
        <select name="cadetornot">
          <option value="cadet">Cadet</option>
          <option value="senior">Senior</option>
        <select>
        <input type=checkbox value="cadet" name="cadet"><br>
        <input type="submit" value="Update Member">
      </form>
    </div>
    <div class="cancelbutton">
      <form action="../protected/sqmembers.php">
        <input type="submit" value="Cancel">
      </form>
    </div>
<?php
  }
}
?>


<html>
  <head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../protected/style.css">
    <title>CapHub Update Member</title>
  </head>
  <body>
    <div class="hideme">
      <img src="../images/banner.png">
      <?php
        if(isset($errorMsg) && $errorMsg) {
          echo "<p style=\"color: red;\">*",htmlspecialchars($errorMsg),"</p>\n\n";
        }?>
      <?php if ($x == 1){ ?>
          <h3>Is this the user you want?</h3>
            <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" accept-charset="UTF-8">
            <input type="submit" value="Confirmed" name="submit">
          </form>
      <?php } ?>
      <h3>Update Squadron member:</h3>
      <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" accept-charset="UTF-8">
        <label for="CAPid">Enter CAPID:</label> <input type="text" name="CAPid" align="right" value=<?PHP if(isset($_POST['CAPid'])) echo htmlspecialchars($_POST['CAPid']); ?>><br>
        <input type="submit" value="submit">
      </form>
      <div class="cancelbutton">
        <form action="../protected/sqmembers.php">
          <input type="submit" value="cancel">
        </form>
      </div>
    </div>
  </body>
</html>
