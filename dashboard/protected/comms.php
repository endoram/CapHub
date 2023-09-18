<?php
require "../includes/header.php";

function executeQuery($query) {
    require "../includes/config_m.php";
    $result = $conn->query($query);
    $conn->close();
    return $result;
}

if (isset($_POST['sent'])) {
    $action = $_POST['sent'];
    $radio_id = $_POST['input'];
    $date = date("Y-m-d");
    $time = date('H:i:s');

    switch ($action) {
        case "Add Equipment ID:":
            $radio_type = $_POST['radio_type'];
            $description = $_POST['description'];
            $query = "SELECT * FROM comms WHERE radio_id='$radio_id' && FQSN='" . $_SESSION['FQSN'] . "'";
            $result = executeQuery($query);

            if ($result->num_rows > 0) {
                $errorMsg = "A radio with that ID has already been added";
            } else {
                $query = "INSERT INTO comms (radio_id, radio_type, in_out, status, description, FQSN) VALUES ('$radio_id', '$radio_type', 'IN', 'Fully Operational', '$description', '" . $_SESSION['FQSN'] . "')";
                $query1 = "INSERT INTO comms_log (`FQSN`, `updated_by`, `radio_id`, `status`, `date`, `time`) VALUES ('" . $_SESSION['FQSN'] . "','" . $_SESSION["capid"] . "', '$radio_id', 'ADDED ITEM', '$date', '$time')";

                executeQuery($query);
                executeQuery($query1);
            }
            break;

        case "Remove Equipment ID:":
            $query = "SELECT * FROM comms WHERE radio_id='$radio_id' && FQSN='" . $_SESSION['FQSN'] . "'";
            $result = executeQuery($query);

            if ($result->num_rows > 0) {
                $query = "DELETE FROM comms WHERE radio_id='$radio_id' && FQSN='" . $_SESSION['FQSN'] . "'";
                $query1 = "INSERT INTO comms_log (`FQSN`, `updated_by`, `radio_id`, `status`, `date`, `time`) VALUES ('" . $_SESSION['FQSN'] . "','" . $_SESSION["capid"] . "', '$radio_id', 'DELETED ITEM', '$date', '$time')";

                executeQuery($query);
                executeQuery($query1);
                echo "Removed radio: $radio_id";
            } else {
                $errorMsg = "No radio has that ID";
            }
            break;

        case "Check Out Equipment ID:":
            $cap_id = $_POST['capid'];
            $query = "SELECT * FROM sq_members WHERE cap_id='$cap_id'";
            $result = executeQuery($query);

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $firstname = $row['first_name'];
                $lastname = $row['last_name'];
                $name = $firstname . " " . $lastname;
            }

            $query = "UPDATE comms SET in_out='OUT', name='$name', out_date='$date' WHERE radio_id='$radio_id' && FQSN='" . $_SESSION['FQSN'] . "'";
            $query1 = "INSERT INTO comms_log (`FQSN`, `updated_by`, `radio_id`, `name`, `in_out`, `date`, `time`) VALUES ('" . $_SESSION['FQSN'] . "','" . $_SESSION["capid"] . "', '$radio_id', '$name', 'OUT', '$date', '$time')";

            executeQuery($query);
            executeQuery($query1);
            break;

        case "Check In Equipment ID:":
            $query = "UPDATE comms SET in_out='IN', name='' WHERE radio_id='$radio_id' && FQSN='" . $_SESSION['FQSN'] . "'";
            $query1 = "INSERT INTO comms_log (`FQSN`, `updated_by`, `radio_id`, `in_out`, `date`, `time`) VALUES ('" . $_SESSION['FQSN'] . "','" . $_SESSION["capid"] . "', '$radio_id', 'IN', '$date', '$time')";

            executeQuery($query);
            executeQuery($query1);
            break;

        case "Equipment ID: ":
            $whatsbroken = $_POST["whatbroken"];
            $status = $_POST['change_status'];

            $query = "UPDATE comms SET status='$status' WHERE radio_id='$radio_id' && FQSN='" . $_SESSION['FQSN'] . "'";
            $query1 = "INSERT INTO comms_log (`FQSN`, `updated_by`, `radio_id`, `status`, `date`, `time`) VALUES ('" . $_SESSION['FQSN'] . "','" . $_SESSION["capid"] . "', '$radio_id', '$status', '$date', '$time')";

            executeQuery($query);
            executeQuery($query1);
            break;

	case "Add Kit:":
            $kit_name = $_POST['kit_name'];
            $radio_ids = $_POST['kit_members'];

            // Insert kit information into the database
            $query = "INSERT INTO kits (kit_name) VALUES ('$kit_name')";
            executeQuery($query);

            // Get the newly inserted kit ID
            $kit_id = $conn->insert_id;

            // Insert kit members into the kit_members table
            foreach ($radio_ids as $radio_id) {
                $query = "INSERT INTO kit_members (kit_id, radio_id) VALUES ($kit_id, '$radio_id')";
                executeQuery($query);
            }
            break;

        case "Check Out Kit:":
            $kit_id = $_POST['kit_id'];

            // Update the in_out status for all radios in the kit
            $query = "UPDATE comms SET in_out='OUT', out_date='$date' WHERE radio_id IN (SELECT radio_id FROM kit_members WHERE kit_id=$kit_id)";
            executeQuery($query);

            // Insert a log entry for checking out the kit
            $query = "INSERT INTO comms_log (`FQSN`, `updated_by`, `kit_id`, `in_out`, `date`, `time`) VALUES ('" . $_SESSION['FQSN'] . "','" . $_SESSION["capid"] . "', $kit_id, 'OUT', '$date', '$time')";
            executeQuery($query);
            break;

        case "Check In Kit:":
            $kit_id = $_POST['kit_id'];

            // Update the in_out status for all radios in the kit
            $query = "UPDATE comms SET in_out='IN' WHERE radio_id IN (SELECT radio_id FROM kit_members WHERE kit_id=$kit_id)";
            executeQuery($query);

            // Insert a log entry for checking in the kit
            $query = "INSERT INTO comms_log (`FQSN`, `updated_by`, `kit_id`, `in_out`, `date`, `time`) VALUES ('" . $_SESSION['FQSN'] . "','" . $_SESSION["capid"] . "', $kit_id, 'IN', '$date', '$time')";
            executeQuery($query);
            break;
    }
}

if (isset($_GET['addradio'])) { $data = "Add Equipment ID:"; handleit($data); }
if (isset($_GET['removeradio'])) { $data = "Remove Equipment ID:"; handleit($data); }
if (isset($_GET['checkout'])) { $data = "Check Out Equipment ID:"; handleit($data); }
if (isset($_GET['checkin'])) { $data = "Check In Equipment ID:"; handleit($data); }
if (isset($_GET['changestatus'])) { $data = "Equipment ID: "; handleit($data); }
if (isset($_GET['addkit'])) { $data = "Add Kit:"; handleit($data); }

function handleit($data) {
    echo '<div class="form-popup" id="myForm">';
    echo '<form method="post" action="comms.php" class="form-container">';

    if (isset($_GET['checkout'])) {
        echo '<label for="input"><b>' . $data . '</b></label>';
        $query = "SELECT radio_id FROM comms WHERE in_out='IN' && FQSN='" . $_SESSION['FQSN'] . "'";
        echo '<input list="input" name="input" autofocus>';
        echo '<datalist id="input">';
        $result = executeQuery($query);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<option value='" . $row['radio_id'] . "'>";
            }
        } else {
            echo "<script>alert('All radios checked out!');</script>";
        }
        echo '</datalist>';
        echo '<label for="input"><b>CAP ID:</b></label>';
        echo '<input type="text" name="capid" required>';
    }

    if (isset($_GET['checkin'])) {
        echo '<label for="input"><b>' . $data . '</b></label>';
        $query = "SELECT radio_id FROM comms WHERE in_out='OUT' && FQSN='" . $_SESSION['FQSN'] . "'";
        echo '<input list="input" name="input" autofocus>';
        echo '<datalist id="input">';
        $result = executeQuery($query);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<option value='" . $row['radio_id'] . "'>";
            }
        } else {
            echo "<script>alert('No Radios checked out!');</script>";
        }
        echo '</datalist>';
    }

    if (isset($_GET['removeradio'])) {
        echo '<label for="input"><b>' . $data . '</b></label>';
        $query = "SELECT radio_id FROM comms WHERE FQSN='" . $_SESSION['FQSN'] . "'";
        echo '<input list="input" name="input" autofocus>';
        echo '<datalist id="input">';
        $result = executeQuery($query);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<option value='" . $row['radio_id'] . "'>";
            }
        } else {
            echo "<script>alert('No Results Found');</script>";
        }
        echo '</datalist>';
    }

    if (isset($_GET['addradio'])) {
        echo '<label for="input"><b>' . $data . ' </b></label>';
        echo '<input type="text" name="input" required autofocus>';
        echo ' ';
        echo '<label for="input"><b>Description: </b></label>';
        echo '<input type="text" name="description" required>';
        echo ' ';
        echo '<label><b>Type: </b></label>';
        echo '
      <select name="radio_type">
        <option value="VHF">VHF</option>
        <option value="HF">HF</option>
        <option value="Equipment">Equipment</option>
      </select>
    ';
    }

    if (isset($_GET['changestatus'])) {
        echo '<label for="input"><b>' . $data . '</b></label>';
        $query = "SELECT radio_id FROM comms WHERE FQSN='" . $_SESSION['FQSN'] . "'";
        echo '<input list="input" name="input">';
        echo '<datalist id="input">';
        $result = executeQuery($query);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<option value='" . $row['radio_id'] . "'>";
            }
        } else {
            echo "<script>alert('No Results Found');</script>";
        }
        echo '</datalist>';

        echo '<label for="input"><b>Whats Broken:</b></label>';
        echo '<input type="text" name="whatbroken">';
        echo '
    <select name="change_status">
      <option value="Fully Operational">Fully Operational</option>
      <option value="Operational">Operational</option>
      <option value="Broken">Broken</option>
      <option value="Batteries">Out of Batteries</option>
    </select> ';
    }
    echo '<button type="submit" value="' . $data . '" name="sent" class="btn">Submit</button>';
    echo '<button type="button" class="btn cancel" onclick="closeForm()">Close</button>';
    echo '</form>';
    echo '</div>';

    if (isset($_GET['addkit'])) {
    $data = "Add Kit:";
    echo '<div class="form-popup" id="myForm">';
    echo '<form method="post" action="comms.php" class="form-container">';
    echo '<label for="input"><b>' . $data . '</b></label>';
    echo '<input type="text" name="kit_name" placeholder="Kit Name" required>';
    echo '<label for="input"><b>Radio IDs (comma-separated):</b></label>';
    echo '<input type="text" name="kit_members" placeholder="Radio IDs" required>';
    echo '<button type="submit" value="' . $data . '" name="sent" class="btn">Submit</button>';
    echo '<button type="button" class="btn cancel" onclick="closeForm()">Close</button>';
    echo '</form>';
    echo '</div>';
}

// HTML form for checking out a kit
if (isset($_GET['checkoutkit'])) {
    $data = "Check Out Kit:";
    echo '<div class="form-popup" id="myForm">';
    echo '<form method="post" action="comms.php" class="form-container">';
    echo '<label for="input"><b>' . $data . '</b></label>';
    echo '<input type="text" name="kit_id" placeholder="Kit ID" required>';
    echo '<button type="submit" value="' . $data . '" name="sent" class="btn">Submit</button>';
    echo '<button type="button" class="btn cancel" onclick="closeForm()">Close</button>';
    echo '</form>';
    echo '</div>';
}

// HTML form for checking in a kit
if (isset($_GET['checkinkit'])) {
    $data = "Check In Kit:";
    echo '<div class="form-popup" id="myForm">';
    echo '<form method="post" action="comms.php" class="form-container">';
    echo '<label for="input"><b>' . $data . '</b></label>';
    echo '<input type="text" name="kit_id" placeholder="Kit ID" required>';
    echo '<button type="submit" value="' . $data . '" name="sent" class="btn">Submit</button>';
    echo '<button type="button" class="btn cancel" onclick="closeForm()">Close</button>';
    echo '</form>';
    echo '</div>';
}
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
    <title>Coms CAPhub</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>

<body>
<div class="row p-3">
    <div class="container-fluid p-1">
        <div class="leftside">
            <div class="sqmenubar">
                <ul>
                    <li><a href="?addradio">Add Equipment</a><li>
                    <li><a href="?removeradio">Remove Equipment</a><li>
                    <li><a href="?checkout">Check Out Equipment</a><li>
                    <li><a href="?checkin">Check In Equipment</a><li>
                    <li><a href="?changestatus">Change Equipment Status</a><li>
		    <li><a href="../comms/add_kit.php">Add Kit</a><li>
                    <li><a href="../includes/comm_log.php">Comm Log</a><li>
                </ul>
            </div>
        </div>
        <?php
        if (isset($errorMsg) && $errorMsg) {
            echo "<p style=\"color: red;\">*", htmlspecialchars($errorMsg), "</p>\n\n";
        }
        ?>
        <div class="middle">
            <div class="radiotable">
                <br>
                <?php
                $table = array(
                    array("SELECT * FROM comms WHERE in_out='OUT' && FQSN='" . $_SESSION['FQSN'] . "'", "Equipment Out"),
                    array("SELECT * FROM comms WHERE radio_type='VHF' && FQSN='" . $_SESSION['FQSN'] . "'", "VHF Radios"),
                    array("SELECT * FROM comms WHERE radio_type='HF' && FQSN='" . $_SESSION['FQSN'] . "'", "HF Radios"),
                    array("SELECT * FROM comms WHERE radio_type='Equipment' && FQSN='" . $_SESSION['FQSN'] . "'", "Misc Equipment")
                );

                foreach ($table as $entry) {
                    $query = $entry[0];
                    $result = executeQuery($query);

                    if ($result->num_rows > 0) {
                        echo "<h4>" . $entry[1] . "</h4>";
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
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>
                              <td>" . $row["radio_id"] . "</td>
                              <td>" . $row["radio_type"] . "</td>";
                            if ($row["status"] == "Fully Operational") {
                                echo '<td bgcolor="#00FF00">' . $row["status"] . "</td>";
                            } else {
                                if ($row["status"] == "Operational") {
                                    echo "<td bgcolor='#FFFF00'>" . $row["status"] . "</td>";
                                } else {
                                    if ($row["status"] == "Broken") {
                                        echo "<td bgcolor='#FF0000'>" . $row["status"] . "</td>";
                                    } else {
                                        if ($row["status"] == "Batteries") {
                                            echo "<td bgcolor='#000000'>" . $row["status"] . "</td>";
                                        }
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
                    echo "</table>";
                }
                ?>
            </div>
        </div>
    </div>
</div>
</body>

<div class="container-fluid p-0">
    <?php
    require "../includes/footer.php";
    ?>
</div>
</html>

