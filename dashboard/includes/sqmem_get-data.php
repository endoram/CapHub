<?php
if (session_status() == PHP_SESSION_NONE) {session_start();}
require "control_access.php";

if (isset($_GET['myData'])) {
  $mydata = $_GET['myData'];
  $logfile = "../squadrons/" . $_SESSION['something'] . "/pt_log.txt";

  file_put_contents($logfile, "");
  file_put_contents($logfile, $mydata, FILE_APPEND);

  if (isset($_GET['stuff']) == 1) {
    $file = fopen($logfile, "r") or die("Unable to open file");
    $contents = fgets($file);
    fclose($file);

    $search = array("[", "]", ":", 'age', "name", "push_ups", "sit_ups", "mile_run", "pacer_test", "sit_reach", ",,", ",,,", ",,,,", ",,,,,", ",,,,,,", ",,,,,,,");
    $contents = str_replace($search, "", $contents);

    $search = array("},{");
    $contents = str_replace($search, "),(", $contents);
    $contents = str_replace("{),(", "", $contents);
    $search = array("}", "{");
    $contents = str_replace($search, "", $contents);
    $contents = str_replace(',""', ",", $contents);
    $contents = str_replace('("""', '("', $contents);
    $contents = str_replace('"""', '"', $contents);
    $contents = str_replace("(),", '', $contents);


    $query = "INSERT INTO physical_testing (name, age, push_ups, sit_ups, mile_run, pacer_test, sit_reach) VALUES ($contents)";
    $query = $query . ";";
    echo $query;
    require 'config_m.php';
    $conn->query($query);
    $conn->close();
  }
  else {$_SESSION['table'] == 5;}
}


if ($_SESSION['table'] == 0) {
  $query = "SELECT * FROM sq_members WHERE hide=0";
  queryit($query);
}
if ($_SESSION['table'] == 1) {
  $_SESSION['table'] == 5;
  date_default_timezone_set("America/Denver");
  $date = date("Y/m/d");
  $query = "SELECT name FROM meeting_nights WHERE member_type='cadet' AND date='$date'";
  queryit($query);
}

function queryit($query) {
  require 'config_m.php';
  $result = $conn->query($query);
  $conn->close();

  $rows = array();
  if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
      $rows[] = $row;
    }
  }
  echo json_encode($rows);
}
?>
