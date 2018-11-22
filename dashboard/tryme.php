<?php
require "mysql_config.php";
?>

<html>
  <head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="/css/index.css">
    <img src="header.jpg">
    <title>CapHub</title>
  </head>
  <body>
    <h2>Please select your squadren:</h2>

    <?php
      $conn = new mysqli($mysql_host2, $mysql_user2, $mysql_password2, $mysql_database2) or die("Database Connection Failed : " . mysql_error());

      $query = "SELECT sq_name FROM squads";
      $result = $conn->query($query);

      if ($result->num_rows > 0) {
        echo "<table border='1'>";
        echo "<tr> <th>Squadren</th> </tr>";

        while($row = $result->fetch_assoc()) {
            echo "<tr><td><a href='https://caphub.org/".$row['sq_name'] . "/'>" . $row['sq_name'] . "</a></td> </tr>";
        }
        echo "</table>";
        $conn->close();
      }
      else {
        echo "<script>alert('No Results Found');</script>";
      //  header("Location:error.php");
          $conn->close();
      }
    ?>
  </body>
</html>
