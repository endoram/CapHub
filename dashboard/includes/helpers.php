<?php
/*
 * The above code is a PHP script that contains various functions for querying and manipulating data in
 * a database.
 */

//require "control_access.php";

/**
 * The function "adduser" is used to add a new user to a database, with parameters for their first
 * name, last name, CAP ID, cadet status, privilege level, and password.
 * 
 * @param firstname The first name of the user being added.
 * @param lastname The parameter "lastname" is used to store the last name of the user being added to
 * the system.
 * @param capid The "capid" parameter is the CAP ID of the user being added. CAP ID stands for Civil
 * Air Patrol Identification Number, which is a unique identifier assigned to each member of the Civil
 * Air Patrol organization.
 * @param cadetornot The parameter "cadetornot" is used to determine whether the user is a cadet or
 * not. It is a boolean value, where "1" represents a cadet and "0" represents a non-cadet.
 * @param priv The "priv" parameter is used to determine the privilege level of the user being added.
 * It is a numeric value that represents the level of access or permissions the user will have within
 * the system.
 * @param password_password The parameter "password_password" is the password that the user wants to
 * set for the new user.
 * 
 * @return a string value. The possible return values are "CAPID already assigned" if the CAP ID
 * already exists in the database, "Done" if the user is successfully added to the database, or nothing
 * if there is an error.
 */
function adduser($firstname, $lastname, $capid, $cadetornot, $priv, $password_password) {
  require "config_m.php";

/* The code snippet is generating a random string of bytes using the `random_bytes()` function. The
bytes are then converted to a hexadecimal string using the `bin2hex()` function. */
  $bytes = random_bytes(20);
  $hash = bin2hex($bytes);
  $pass = $hash . $password_password;
  $hashedPassSHA = hash('sha256', $pass);

  /* The line of code ` = password_hash(, PASSWORD_DEFAULT);` is generating
  a hashed version of the user's password. The `password_hash()` function is a built-in PHP function
  that takes the user's password as the first parameter and a hashing algorithm as the second
  parameter. In this case, `PASSWORD_DEFAULT` is used as the hashing algorithm, which is a constant
  that represents the strongest algorithm available on the system. The hashed password is then
  stored in the variable `` for later use, such as storing it in a database. */
  $hash_pass = password_hash($password_password, PASSWORD_DEFAULT);
  $y = 0;

  if($_SESSION['privlv'] <= 1){
    $priv = "0";
    $password_password = NULL;
  }
  else {
    $priv = $priv;
#$_POST['privlage_level'];
    $password_password = $password_password;
#$_POST['psw'];
  }


  /* The below code is creating a SQL query to select the "cap_id" column from the "sq_members" table
  where the "cap_id" is equal to the value of the variable "". */
  $query = "SELECT cap_id FROM sq_members WHERE cap_id=" . $capid;
  $result = $conn->query($query);

  if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
      if ($row['cap_id'] == $capid) {
        $GLOBALS['errorMsg'] = "A member already is asigned that CAP ID";
        $y = 1;
	      return "CAPID already asigned";
      }
    }
  }

  if ($y == 0) {
    $FQSN = $_SESSION['FQSN'];
    /* The below code is creating an SQL query to insert data into a table called "sq_members". The
    query is inserting values into the columns "cap_id", "first_name", "last_name", "member_type",
    "privlage_level", "user_passSHA", "FQSN", and "hash". The values being inserted are variables
    that are concatenated into the query. */
    $query = "INSERT INTO sq_members (cap_id, first_name, last_name, member_type, privlage_level, user_passSHA, FQSN, hash)
    VALUES (" . $capid . ",'" . $firstname . "', '" . $lastname . "', '" . $cadetornot . "', '" . $priv . "', '" . $hashedPassSHA . "', '" . $FQSN . "', '" . $hash . "')";
    $conn->query($query);
    $conn->close();
    return "Done";
    // If un-commented when add user confirmed it will return you to sqmembers.php
  #  header("Location: ../protected/sqmembers.php");
  }
  else {$conn->close();}
}


/**
 * The function `dateSearch` is a PHP function that generates a form with a date input field and a
 * submit button, which can be used to search for a specific date.
 * 
 * @param data The `` parameter is a variable that holds the data you want to pass to the form. It
 * is used as the value of the submit button.
 * @param query The query parameter is a string that represents the search query or keyword that will
 * be used in the search functionality. It is used to pass the search query to the form submission so
 * that it can be used in the search process.
 * @param page The "page" parameter is the URL or file path where the form data will be submitted to.
 */
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


/**
 * The function `dateRange` generates a form with a date range picker and submit button.
 * 
 * @param data The `` parameter is the value that will be passed to the `value` attribute of the
 * submit button. It can be any string or variable that you want to pass along with the form
 * submission.
 * @param query The "query" parameter is a string that represents the query or search term that will be
 * used in the form submission. It is used to pass the query to the next page when the form is
 * submitted.
 * @param page The "page" parameter is the URL or file path where the form will be submitted to. It is
 * the destination page where the form data will be processed.
 */
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

/**
 * The function "searchMe" creates a form popup in PHP for searching data.
 * 
 * @param data The "data" parameter is a string that represents the type of data you want to search
 * for. It could be something like "name", "email", or "address".
 * @param query The "query" parameter is a string that represents the query or search term that will be
 * used to search for data. It is passed as a hidden input field in the form and will be used in the
 * action URL of the form.
 * @param page The "page" parameter is the URL or file path where the form will be submitted to. It is
 * used in the "action" attribute of the form element.
 */
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

/**
 * The function "queryCreate" takes in various parameters and performs validation checks on user input
 * before executing a query based on the input.
 * 
 * @param sent The "sent" parameter is used to determine the type of query being performed. It can have
 * values such as "Name:", "CAP ID:", "date", "Firstname:", "Lastname:", and "Privilege Level:".
 * @param query The query parameter is the SQL query that will be executed to retrieve the data from
 * the database.
 * @param page The "page" parameter is used to specify the page number of the query results that should
 * be displayed. It is used to implement pagination in the query results.
 * @param displayHeaders A boolean value indicating whether or not to display the headers in the query
 * results.
 * @param queryHeaders An array of column names that will be displayed as headers in the query results.
 */
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

    /* The below code is a PHP script that handles form submissions. It checks the value of the 'sent'
    field in the  array to determine which form field was submitted. */
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
  

  /* The below code is checking if the value of the variable  is equal to "date_range". If it is,
  it retrieves the value of the "daterange" input field from a form using the  superglobal. It
  then removes any hyphens from the date string and splits it into an array using spaces as the
  delimiter. */
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

/**
 * The function "queryit" takes in data, a query, a page, display headers, and query headers as
 * parameters, executes the query on the database, and displays the results in a table format with an
 * option to export the data.
 * 
 * @param data The `` parameter is the data that you want to query. It is appended to the ``
 * parameter to form the complete query.
 * @param query The query parameter is the SQL query that you want to execute. It should be a string
 * that represents a valid SQL query.
 * @param page The "page" parameter is the URL of the page where the query results will be displayed.
 * @param displayHeaders An array of strings representing the headers to be displayed in the table.
 * @param queryHeaders The queryHeaders parameter is an array that contains the column names or keys of
 * the data that you want to display in the table. Each element in the array represents a column in the
 * table.
 */
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

/**
 * The function `timeZone()` retrieves the time zone from the database based on the current session and
 * sets it as the default time zone for the PHP script.
 */
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

/**
 * The function exports data from a MySQL query to a CSV file.
 * 
 * @param query The query parameter is a SQL query that retrieves the data you want to export. It
 * should be a valid SQL SELECT statement.
 * @param rowHeaders The rowHeaders parameter is an array that contains the headers for each column in
 * the exported CSV file. These headers will be added as the first row in the CSV file.
 */
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

/**
 * The ARP function retrieves the first name and last name of a member from a database based on their
 * cap_id.
 * 
 * @param cap_id The `cap_id` parameter is used to identify a specific member in the `sq_members`
 * table. It is used in the SQL query to retrieve the first name and last name of the member associated
 * with that `cap_id`.
 * 
 * @return The function ARP returns the full name (first name and last name) of a member from the
 * sq_members table in the database, based on the provided cap_id.
 */
function ARP($cap_id) {
  require '../includes/config_m.php';
  $query = "SELECT first_name, last_name FROM sq_members WHERE cap_id=$cap_id";
  $result = $conn->query($query);

  if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
      $name = $row['first_name'] ." ". $row['last_name'];
    }
  }
  return $name;
}
?>
