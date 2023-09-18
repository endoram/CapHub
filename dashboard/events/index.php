<?php
include '../includes/header.php';    // Standard Header include   
include '../includes/config_m.php';  // Include the database configuration file
include 'event_helpers.php';         // Helper functions used by event related scripts

$events = getEvents();
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
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <a class="navbar-brand" href="#">Event Tracker</a>
    </nav>
    <div class="container mt-5">
        <h1>Events</h1>
        <div class="row">
            <?php foreach ($events as $event) : ?>
                <!-- Display a list of events with links to event.php?id=EVENT_ID -->
                <div class="col-md-4">
                    <div class="card mb-4">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo $event['event_name']; ?><p class="card-text">Location: <?php echo $event['event_location']; ?></p></h5>
                            <p class="card-text"><?php echo $event['event_type'];?></p>
                            <p class="card-text">Start Date: <?php echo $event['start_date']." ".$event['start_time']; ?></p>
                            <p class="card-text">End Date: <?php echo $event['end_date']." ".$event['end_time']; ?></p>
                            <a href="event.php?id=<?php echo $event['event_id']; ?>" class="btn btn-primary">View Event</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <a href="create_event.php" class="btn btn-success">Create Event</a>
    </div>
    <!-- The code block is including three JavaScript files: jQuery, Popper.js, and Bootstrap. --> 
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>