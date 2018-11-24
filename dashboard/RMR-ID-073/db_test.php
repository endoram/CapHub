<?php
require "../header.php";
require "config_m.php";

//connect to database
$conn = new mysqli($mysql_host, $mysql_user, $mysql_password, $mysql_database) or die("Database Connection Failed : " . mysql_error());

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$query = "SELECT * FROM sq_members";
$query_product = "SELECT * FROM users";

//Execute the query
$result = $conn->query($query_product);


if ($result->num_rows > 0) {
  echo "<table border='1'>";
  echo "<tr> <th>User ID</th> <th>First Name</th> <th>Last Name</th> </tr>";

  while($row = $result->fetch_assoc()) {
      echo "<tr><td>".$row["cap_id"]."</td> <td>".$row["first_name"]."</td><td>".$row["last_name"]."</td> </tr>";
  }
  echo "</table>";
  $conn->close();
}
else {
    echo "0 results";
    $conn->close();
}

?>


<html>
  <body>
    <a href="adduser/form.php" class="button">Add user</a>
    <a href="db_test.php" class="button">Reload</a>
  </body>
</html>
