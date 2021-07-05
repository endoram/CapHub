<?php
session_start();
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=data.csv');

$output = fopen('php://output', 'w');

// output the column headings
fputcsv($output, array('First Name', 'Last Name', 'CAP ID'));

if(isset($_SESSION['query_idea'])) {
  $query = $_SESSION['query_idea'];
  unset($_SESSION['query_idea']);
}

require "../includes/config_m.php";
$result = $conn->query($query);

// loop over the rows, outputting them
while($row = $result->fetch_assoc()) {
  fputcsv($output, $row);
}
unset($_SESSION['query_idea']);
exit()
?>
