<?php
  require "../includes/header.php";
?>

<html>
  <head>
    <title>Coms CAPhub</title>
    <link rel="stylesheet" type="text/css" href="../protected/style.css">
  </head>
  </body>
    <div class="row">
      <div class="leftside">
    <!--    <div class="sqmenubar">
          <ul>
            <li><a href="?addradio">Add Equipment</a><li>
            <li><a href="?removeradio">Remove Equipment</a><li>
            <li><a href="?checkout">Check Out Equipment</a><li>
            <li><a href="?checkin">Check In Equipment</a><li>
            <li><a href="?changestatus">Change Equipment Status</a><li>
            <li><a href="?comms_log">Comm Log</a><li> 
          </ul>
        </div>-->
      </div>
      <?php
        if(isset($errorMsg) && $errorMsg) {
          echo "<p style=\"color: red;\">*",htmlspecialchars($errorMsg),"</p>\n\n";
        }
      ?>
      <div class="middle">
        <div class="radiotable">
          <?php
            require "../includes/config_m.php";
            $query = "SELECT * FROM comms_log ORDER BY id DESC LIMIT 100";
            $result = $conn->query($query);
            if ($result->num_rows > 0) {
              echo '
                <table>
                  <colgroup>
                    <col span="8" style="background-color:lightgrey">
                  </colgroup>
                  <tr>
                    <th>FQSN</th>
                    <th>Updated By</th>
                    <th>Radio ID</th>
                    <th>Status</th>
                    <th>In/Out</th>
                    <th>Name</th>
                    <th>Date</th>
                    <th>Time</th>
                  </tr>
              ';
              while($row = $result->fetch_assoc()) {
                echo "<tr>
                <td>" . $row["FQSN"] . "</td>
                <td>" . $row["updated_by"] . "</td>
                <td>" . $row["radio_id"] . "</td>
                <td>" . $row["status"] . "</td>
                <td>" . $row["in_out"] . "</td>
                <td>" . $row["name"] . "</td>
                <td>" . $row["date"] . "</td>
                <td>" . $row["time"] . "</td>
                </tr>";
              }
            }
            else {
            $conn->close();
            }?>
        </div>
      </div>
    </div>
  </body>
  <div class="navbar fixed-bottom">
    <div class="container-fluid p-0">
    <?php
      require "../includes/footer.php";
    ?>
    </div>
  </div>
</html>