<?php
session_start();
header('Content-Type: text/csv; charset=utf-8');
#Make title of csv date that was queryed
header('Content-Disposition: attachment; filename=data.csv');

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
