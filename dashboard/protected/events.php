<?php
require "../includes/header.php";

if (isset($_POST['sent'])){
  if ($_POST['sent'] = "addevent") {
    $query = "INSERT INTO events (event_date, event_name, event_unique) VALUES ('$_POST[event_date]', '$_POST[event_name]', 1)";
    require '../includes/config_m.php';
    $conn->query($query);

    $query = "SELECT ID FROM events WHERE event_date='$_POST[event_date]'";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
      while($row = $result->fetch_assoc()) {
        $eventid = $row["ID"];
      }
      $query = "UPDATE events SET event_id=$eventid WHERE ID=$eventid";
      $conn->query($query);
    }
    $conn->close();
  }
}


if(isset($_GET['add_event'])){
  echo '<form method="post" action="events.php" class="form-container">';

  echo '<label for="input"><b>Event Name:</b></label>';
  echo '<input list="input" name="event_name" required><br>';

  echo '<label for="input"><b>Event Date:</b></label>';
  echo '<input type="date" name="event_date" required><br>';

  echo '<button type="submit" value="addevent" name="sent" class="btn">Submit</button>';
  echo '<button type="button" class="btn cancel" onclick="closeForm()">Close</button>';
  echo '</form>';
}
?>

<!--Script to handle opeing and closing of search box-->
<script>
function openForm() {
    document.getElementById("myForm").style.display = "block";
}

function closeForm() {
    document.getElementById("myForm").style.display = "none";
}
</script>


<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="../libs/calendar/datepicker.min.css">
    <title>Squadron Events</title>
  </head>
  <body>
    <div class="row">
      <div class="leftside">
        <div class="sqmenubar">
          <ul>
            <li><a href="?add_event">Add Event</a><li>
            <li><a href="?update_events">Update Event</a></li>
            <li><a href="?remove_events">Remove Event</a><li>
          </ul>
        </div>
      </div>
      <div class="middle">
        <div class="radiotable">
          <br>
          <?php
            $query = "SELECT * FROM events WHERE event_unique=1";
            require "../includes/config_m.php";
            $result = $conn->query($query);
            if ($result->num_rows > 0) {
              echo '
              <br>
                <table>
                <colgroup>
                  <col span="6" style="background-color:lightgrey">
                </colgroup>
                  <tr>
                    <th>Event ID</th>
                    <th>Event</th>
                    <th>Date</th>
                  </tr>
              ';
              while($row = $result->fetch_assoc()) {
                  echo "<tr>
                  <td>" . $row["event_id"] . "</td>
                  <td><a href='event_handle.php?eventid=" . $row["event_id"] . "'>" . $row["event_name"] . "</a></td>
                  <td>" . $row["event_date"] . "</td>
                  </tr>";
              }
            }
            else {
            $conn->close();
            }
            echo "</table>";
          ?>
        </div>
      </div>
  </body>
</html>
