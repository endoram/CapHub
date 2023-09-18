<?php
session_start();
require "../incldues/control_access.php";

// Ensure that the form was submitted
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Retrieve kit name from the form
    $kitName = $_POST["kitName"];

    // Retrieve selected radios from the form as an array
    $selectedRadios = $_POST["selectedRadios"];

    // Retrieve new radios as a comma-separated string and split into an array
    $newRadiosString = $_POST["newRadios"];
    $newRadiosArray = explode(",", $newRadiosString);

    // Remove whitespace from each new radio and trim it
    $newRadiosArray = array_map("trim", $newRadiosArray);

    // Remove any empty values from the new radios array
    $newRadiosArray = array_filter($newRadiosArray);

    // Combine selected radios and new radios into a single array
    $allRadios = array_merge($selectedRadios, $newRadiosArray);

    // Now, you can process the $kitName and $allRadios array as needed
    // For example, you can insert the kit information into your database
    // and associate the radios with the kit.

    // Replace this with your database connection code
    include "../incldues/config_m.php";

    // Insert the kit information into the kits table
    $sql = "INSERT INTO kits (kit_name) VALUES ('$kitName')";
    if ($conn->query($sql) === TRUE) {
        $kitId = $conn->insert_id;

        // Insert each radio into the kit_members table
        foreach ($allRadios as $radioId) {
            $sql = "INSERT INTO kit_members (kit_id, radio_id) VALUES ($kitId, '$radioId')";
            $conn->query($sql);
        }

        echo "Kit added successfully!";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
} else {
    // Handle the case where the form was not submitted
    echo "Form not submitted.";
}
?>

