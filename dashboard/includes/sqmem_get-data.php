<?php
if (session_status() == PHP_SESSION_NONE) {session_start();}
require "control_access.php";

if (isset($_GET['retired'])) {
  require 'config_m.php';
  $data = $_GET['retired'];
  $_SESSION['table'] = 1;
  $query = "UPDATE sq_members SET retire=1 where cap_id in ($data)";
  $result = $conn->query($query);
  $conn->close();
}

if (isset($_GET['myData'])) {
  $mydata = $_GET['myData'];
  $logfile = "../squadrons/" . $_SESSION['something'] . "/pt_log.txt";

  file_put_contents($logfile, "");
  file_put_contents($logfile, $mydata, FILE_APPEND);

  if (isset($_GET['stuff']) == 1) {
    $file = fopen($logfile, "r") or die("Unable to open file");
    $contents = fgets($file);
    fclose($file);

    $search = array("[", "]", ":", 'age', "cap_id", "name", "push_ups", "sit_ups", "mile_run", "pacer_test", "sit_reach", ",,", ",,,", ",,,,", ",,,,,", ",,,,,,", ",,,,,,,");
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


    $query = "INSERT INTO physical_testing (name, cap_id, age, push_ups, sit_ups, mile_run, pacer_test, sit_reach) VALUES ($contents)";
    $query = $query . ";";
    echo $query;
    require 'config_m.php';
    $conn->query($query);
    $conn->close();
  }
  else {$_SESSION['table'] == 5;}
}

if (isset($_GET['recover'])) {
  $logfile = "../squadrons/" . $_SESSION['something'] . "/pt_log.txt";
  echo file_get_contents($logfile);
}

if ($_SESSION['table'] == 0) {
  $query = "SELECT * FROM sq_members WHERE hide=0 and retire=0";
  queryit($query);
}

if (isset($_GET['stuffmore']) == 1) {
  require 'config_m.php';
  $_SESSION['table'] == 5;

  date_default_timezone_set("America/Denver");
  $date = date("Y/m/d");

  $query = "SELECT name, cap_id FROM meeting_nights WHERE member_type='cadet' AND date='$date'";
  $result = $conn->query($query);
  $conn->close();$row1 = "";

  if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
      $row1 = '{"name":"' . $row['name'] . '", "cap_id":"' . $row['cap_id'] . '", "age":" ", "push_ups":" ", "sit_ups":" ", "mile_run":" ", "pacer_test":" ", "sit_reach":" "}' . $row1;
    }
    $row1 = str_replace("}{", '},{', $row1);
    $row1 = "[" . $row1 . "]";
    echo $row1;
  }
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
