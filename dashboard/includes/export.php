<?php
session_start();
header('Content-Type: text/csv; charset=utf-8');
#Make title of csv date that was queryed
require 'config_m.php';
  $_SESSION['table'] == 5;

  $query = "SELECT time_zone FROM squads WHERE FQSN='" . $_SESSION['FQSN'] . "'";
  $result = $conn->query($query);
  if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
      date_default_timezone_set($row['time_zone']);
    }
  }
  else {
    echo "<script>alert('There has been an issue with the timezone. Please contact the dev team.');</script>";
    $conn->close();
  }
  $currDate = date("y/m/d");
header('Content-Disposition: attachment; filename=' . $currDate . '.csv');

$output = fopen('php://output', 'w');

// output the column headings
fputcsv($output, $_SESSION['query_values']);

if(isset($_SESSION['query_idea'])) {
  $query = $_SESSION['query_idea'];
  unset($_SESSION['query_idea']);
}

require "../includes/config_m.php";
$result = $conn->query($query);

// loop over the rows, outputting them
while($row = $result->fetch_assoc()) {
	fputcsv($output, $row);
 # fputcsv($output, $row["date"]);
 # fputcsv($output, $row["cap_id"]);			#fputcsv REQUIRES an ARRAY - Will come back
 # fputcsv($output, $row["name"]);
 # fputcsv($output, $row["time_in"]);
 # fputcsv($output, $row["time_out"]);
 # fputcsv($output, $row["member_type"]);
}
unset($_SESSION['query_idea']);
unset($_SESSION['query_values']);
exit();
?>
