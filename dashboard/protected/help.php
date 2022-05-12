<?php
require "../includes/header.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $firstname = $_POST['firstname'];
  $lastname = $_POST['lastname'];
  $capid = $_POST['capid'];
  $issue = $_POST['issue'];


  require "../includes/mysql_config.php";
  $conn = new mysqli($mysql_host2, $mysql_user2, $mysql_password2, $mysql_database2) or die("Database Connection Failed : " . mysql_error());
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  $query = "INSERT INTO issues (firstname, lastname, capid, issue) VALUES ('$firstname', '$lastname', '$capid', '$issue')";
  #echo $query;
  $conn->query($query);
  $conn->close();
}
?>

<html>
  <head>
    <title>CapHub MainPage</title>
  </head>
  <body>
    <center>
    <h3>If you have any questions or would like to request features</h3>
    <br>
    <h3>Be sure to send the creators an email at:</h3>
    <br>
    <p>caphub.org@gmail.com</p><br>
    <h2>Report A Bug by Emailing:</h2><br>
    <p>caphub.org@gmail.com</p><br>
  </center>
  </body>
</html>


<!--

<html>
  <head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../protected/style.css">
    <title>CapHub Help Page</title>
  </head>
  <body>
    <div class="column">
      <div class="addmemberform">
        <form method="post" action="<?php# echo htmlspecialchars($_SERVER['PHP_SELF']);?>" accept-charset="UTF-8">
          <?php
        #  if(isset($errorMsg) && $errorMsg) {
        #    echo "<p style=\"color: red;\">*",htmlspecialchars($errorMsg),"</p>\n\n";
        #  }?>
          <label for="firstname">First name:</label> <input type="text" name="firstname" align="right" value="<?PHP #if(isset($_POST['capid'])) echo htmlspecialchars($_POST['firstname']); ?>" required><br>
          <label for="lastname">Last name:</label> <input type="text" name="lastname" align="right" value="<?PHP #if(isset($_POST['capid'])) echo htmlspecialchars($_POST['lastname']); ?>" required><br>
          <label for="capid">CAP ID:</label> <input type="text" id="capid" name="capid" align="right" title="Must be a proper CAPID" pattern="[0-9].{5,}" value="<?PHP if(isset($_POST['capid'])) echo htmlspecialchars($_POST['capid']); ?>" required><br>
          <label for="issue">Issue:</label><br><textarea type="text" id="issue" name="issue" align="right" required></textarea><br>
          <input type="submit" value="Submit">
        </form>
    I really should only need a CAPID for this. I can query for more infomration when I send email...or put into database
        <div class="cancelbutton">
          <form action="../protected/sqmembers.php">
            <input type="submit" value="Cancel">
          </form>
        </div>
      </div>
    </div>
  </body>
</html>

-->
