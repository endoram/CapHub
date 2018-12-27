<?php
require "includes/config_m.php";
session_start();

unset($_SESSION["capid"]);
unset($_SESSION["password"]);
unset($_SESSION["privlv"]);
unset($_SESSION["name"]);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $capid = $_POST['capid'];
  $password = $_POST['password'];
  if(!is_numeric($capid)) {
    $errorMsg = "Invalid Cap ID or password";
  }
  else {
    $hash_pass = password_hash($password, PASSWORD_DEFAULT);

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
        if (password_verify($password, $hash_pass)) {
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

<?php
require "../header.php";
?>

<html>
  <head>
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
        <input type="submit" value="Login">
      </form>
  </div>
  </body>
</html>
