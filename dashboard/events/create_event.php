<?php
session_start();
include "../includes/control_access.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Event</title>
    <!-- Include Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <a class="navbar-brand" href="#">Event Tracker</a>
        <!-- Add navigation links if needed -->
    </nav>
    <div class="container mt-5">
        <h1>Create Event</h1>
        <form method="POST" action="process_create_event.php">
            <div class="form-group">
                <label for="eventName">Event Name</label>
                <input type="text" class="form-control" id="eventName" name="eventName" required>
            </div>
            <div class="form-group">
                <label for="eventLocation">Location</label>
                <input type="text" class="form-control" id="eventLocation" name="eventLocation" required>
            </div>
            <div class="form-group">
                <label for="eventStartDate">Start Date</label>
                <input type="date" class="form-control" id="eventStartDate" name="eventStartDate" required>
            </div>
            <div class="form-group">
                <label for="eventStartTime">Start Time</label>
                <input type="time" class="form-control" id="eventStartTime" name="eventStartTime" required>
            </div>
            <div class="form-group">
                <label for="eventEndDate">End Date</label>
                <input type="date" class="form-control" id="eventEndDate" name="eventEndDate" required>
            </div>
            <div class="form-group">
                <label for="eventEndTime">End Time</label>
                <input type="time" class="form-control" id="eventEndTime" name="eventEndTime" required>
            </div>
            <div class="form-group">
                <label for="evetType">Event Type</label>
                <select class="form-control" id="eventType" name="eventType">
                    <option value="Leadership Lab">Leadership Lab</option>
                    <option value="Volunteer">Volunteer</option>
                    <option value="Emergency Service">Emergency Service</option>
                </select>
            </div>
            
            <button type="submit" class="btn btn-primary">Create Event</button>
        </form>
        <br>
        <!-- Go back button -->
        <a href="index.php" class="btn btn-secondary">Go Back</a>
    </div>
    <!-- Include Bootstrap JS and jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

<?php
/*

            <div class="form-group">
                <label for="seniorMembers">Minimum Required Senior Members</label>
                <input type="number" class="form-control" id="seniorMembers" name="seniorMembers" required min="0">
            </div>
            <div class="form-group">
                <label for="cadets">Minimum Required Cadets</label>
                <input type="number" class="form-control" id="cadets" name="cadets" required min="0">
            </div>
*/
?>