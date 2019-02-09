<?php
session_start();

if(empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] == "off"){
    $redirect = 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    header('HTTP/1.1 301 Moved Permanently');
    header('Location: ' . $redirect);
    exit();
}


unset($_SESSION["capid"]);
unset($_SESSION["password"]);
unset($_SESSION["privlv"]);
unset($_SESSION["name"]);
unset($_SESSION["something"]);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $squadrons = $_POST['squadron'];
  $capid = $_POST['capid'];
  $password = $_POST['password'];

  $something = $squadrons . "/config_m.php";
  require $something;

  if(!is_numeric($capid)) {
    $errorMsg = "Invalid Cap ID or password";
  }
  elseif ($password == '') {
    $errorMsg = "Invalid Cap ID or password";
  }
  else {
    $query = "SELECT * FROM sq_members WHERE cap_id=$capid";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
      while($row = $result->fetch_assoc()) {
          $db_pass = $row['user_pass'];
          $db_priv = $row['privlage_level'];
          $db_fname = $row['first_name'];
          $db_lname = $row['last_name'];
      }
      if ($db_priv >= 1){
        if (password_verify($password, $db_pass)) {
          $conn->close();
          $_SESSION['capid'] = $capid;
          $_SESSION['password'] = $password;
          $_SESSION['privlv'] = $db_priv;
          $_SESSION['name'] = $db_fname . " " . $db_lname;
          header("Location: protected/main.php");
          exit();
        }
        else {$errorMsg = "Invalid Cap ID or password";}
        $conn->close();
      }
      else {$errorMsg = "Invalid Cap ID or password";}
    }
    else {
      $errorMsg = "Invalid Cap ID or password";
      $conn->close();
    }
  }
}
?>

<html>
  <head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="css/index.css">
  </head>
  <body>
    <div class="loginform">
      <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" accept-charset="UTF-8">
        <?php
        if(isset($errorMsg) && $errorMsg) {
          echo "<p style=\"color: red;\">*",htmlspecialchars($errorMsg),"</p>\n\n";
        }?>
        <label for="EMIAL">CAP ID:</label> <input type="text" name="capid" align="right" value="<?PHP if(isset($_POST['capid'])) echo htmlspecialchars($_POST['capid']); ?>"><br>
        <label for="PASSWORD">Password:</label> <input type="password" name="password" align="right" value="<?PHP if(isset($_POST['password'])) echo htmlspecialchars($_POST['password']); ?>"><br>
        <label for="LOCATIONID">Location:</label>
        <select name="squadron">
          <?php
            require "includes/mysql_config.php";
            $conn = new mysqli($mysql_host2, $mysql_user2, $mysql_password2, $mysql_database2) or die("Database Connection Failed : " . mysql_error());

            if ($conn->connect_error) {
              die("Connection failed: " . $conn->connect_error);
            }

            $query = "SELECT sq_name FROM squads";
            $result = $conn->query($query);

            if ($result->num_rows > 0) {
              while($row = $result->fetch_assoc()) {
                echo "<option value=" . $row['sq_name'] . ">" . $row['sq_name'] . "</option>";
              }
              $conn->close();
            }
            else {
              echo "<script>alert('No Results Found');</script>";
              $conn->close();
            }
          ?>
        </select>
        <br><b></b><br>
        <input type="submit" value="Login">
      </form>
  </div>
  </body>
</html>
