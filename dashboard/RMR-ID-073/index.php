<?php
require "../header.php";
require "config_m.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $capid = $_POST['capid'];
  $password = $_POST['password'];

  if(!is_numeric($capid)) {
    $errorMsg = "Invalid Cap ID or password";
  }
  else {
    $conn = new mysqli($mysql_host, $mysql_user, $mysql_password, $mysql_database) or die("Database Connection Failed : " . mysql_error());

    $query = "SELECT user_pass FROM sq_members WHERE cap_id=" . $capid;
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
      while($row = $result->fetch_assoc()) {
          $db_pass = $row["password"];
      }
      if (strcmp($db_pas,$password)) {
        header("Location: main.php");
      }
      else {$errorMsg = "Invalid Cap ID or password";}
      $conn->close();
    }
    else {
      echo "<p style=\"color: red;\">*Invalid Cap ID or password</p>\n\n";
        $conn->close();
    }
  }
}
if(isset($errorMsg) && $errorMsg) {
  echo "<p style=\"color: red;\">*",htmlspecialchars($errorMsg),"</p>\n\n";
}

?>

<html>
  <head>
    <link rel="stylesheet" type="text/css" href="css/index.css">
  </head>
  <body>
    <div class="loginform">
      <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" accept-charset="UTF-8">
        <label for="EMIAL">cap ID:</label> <input type="text" name="capid" align="right" value="<?PHP if(isset($_POST['capid'])) echo htmlspecialchars($_POST['capid']); ?>"><br>
        <label for="PASSWORD">Password:</label> <input type="password" name="password" align="right" value="<?PHP if(isset($_POST['password'])) echo htmlspecialchars($_POST['password']); ?>"><br>
        <input type="submit" value="Login">
      </form>
  </div>
  </body>
</html>
