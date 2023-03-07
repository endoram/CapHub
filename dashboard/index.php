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
unset($_SESSION["FQSN"]);
unset($_SESSION["squad"]);

##var_dump($_SESSION);
unset($_SESSION["cap_id"]);
unset($_SESSION["query_idea"]);
unset($_SESSION["table"]);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $capid = $_POST['capid'];
  $password = $_POST['password'];

  require "includes/config_m.php";

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
          $db_passSHA = $row['user_passSHA'];
          $db_priv = $row['privlage_level'];
          $db_fname = $row['first_name'];
          $db_lname = $row['last_name'];
          $db_FQSN = $row['FQSN'];
          $hash = $row['hash'];
      }
      if ($db_priv >= 1){
        if (password_verify($password, $db_pass)) {
          if($db_passSHA == null) {
            $bytes = random_bytes(20);
            $hash = bin2hex($bytes);
            $pass = $hash . $password;
            $hashedPassSHA = hash('sha256', $pass);

            $query = "UPDATE sq_members SET user_passSHA='$hashedPassSHA', hash='$hash', user_pass='' WHERE cap_id='$capid'";
            $conn->query($query);
          }
          $conn->close();
          $_SESSION['capid'] = $capid;
          $_SESSION['password'] = $password; #Needed For some reason, SESSION reasons.
          $_SESSION['privlv'] = $db_priv;
          $_SESSION['name'] = $db_fname . " " . $db_lname;
          $_SESSION['FQSN'] = $db_FQSN;
          header("Location: protected/main.php");
          exit();
        }
        else {
          $pass = $hash . $password;
          if (hash('sha256', $pass) == $db_passSHA) {
            $conn->close();
            $_SESSION['capid'] = $capid;
            $_SESSION['password'] = $password;
            $_SESSION['privlv'] = $db_priv;
            $_SESSION['name'] = $db_fname . " " . $db_lname;
            $_SESSION['FQSN'] = $db_FQSN;
            header("Location: protected/main.php");
            exit();
          } 
          else {$errorMsg = "Invalid Cap ID or password";}
          $errorMsg = "Invalid Cap ID or password";
        }
        $conn->close();
      }
      else {$errorMsg = "Invalid Cap ID or password";}
    }
    else {$errorMsg = "Invalid Cap ID or password";
      $conn->close();
    }
  }
}
?>


<!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-5ZHX8TXJRD"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-5ZHX8TXJRD');
</script>


<script src="https://apis.google.com/js/platform.js" async defer></script>
<style>
  h1, h2 {
    align-self: center;
    color: #00FF00;
  }

  body{
  }

  .loginform {
    text-align: center;
    -webkit-transform: translateY(100%);
    transform: translateY(100%);
    margin-top: 200px;
  }
</style>

<html>
  <head>
    <title>CAPhub Login</title>
    <meta name="google-signin-client_id" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <img src="images/bannerThree.png">
  </head>
  <body>
    <div class="loginform">
      <div class="g-signin2" data-onsuccess="onSignIn"></div>
      <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" accept-charset="UTF-8">
        <?php
        if(isset($errorMsg) && $errorMsg) {
          echo "<p style=\"color: red;\">*",htmlspecialchars($errorMsg),"</p>\n\n";
        }?>
        <label for="EMIAL">CAP ID:</label> <input type="text" name="capid" align="right" value="<?PHP if(isset($_POST['capid'])) echo htmlspecialchars($_POST['capid']); ?>"><br>
        <label for="PASSWORD">Password:</label> <input type="password" name="password" align="right" value="<?PHP if(isset($_POST['password'])) echo htmlspecialchars($_POST['password']); ?>"><br><br>
        <input type="submit" value="Login">
      </form>
  </div>
  </body>
</html>
