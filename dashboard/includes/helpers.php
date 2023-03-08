<?php
//require "control_access.php";


function searhMe() {

}

function timeZone() {
	require '../includes/config_m.php';

	$query = "SELECT time_zone FROM squads WHERE FQSN='" . $_SESSION['FQSN'] . "'";
	$result = $conn->query($query);
	if ($result->num_rows > 0) {
	  while($row = $result->fetch_assoc()) {
	    date_default_timezone_set($row['time_zone']);
	    $time = $row['time_zone'];
	 //   return $time;
	  }
	}
	else {
	  echo "<script>alert('There has been an issue with the timezone. Please contact the dev team.');</script>";
	  $conn->close();
	}
}

function queryMe($query, $rowHeaders) {
	require "../includes/config_m.php";
	$query1 = "SELECT time_zone FROM squads WHERE FQSN='" . $_SESSION['FQSN'] . "'";
	$result = $conn->query($query1);
	if ($result->num_rows > 0) {
    	while($row = $result->fetch_assoc()) {
    		date_default_timezone_set($row['time_zone']);
    	}
    } else {
    	echo "<script>alert('There has been an issue with the timezone. Please contact the dev team.');</script>";
    	$conn->close();
  	}
	$currDate = date("y/m/d");

	header('Content-Type: text/csv; charset=utf-8');
	header('Content-Disposition: attachment; filename=' . $currDate . '.csv');

	ob_end_clean();
	$output = fopen('php://output', 'w');
	fputcsv($output, $rowHeaders);

	$result = $conn->query($query);
	while($row = $result->fetch_assoc()) {
		fputcsv($output, $row);
	}
	exit();
}


?>