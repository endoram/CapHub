<?php
// Include the database configuration file
include '../includes/header.php';
include '../includes/config_m.php';
include 'event_helpers.php';

// Handle participant sign-in/sign-out actions
if (isset($_POST['rmuser0'])) {
    $capid = $_POST['capidrm'];

     if ($capid == "" or !is_numeric($capid)) {$errorMsg = "Invalid Cap ID";}    //Validate all numbers
      else {
        require "../includes/config_m.php";

        $query = "SELECT * FROM sq_members WHERE cap_id=" . $capid;   //Validate number is a CAPID in the database
        $result = $conn->query($query);

        if ($result->num_rows > 0) {
          while($row = $result->fetch_assoc()) {  //Get first and last name from database
            $db_capid = $row['cap_id'];
            $name = $row['first_name'];
            $name0 = $row['last_name'];
            $name = $name . ' ' . $name0;
            $membertype = $row['member_type'];
            $FQSN = $row['FQSN'];
          }
          if($db_capid == $capid) {
            $date = date("Y/m/d");
            require "../includes/config_m.php";
            $query = "SELECT * FROM participants WHERE cap_id=" . $db_capid . " AND event_id='" . $_SESSION['id'] . "'";
            $result = $conn->query($query);

            if ($result->num_rows > 0) {    //If already signed in for that day sign out
              $message = $name . " signed out";
              $date = date("Y/m/d");
              $time = date("H:i:s");
              $query = "UPDATE participants SET time_out='" . $time . "', participant_status='Signed Out' WHERE cap_id=" . $capid . " AND event_id='" . $_SESSION['id'] . "'";
              $conn->query($query);
            }
            else {      //If not sign you in
              $message = "Thank you " . $name . " for signing into the event!";
              $date = date("Y/m/d");
              $time = date("H:i:s");
              $capid = $_POST['capidrm'];
              $partStatus = "Signed In";

            //  if($FQSN == $_SESSION['FQSN']) {
            //    echo'<script>alert("Hey $name are you visisting $_SESSION["FQSN"]?");</script>';
            //  }
              $query = "INSERT INTO participants (event_id, cap_id, participant_name, participant_status, time_in, member_type, FQSN) VALUES ('" . $_SESSION['id'] . "', '" . $capid . "', '" .  $name . "', '" . $partStatus . "', '" . $time . "','" . $membertype . "','" . $FQSN . "')";
               $conn->query($query);
              }
            }
          }
        else {$errorMsg = "CAP ID not in database Please talk to the cadet admin NCO";}
        $conn->close();
    }
}

if (isset($_GET['radio_id']) && isset($_GET['cap_id'])) {
  echo (checkoutRadio($_GET['radio_id'], $_GET['cap_id']));
}
if (isset($_GET['sign_in']) && isset($_GET['cap_id'])) {
  echo (checkinRadio($_GET['sign_in'], $_GET['cap_id']));
}


// Export participant data as CSV
if (isset($_GET['export']) && $_GET['export'] == 'csv') {
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="event_participants.csv"');

    $output = fopen('php://output', 'w');
    fputcsv($output, array('Participant Name', 'Status'));

    $eventId = $_GET['id'];
    $participants = getEventParticipants($eventId);

    foreach ($participants as $participant) {
        fputcsv($output, array($participant['participant_name'], $participant['participant_status']));
    }

    fclose($output);
}

if (isset($_GET['id'])) {
    $_SESSION['id'] = $_GET['id'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Details</title>
    <!-- Include Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
  <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <a class="navbar-brand" href="#">Event Tracker</a>
    </nav>
    <div class="row p-3">
        <div class="container-fluid p-1">
            <div class="leftside">
              <div class="sqmenubar">
                <ul>
                  <li><a href="?id=<?echo $_SESSION['id'];?>">Participants</a></li><br><br>
                  <li><a href="?signIn=<?echo $_SESSION['id'];?>">Sign-In/Out</a></li>
                  <li><a href="event_comms.php?id=<?echo $_SESSION['id'];?>">Comms</a></li>
                  <li><a href="?export=<?echo $_SESSION['id'];?>">Export Participants</a></li>
                </ul>
              </div>
            </div>
            <div class="middle p-2">
                <?php
                if (isset($_GET['id'])) {
                    $eventId = $_GET['id'];
                    $eventDetails = getEventDetails($eventId);
                    if ($eventDetails) {
                        // Display event details
                        echo "<h1>{$eventDetails['event_name']}</h1>";
                        echo "<p>Location: {$eventDetails['event_location']}</p>";
                        echo "<p>Start Time: {$eventDetails['start_date']} {$eventDetails['start_time']}</p>";
                        echo "<p>End Time: {$eventDetails['end_date']} {$eventDetails['start_time']}</p>";
                        $count = 0;

                        // Display event participants
                        $participants = getEventParticipants($eventId);
                        if ($participants) {
                            echo "<h2>Participants</h2>";
                            echo "<table class='table'>";
                            echo "<thead>";
                            echo "<tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Radio</th>
                                    <th>Status</th>
                                    <th>Time In</th>
                                    <th>Time Out</th>
                                    <th>Squadron</th>
                                    <th>Member Type</th>
                                 </tr>";
                            echo "</thead>";
                            echo "<tbody>";
                            foreach ($participants as $participant) {
                                ++$count;
                                echo "<tr>";
                                echo "<td>$count</td>";
                                echo "<td><a href=event_comms.php?cap_id={$participant['cap_id']}>{$participant['participant_name']}</a></td>";
                                echo "<td>{$participant['radio']}</td>";
                                echo "<td>{$participant['participant_status']}</td>";
                                echo "<td>{$participant['time_in']}</td>";
                                echo "<td>{$participant['time_out']}</td>";
                                echo "<td>{$participant['FQSN']}</td>";
                                echo "<td>{$participant['member_type']}</td>";
                                echo "</tr>";
                            }
                            echo "</tbody>";
                            echo "</table>";
                        }
                    } else {
                        echo "<p>Event not found.</p>";
                    }
                }

                echo "<div class='meetingform'>";
                if (isset($_GET['signIn'])) {
                    $eventId = $_GET['signIn'];
                  if(isset($errorMsg) && $errorMsg) {
                    echo "<p style=\"color: red;\">*",htmlspecialchars($errorMsg),"</p>\n\n";
                  }
                  if(isset($message) && $message) {
                    echo "<p style=\"color: green;\">*",htmlspecialchars($message),"</p>\n\n";
                  }
                  if(isset($_SESSION['message']) && $_SESSION['message']) {
                    echo "<p style=\"color: green;\">*",htmlspecialchars($_SESSION['message']),"</p>\n\n";
                    unset($_SESSION['message']);
                  }
                    ?>
                        <label>Enter CAP ID:</label>
                      <form method='POST'>
                        <input type="text" name="capidrm" autofocus> <!--Getting user's CAPID-->
                        <br>
                        <input type="submit" value="Submit" name="rmuser0">
                      </form>
                    </div>
                      <?php
                      $participants = getEventParticipants($_SESSION['id']);
                        if ($participants) {
                            echo "<h2>Participants</h2>";
                            echo "<table class='table'>";
                            echo "<thead>";
                            echo "<tr><th>ID</th><th>Participant Name</th><th>Status</th></tr>";
                            echo "</thead>";
                            echo "<tbody>";
                            $count = 0;
                            foreach ($participants as $participant) {
                                ++$count;
                                echo "<tr>";
                                echo "<td>$count</td>";
                                echo "<td>{$participant['participant_name']}</td>";
                                echo "<td>{$participant['participant_status']}</td>";
                                echo "</tr>";
                            }
                            echo "</tbody>";
                            echo "</table>";
                        }
                }
                ?>
            </div>
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
