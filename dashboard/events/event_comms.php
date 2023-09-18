<?php
include '../includes/header.php';

// Include the database configuration file
include '../includes/config_m.php';
include 'event_helpers.php';
include '../includes/helpers.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Tracker - Home</title>
    <!-- Include Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <?$eventDetails = getEventDetails($_SESSION['id']);?>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <a class="navbar-brand" href="#">Event Comms - <?echo $eventDetails['event_name'];?></a>
    </nav>
    <div class="leftside">
          <div class="sqmenubar">
            <ul>
              <li><a href="event.php?id=<?echo $_SESSION['id'];?>">Participants</a></li><br><br>
              <li><a href="event.php?signIn=<?echo $_SESSION['id'];?>">Sign-In/Out</a></li>
            </ul>
          </div>
        </div>
    <div class="container mt-5">
        <?
        if (isset($_GET['cap_id'])) {
            $capid = $_GET['cap_id'];

            if ($capid == "" or !is_numeric($capid)) {$errorMsg = "Invalid Cap ID";}    //Validate all numbers
            else {
                $cadet = arp($capid);
                $query = "SELECT * FROM comms WHERE name='" . $cadet . "'";
                $result = $conn->query($query);

                echo '<div class="container mt-5">';
                echo '<h2>'.$cadet. ' - ' .$capid.'</h2>';
                echo '<hr style="height:2px;border-width:2;color:black;background-color:black">';
                echo '<div class="radiotable">';
                if ($result->num_rows > 0) {
                    echo "<thead>
                        <table class='table table-bordered'>
                        <tr>
                            <th>Radio ID</th>
                            <th>Status</th>
                            <th>Out Date</th>
                            <th>Out Time</th>
                        </tr>
                    </thead>
                    <tbody>";
                    while($row = $result->fetch_assoc()) {
                        echo "
                        <tr>
                            <td><a href=event.php?id=" .$_SESSION['id']."&sign_in=".$row['radio_id']."&cap_id=".$capid.">".$row['radio_id']."</a></td>
                            <td>".$row['status']."</td>
                            <td>".$row['out_date']."</td>
                            <td>".$row['time']."</td>
                        </tr>";
                    }
                    ?>
                    </tbody>
                    </table>
                <?php
                } else {
                    echo "There is nothing assigned to " . $cadet;
                }
                
                ?>

                
                <br> <hr style="height:2px;border-width:2;color:black;background-color:black">
                <?php
                    $table = array(
                      array("SELECT * FROM comms WHERE radio_type='VHF' AND in_out='IN' AND FQSN='" . $_SESSION['FQSN'] . "'", "Equipment In"),
                    );  
                    for ($x = 0; $x <= 0; $x++) {
                      $value = $table[$x][0];
                      require "../includes/config_m.php";
                      $result = $conn->query($value);
                      if ($result->num_rows > 0) {
                        echo "<h4>" . $table[$x] [1] . "</h4>";
                        echo '
                          <table>
                            <colgroup>
                              <col span="7" style="background-color:lightgrey">
                            </colgroup>
                            <tr>
                              <th>Equipment ID</th>
                              <th>Type</th>
                              <th>Status</th>
                              <th>In/Out</th>
                              <th>Date Out</th>
                              <th>Name</th>
                              <th>Description</th>
                            </tr>
                        ';
                        while($row = $result->fetch_assoc()) {
                          echo "<tr>
                          <td><a href=event.php?id=" .$_SESSION['id']."&radio_id=".$row['radio_id']."&cap_id=".$capid.">" . $row["radio_id"] . "</a></td>
                          <td>" . $row["radio_type"] . "</td>";
                        if($row["status"] == "Fully Operational") {echo '<td bgcolor="#00FF00">' . $row["status"] . "</td>";}
                        else {
                          if($row["status"] == "Operational") {echo "<td bgcolor='#FFFF00'>" . $row["status"] . "</td>";}
                          else{
                            if($row["status"] == "Broken") {echo "<td bgcolor='#FF0000'>" . $row["status"] . "</td>";}
                            else {
                              if($row["status"] == "Batteries") {echo "<td bgcolor='#000000'>" . $row["status"] . "</td>";}
                            }
                          }
                        }
                        echo "
                          <td>" . $row["in_out"] . "</td>
                          <td>" . $row["out_date"] . "</td>
                          <td>" . $row["name"] . "</td>
                          <td>" . $row["description"] . "</td>
                          </tr>";
                        }
                      }
                      else {
                      $conn->close();
                      }
                      echo "</table>";
                    }
                    ?>
              
              <?
            }
        }


        if (isset($_GET['id']) && is_numeric($_GET['id'])) {
            require "../includes/config_m.php";
            $query = "SELECT * FROM participants 
                        INNER JOIN comms 
                        ON participants.participant_name = comms.name 
                        WHERE participants.event_id='".$_GET['id']."' AND participants.radio='YES' ";
            $result = $conn->query($query);

            echo '<div class="container mt-5">';
            echo "<h1>Equipment Out:</h1>";
            echo '<hr style="height:2px;border-width:2;color:black;background-color:black">';
            echo '<div class="radiotable">';

            if ($result->num_rows > 0) {
                echo '
                  <table>
                    <colgroup>
                      <col span="7" style="background-color:lightgrey">
                    </colgroup>
                    <tr>
                      <th>Equipment ID</th>
                      <th>Type</th>
                      <th>Status</th>
                      <th>In/Out</th>
                      <th>Date Out</th>
                      <th>Name</th>
                      <th>Description</th>
                    </tr>
                ';
                while($row = $result->fetch_assoc()) {
                    echo "<tr>
                        <td><a href=event.php?id=" .$_SESSION['id']."&radio_id=".$row['radio_id'].">" . $row["radio_id"] . "</a></td>
                        <td>" . $row["radio_type"] . "</td>";
                    if($row["status"] == "Fully Operational") {echo '<td bgcolor="#00FF00">' . $row["status"] . "</td>";}
                    else {
                      if($row["status"] == "Operational") {echo "<td bgcolor='#FFFF00'>" . $row["status"] . "</td>";}
                      else{
                        if($row["status"] == "Broken") {echo "<td bgcolor='#FF0000'>" . $row["status"] . "</td>";}
                        else {
                          if($row["status"] == "Batteries") {echo "<td bgcolor='#000000'>" . $row["status"] . "</td>";}
                        }
                      }
                    }
                    echo "
                      <td>" . $row["in_out"] . "</td>
                      <td>" . $row["out_date"] . "</td>
                      <td>" . $row["name"] . "</td>
                      <td>" . $row["description"] . "</td>
                      </tr>";
                }
              }
              else {
              $conn->close();
              }
              echo "</table>";
        }
        ?>
        </div>
    </div>
</div>
    <!-- Include Bootstrap JS and jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
  <?php
    require "../includes/footer.php";
  ?>
</html>