<?php
require "mysql_config.php";
?>
<style>
  /* Dropdown Button */
  .dropbtn {
     background-color: #4CAF50;
     color: white;
     padding: 10px;
     font-size: 16px;
     border: none;
  }

  /* The container <div> - needed to position the dropdown content */
  .dropdown {
     position: relative;
     display: inline-block;
  }

  /* Dropdown Content (Hidden by Default) */
  .dropdown-content {
     display: none;
     position: absolute;
     background-color: #f1f1f1;
     min-width: 160px;
     box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
     z-index: 1;
  }

  /* Links inside the dropdown */
  .dropdown-content a {
     color: black;
     padding: 12px 16px;
     text-decoration: none;
     display: block;
  }

  /* Change color of dropdown links on hover */
  .dropdown-content a:hover {background-color: #ddd;}

  /* Show the dropdown menu on hover */
  .dropdown:hover .dropdown-content {display: block;}

  /* Change the background color of the dropdown button when the dropdown content is shown */
  .dropdown:hover .dropbtn {background-color: #3e8e41;}

</style>

<html>
  <head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="/style.css">
    <img src="header.jpg">
    <title>CapHub</title>
  </head>
  <body>
    <h2>Please select your squadron:</h2>

    <div class="dropdown">
        <button class="dropbtn">Squadrons</button>
        <div class="dropdown-content">
            <?php
              $conn = new mysqli($mysql_host2, $mysql_user2, $mysql_password2, $mysql_database2) or die("Database Connection Failed : " . mysql_error());

              if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
              }

              $query = "SELECT sq_name FROM squads";
              $result = $conn->query($query);

              if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                  echo "<a href=https://caphub.org/".$row['sq_name'] . "/'>" . $row['sq_name'] . "</a>";
                }
                $conn->close();
              }
              else {
                echo "<script>alert('No Results Found');</script>";
                $conn->close();
              }
            ?>
        </div>
    </div>
  </body>
</html>
