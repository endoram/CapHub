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
  <?php
    require "../includes/footer.php";
  ?>
</html>