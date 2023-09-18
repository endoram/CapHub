<?php
session_start();
// Include any necessary configuration and database connection files
require "../includes/config_m.php"; // Adjust the path as needed
require "../includes/control_access.php";

// Define a function for input validation and sanitization
function sanitizeInput($input) {
    require "../includes/config_m.php";
    // Remove leading and trailing whitespace
    $input = trim($input);
    // Prevent SQL injection
    $input = stripslashes($input);
    $input = mysqli_real_escape_string($conn, $input); // Replace $conn with your database connection variable

    // You can add more validation/sanitization here based on your requirements

    return $input;
}

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Initialize variables with sanitized input
    $eventName = sanitizeInput($_POST["eventName"]);
    $eventLocation = sanitizeInput($_POST["eventLocation"]);
    $eventStartDate = sanitizeInput($_POST["eventStartDate"]);
    $eventStartTime = sanitizeInput($_POST["eventStartTime"]);
    $eventEndDate = sanitizeInput($_POST["eventEndDate"]);
    $eventEndTime = sanitizeInput($_POST["eventEndTime"]);
 #   $seniorMembers = sanitizeInput($_POST["seniorMembers"]);
 #   $cadets = sanitizeInput($_POST["cadets"]);
    $eventType = sanitizeInput($_POST["eventType"]);
    echo $eventType;

    $FQSN = $_SESSION['FQSN'];

    // Add additional server-side validation here if needed

    // Insert the sanitized data into the database
    
    $query = "INSERT INTO events (event_name, event_location, start_date, start_time, end_date, end_time, FQSN, event_type) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "ssssssss", $eventName, $eventLocation, $eventStartDate, $eventStartTime, $eventEndDate, $eventEndTime, $FQSN, $eventType);

    if (mysqli_stmt_execute($stmt)) {
        mysqli_stmt_close($stmt);
        mysqli_close($conn);
        // Redirect to a success page or display a success message
        header("Location: index.php"); // Adjust the redirection URL
        exit();
    } else {
        // Handle the database insertion error
        echo "Error: " . mysqli_error($conn);
        mysqli_stmt_close($stmt);
        mysqli_close($conn);
        // You may want to redirect or display an error message
    }
} else {
    // Redirect to an error page or display an error message for invalid form submission
    header("Location: error.php"); // Adjust the redirection URL
    exit();
}
?>
