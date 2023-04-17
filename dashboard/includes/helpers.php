<?php
//require "control_access.php";

function dateSearch($data, $query, $page) {
	echo "<script src='../libs/calendar/datepicker.min.js'></script>
	<script>
    	const picker = datepicker('.form-popup', {
    	alwaysShow: true
    	})
	</script>";
	echo '<div class="form-popup" id="myForm">';
	echo '<form method="post" action="'.$page.'" class="form-container">';

	echo '<label for="input"><b> Select a date:</b></label>';
	echo '<input type="date" name="input" required>';

	echo '<button type="submit" value="' . $data . '" name="sent" class="btn">Submit</button>';
	echo '<input type="hidden" name="query" value="'.$query.'">';
	echo '<input type="hidden" name="page" value="'.$page.'">';
	echo '<button type="button" class="btn cancel" onclick="closeForm()">Close</button>';
	echo '</form>';
	echo '</div>';
}

function dateRange($data, $query, $page) {
?>
<script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js" defer></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css"/>
<?
echo '<div class="form-popup" id="myForm">';
echo '<form method="post" action="'.$page.'" class="form-container">';
echo '<label for="input"><b> Select a start date:</b></label>';

echo '<input type="text" name="daterange"/>';
?>
<script>
$(function() {
  $('input[name="daterange"]').daterangepicker({
    opens: 'left',
    locale: {format: 'YYYY-MM-DD'}
  }, function(start, end, label) {
  //  console.log("A new date selection was made: " + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD'));
  });
});
</script>

<?
echo '<button type="submit" value="' . $data . '" name="sent" class="btn">Submit</button>';
echo '<button type="button" class="btn cancel" onclick="closeForm()">Close</button>';
echo '<input type="hidden" name="query" value="'.$query.'">';
echo '<input type="hidden" name="page" value="'.$page.'">';
echo '</form>';
echo '</div>';
}

function searchMe($data, $query, $page) {
	unset($_GET['firstname, lastname, capid']);

	echo '<div class="form-popup" id="myForm">';
	echo '<form method="post" action="' . $page . '" class="form-container">';

	echo '<label for="input"><b>' . $data . '</b></label>';
	echo '<input type="text" name="input" required>';

	echo '<button type="submit" value="' . $data . '" name="sent" class="btn">Submit</button>';
	echo '<input type="hidden" name="query" value="'.$query.'">';
	echo '<input type="hidden" name="page" value="'.$page.'">';
	echo '<button type="button" class="btn cancel" onclick="closeForm()">Close</button>';
	echo '</form>';
	echo '</div>';
}

function queryCreate($sent, $query, $page, $displayHeaders, $queryHeaders) {
	if($sent == "Name:") {   //Validation for names
    $firstname = $_POST['input'];
    if (preg_match('/[^A-Z a-z]/', $firstname)) {
      echo "<p style='color: red'>Names don't have numbers in them - try again<p>";
    }
    else {
      $data = "name LIKE '" . $_POST['input'] . "%'";   //Query statment
      queryit($data, $query, $page, $displayHeaders, $queryHeaders);     //Take data to be queryed
    }
 	}
 	  if($sent == "CAP ID:") { //Validation for CAPID
    $capid = $_POST['input'];
    if(!is_numeric($capid)) {
      echo "<p style='color: red'>Invalid Cap ID<p>";
    }
    else {
      $data = "cap_id LIKE '" . $_POST['input'] . "'";
      queryit($data, $query, $page, $displayHeaders, $queryHeaders);
    }
  }

  if($sent == "date") {  //Validation for date
    $date = $_POST['input'];
    $contents = str_replace("-", "/", $date);
    if(!isset($contents)) {
      echo "<p style='color: red'>Invalid Date<p>";
    }
    else {
      $data = "date='" . $contents . "' ORDER BY name";
      queryit($data, $query, $page, $displayHeaders, $queryHeaders);
    }

   }

    if($_POST['sent'] == "Firstname:") {
    $firstname = $_POST['input'];
    if (preg_match('/[^A-Za-z]/', $firstname)) {
      echo "<p style='color: red'>Names don't have numbers in them - try again<p>";
    }
    else {
      $data = "first_name LIKE '" . $_POST['input'] . "%'";
      queryit($data, $query, $page, $displayHeaders, $queryHeaders);
    }
	  }
	  if($_POST['sent'] == "Lastname:") {
	    $lastname = $_POST['input'];
	    if (preg_match('/[^A-Za-z]/', $lastname)) {
	      echo "<p style='color: red'>Names don't have numbers in them - try again<p>";
	    }
	    else {
	      $data = "last_name LIKE '" . $_POST['input'] . "%'";
	      queryit($data, $query, $page, $displayHeaders, $queryHeaders);
	    }
	  }
	if($_POST['sent'] == "Privilege Level:") {
	    $priv = $_POST['input'];
	    if(!is_numeric($priv)) {
	      echo "<p style='color: red'>Invalid Privlage Level<p>";
	    }
	    else {
	      $data = "privlage_level=" . $_POST['input'];
	      queryit($data, $query, $page, $displayHeaders, $queryHeaders);
	    }
  	}
  

  if($sent == "date_range") {  //Validation for date range
    $date = $_POST['daterange'];
    #echo $date;
    $contents = str_replace("-", "", $date);
    #echo $contents;
    $dates = explode(" ", $contents);
    #var_dump($dates);
    if(!isset($contents)) {
      echo "<p style='color: red'>Invalid Date<p>";
    }
    else {
      $data = "date BETWEEN '" . $dates[0] . "' AND '" . $dates[2] . "' ORDER BY date, name";
      queryit($data, $query, $page, $displayHeaders, $queryHeaders);
    }
  }
}

function queryit($data, $query, $page, $displayHeaders, $queryHeaders) {
	require "../includes/config_m.php";
	$query = $query . " " . $data;
	$result = $conn->query($query);
	$count = 1;

	echo '<div class="sqsearch">
    <br>
    <table>
      <colgroup>
        <col span="'.count($displayHeaders).'" style="background-color:lightgrey">
      </colgroup>
      <tr>';

	foreach ($displayHeaders as $key => $value) {
		echo "<th>".$value."</th>";
	}
	echo '</tr>';

  if ($result->num_rows > 0) {    //If the query is not empty
    while($row = $result->fetch_assoc()) {
    	echo "<tr>";
    	echo "<td>".$count."</td>";
    	foreach ($queryHeaders as $key => $value) {
    		echo "<td>" . $row[$value] . "</td>";
    	}
        echo "</tr>";
        $count = $count + 1;
    }
    $conn->close();
  }
  else {
    echo "<h4 style='color: darkyellow'>No Reults found</h4>";
    $conn->close();
  }
  echo "</table></div></div>";
    echo "</table></div></div>";
  echo '
          <form action="'.$page.'" method="post">
              <input type="submit" name="export" value="Export"/>
              <input type="hidden" name="exportData" value="' . $query . '"/>
          </form>
          ';
}

function timeZone() {
	require '../includes/config_m.php';

	$query = "SELECT time_zone FROM squads WHERE FQSN='" . $_SESSION['FQSN'] . "'";
	$result = $conn->query($query);
	if ($result->num_rows > 0) {
	  while($row = $result->fetch_assoc()) {
	    date_default_timezone_set($row['time_zone']);
	    $time = $row['time_zone'];
	  }
	}
	else {
	  echo "<script>alert('There has been an issue with the timezone. Please contact the dev team.');</script>";
	  $conn->close();
	}
}

function exportMe($query, $rowHeaders) {
	timeZone();
	$currDate = date("y-m-d");

	require "../libs/export/php-export.php";
	$filename = $currDate.".csv";
	$exporter = new ExportDataCSV('browser', $filename);
	require "../includes/config_m.php";

	$exporter->initialize();
	$exporter->addRow($rowHeaders);

	$result = $conn->query($query);
	while($row = $result->fetch_assoc()) {
		$exporter->addRow($row); 
	}
	$exporter->finalize();
	exit();
}

function ARP($cap_id) {
  require '../includes/config_m.php';
  $query = "SELECT first_name, last_name FROM sq_members WHERE FQSN='".$_SESSION['FQSN']."' && cap_id=$cap_id";
  $result = $conn->query($query);

  if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
      $name = $row['first_name'] ." ". $row['last_name'];
    }
  }
  return $name;
}
?>