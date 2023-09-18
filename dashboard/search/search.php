<?php
/* The code you provided is a PHP script that performs a database query based on user input and returns
the results as JSON. */
session_start();
require "../includes/control_access.php";

// Include your database connection code here
include '../includes/config_m.php';

$searchInput = $_GET['searchInput'];
$filterColumn = $_GET['filterColumn'];

// Construct the SQL query based on user input
$sql = "SELECT * FROM meeting_nights WHERE $filterColumn LIKE '%$searchInput%'";

// Execute the query and fetch results
$result = mysqli_query($conn, $sql);

$data = array(); // Initialize an array to store the data

while ($row = mysqli_fetch_assoc($result)) {
    $data[] = $row;
}

// Close the database connection
mysqli_close($conn);

// Return the data as JSON
echo json_encode($data);
exit(); // Terminate the script
?>
