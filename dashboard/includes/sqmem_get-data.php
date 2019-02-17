<?php
require 'config_m.php';

if ($_SESSION['table'] == 0) {
  $query = "SELECT * FROM sq_members WHERE hide=0";
}
if ($_SESSION['table'] == 1) {
  $query = "";
}

$result = $conn->query($query);
$conn->close();

$rows = array();
if ($result->num_rows > 0) {
  while($row = $result->fetch_assoc()) {
    $rows[] = $row;
  }
}
echo json_encode($rows);
?>
