<?php
session_start();
require "../includes/control_access.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Kit</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2>Add Kit</h2>
        <form id="addKitForm" method="post" action="process_kit.php">
            <div class="form-group">
                <label for="kitName">Kit Name:</label>
                <input type="text" class="form-control" id="kitName" name="kitName" required>
            </div>
            <div class="form-group">
                <label for="selectedRadios">Select Radios:</label>
                <select multiple class="form-control" id="selectedRadios" name="selectedRadios[]">
                    <?php
                    // Replace with your database connection code
                    require "../incldues/config_m.php";

                    // Fetch radios from comms table
                    $sql = "SELECT radio_id, radio_type FROM comms";
		    echo $sql;
		    echo "<option value='test'>".$sql."</option>";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo '<option value="' . $row["radio_id"] . '">' . $row["radio_type"] . ' (' . $row["radio_id"] . ')</option>';
                        }
                    }

                    $conn->close();
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label for="newRadios">Add New Radios (comma-separated):</label>
                <textarea class="form-control" id="newRadios" name="newRadios" rows="3"></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>

    <!-- The code block is including three JavaScript files: jQuery, Popper.js, and Bootstrap. -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

