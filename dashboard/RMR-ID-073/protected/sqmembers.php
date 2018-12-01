<?php
require "../includes/header.php";
require "../includes/config_m.php";
?>

<html>
  <head>
    <title>CapHub Squadron Members</title>
  </head>
  <body>
    <div class="sqtable">
      <table>
        <colgroup>
          <col span="3" style="background-color:lightgrey">
          <col style="background-color:red">
        </colgroup>
        <tr>
          <th>First Name</th>
          <th>Last Name</th>
          <th>CAPID</th>
          <th>Priv</th>
        </tr>
        <?php
          $query = "SELECT * FROM sq_members";
          $result = $conn->query($query);

          if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
              echo "<tr>
              <td>" . $row["first_name"] . "</td>
              <td>" . $row["last_name"] . "</td>
              <td>" . $row["cap_id"] . "</td>
              <td>" . $row["privlage_level"] . "</td>
              </tr>";
            }
          }
          else {
          echo "0 results";
          $conn->close();
          }
          $conn->close();
        ?>
      </table>
  </div>
  </body>
</html>
