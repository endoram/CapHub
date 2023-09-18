<?php
/* The code is a PHP script that is used to display event details and equipment information. It
includes various PHP files and database configurations. */
include '../includes/header.php';     // Standard Header include
include '../includes/config_m.php';   // Include the database configuration file
include 'event_helpers.php';          // Helper functions used by event related scripts
include '../includes/helpers.php';    // Regular script helper functions
?>

<!DOCTYPE html>
<html lang="en">
<?/* The code block is responsible for creating the HTML structure of the webpage. It includes the
necessary meta tags for character encoding and viewport settings. It also sets the title of the
webpage to "Event Tracker - Home". */ ?>
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

            /* The above code is a PHP script that performs the following tasks: */
            if ($capid == "" or !is_numeric($capid)) {$errorMsg = "Invalid Cap ID";}    //Validate all numbers
            else {
                $cadet = arp($capid);
                /* The line of code ` = "SELECT * FROM comms WHERE name='" .  . "'";` is
                creating a SQL query to retrieve all columns (`*`) from the "comms" table where the
                "name" column is equal to the value stored in the `` variable. This query is
                used to fetch the communication equipment details for a specific cadet. */
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
                    /* The below code is a PHP script that retrieves data from a database table called
                    "comms" based on certain conditions. It then displays the retrieved data in an
                    HTML table format. */
                      /* The code `SELECT * FROM comms WHERE radio_type='VHF' AND in_out='IN'
                        AND FQSN='" . ['FQSN'] . "'"` is a SQL query that retrieves
                        data from the "comms" table. It selects all columns (`*`) where the
                        "radio_type" column is equal to 'VHF', the "in_out" column is equal to
                        'IN', and the "FQSN" column is equal to the value stored in the
                        `['FQSN']` variable. */
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


        /* The code block is checking if the 'id' parameter is set in the URL and if it is a numeric
        value. If both conditions are true, it includes the 'config_m.php' file, executes a SQL
        query to retrieve data from the 'participants' and 'comms' tables based on the event ID and
        where the 'radio' column is set to 'YES'. It then displays the retrieved data in a table
        format on the webpage. */
        if (isset($_GET['id']) && is_numeric($_GET['id'])) {
            require "../includes/config_m.php";
            /* The query variable is storing a SQL query that retrieves data from the `participants`
            and `comms` tables. It uses an inner join to combine the two tables based on the
            condition that the `participant_name` column in the `participants` table is equal to the
            `name` column in the `comms` table. */
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
    <!-- The code block is including three JavaScript files: jQuery, Popper.js, and Bootstrap. -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
  <?php
    require "../includes/footer.php";   // Require standard footer
  ?>
</html>