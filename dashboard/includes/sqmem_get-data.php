<?php

/*
*
*   TODO refacter DATE settings - Create or use function
*
*/

if (session_status() == PHP_SESSION_NONE) {session_start();}
require "control_access.php";

/* This code block is checking if the `retired1` parameter is set in the GET request. If it is set, it
retrieves the value of `retired1` and performs an update query on the `sq_members` table based on
the value of `['table']`. If `['table']` is 0, it sets the `retire` column to 1
for the specified `cap_id` values. If `['table']` is 11, it sets the `retire` column to 0
for the specified `cap_id` values. After the update query is executed, it sets `['table']`
to 1. */
if (isset($_GET['retired1'])) {
  require 'config_m.php';
  $data = $_GET['retired1'];
  if ($_SESSION['table'] == 0) {
    $query = "UPDATE sq_members SET retire=1 where cap_id in ($data) && FQSN='" . $_SESSION["FQSN"] . "'";
    $result = $conn->query($query);
    $conn->close();
  }
  if ($_SESSION['table'] == 11) {
    $query = "UPDATE sq_members SET retire=0 where cap_id in ($data) && FQSN='" . $_SESSION["FQSN"] . "'";
    $result = $conn->query($query);
    $conn->close();
  }
  $_SESSION['table'] = 1;
}

/* This code block is checking the value of `['table']`. If it is equal to 0, it executes a
SELECT query to retrieve all rows from the `sq_members` table where the `hide` column is 0 and the
`retire` column is 0. The `queryit()` function is then called with the query as an argument to
execute the query and return the results. */
if ($_SESSION['table'] == 0) {
  $query = "SELECT * FROM sq_members WHERE hide=0 and retire=0";
  queryit($query);
}

/* This code block is checking the value of `['table']`. If it is equal to 11, it executes a
SELECT query to retrieve all rows from the `sq_members` table where the `hide` column is 0 and the
`retire` column is 1. The `queryit()` function is then called with the query as an argument to
execute the query and return the results. */
if ($_SESSION['table'] == 11) {
  $query = "SELECT * FROM sq_members WHERE hide=0 and retire=1";
  queryit($query);
}

#Physical Testing table generation on new pt record
if (isset($_POST['stuffmore']) == 1) {
  require 'config_m.php';
  $_SESSION['table'] == 5;

  /* This line of code is creating a SQL query to select the `time_zone` column from the `squads` table
  where the `FQSN` column is equal to the value stored in the `['FQSN']` variable. */
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
  $date=date('20y-m-d');

  /* This line of code is creating a SQL query to select the `date` column from the `pt` table where
  the `date` column is equal to the value stored in the `` variable and the `FQSN` column is
  equal to the value stored in the `["FQSN"]` variable. */
  $query = "SELECT date FROM pt WHERE date='$date' && FQSN='" . $_SESSION["FQSN"] . "'";
  $result = $conn->query($query);
  /* The below code is a PHP script that retrieves data from a database table called "pt" based on a
  specific date and the value stored in the session variable "FQSN". */
  if ($result->num_rows > 0) {    //If the query is not empty
    $query = "SELECT * FROM pt WHERE date='$date' && FQSN='" . $_SESSION["FQSN"] . "'";
    $result = $conn->query($query);
    if ($result->num_rows > 0) {
      $row1 = '';
      while($row = $result->fetch_assoc()) {
        $row1 = '{"name":"' . $row['name'] . '", "cap_id":"' . $row['cap_id'] . '", "age":"' . $row['age'] . '", "push_ups":"' . $row['push_ups'] . '", "sit_ups":"' . $row['sit_ups'] . '", "mile_run":"' . $row['mile_run'] . '", "pacer_test":"' . $row['pacer'] . '", "sit_reach":"' . $row['sit_reach'] . '", "gender":"' . $row['gender'] . '", "passed":"' . $row['passed'] . '"}' . $row1;
      }
      $row1 = str_replace("}{", '},{', $row1);
      $row1 = "[" . $row1 . "]";
      echo $row1;
    }
    $conn->close();
  } else {
    //$query = "SELECT name, cap_id FROM meeting_nights WHERE member_type='cadet' && date='$date' && FQSN='" . $_SESSION["FQSN"] . "'";
    
    /* The above code is performing a SQL query to retrieve data from two tables, "meeting_nights" and
    "sq_members". It is selecting the "gender" column from the "sq_members" table, and the "name"
    and "cap_id" columns from the "meeting_nights" table. */
    $query = "
    SELECT sq_members.gender, meeting_nights.name, meeting_nights.cap_id
    FROM meeting_nights
    INNER JOIN sq_members ON meeting_nights.cap_id = sq_members.cap_id
    WHERE meeting_nights.member_type='cadet' && meeting_nights.date='$date' && meeting_nights.FQSN='" . $_SESSION["FQSN"] . "'";
    $result = $conn->query($query);
    $conn->close();
    $row1 = "";

    if ($result->num_rows > 0) {
      while($row = $result->fetch_assoc()) {
        /* The below code is checking if the value of the 'table' key in the  array is equal
        to 1. If it is, then it includes the 'config_m.php' file, and then executes an SQL INSERT
        query to insert data into the 'pt' table. The values being inserted are obtained from the
         array and the  array. */
        if ($_SESSION['table'] == 1) {
          require 'config_m.php';
              $query1 = "INSERT INTO pt (name, cap_id, date, FQSN, gender) VALUES ('" . $row['name'] . "'," . $row['cap_id'] . ", '$date', '" . $_SESSION["FQSN"] . "', '" . $row['gender'] . "')";   
              $result1 = $conn->query($query1);
        }
        $row1 = '{"name":"' . $row['name'] . '", "cap_id":"' . $row['cap_id'] . '", "age":"", "push_ups":" ", "sit_ups":" ", "mile_run":" ", "pacer_test":" ", "sit_reach":" ", "gender":"' . $row['gender'] . '", "passed":"' . $row['passed'] . '"}' . $row1;
      }
      $row1 = str_replace("}{", '},{', $row1);
      $row1 = "[" . $row1 . "]";
      $conn->close();
      echo $row1;
    }
  } 
} 

if (isset($_POST['capid'])) {
  require 'config_m.php';
 /* The below code is creating a SQL query to select the "time_zone" column from the "squads" table.
 The query is using the value stored in the ['FQSN'] variable as a condition to filter the
 results. */
  $query = "SELECT time_zone FROM squads WHERE FQSN='" . $_SESSION['FQSN'] . "'";
  $result = $conn->query($query);
  if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
      /* The below code is setting the default timezone for the PHP script to the value stored in the
      'time_zone' column of the  variable. */
      date_default_timezone_set($row['time_zone']);
    }
  }
  else {
    echo "<script>alert('There has been an issue with the timezone. Please contact the dev team.');</script>";
    $conn->close();
  }
  /* The below code is using the PHP date function to get the current date in the format "20y-m-d",
  where "y" represents the year, "m" represents the month, and "d" represents the day. */
  $date=date('20y-m-d');

 /* The below code is performing a SQL query to select the first name, last name, and gender from the
 "sq_members" table. The query is filtering the results based on the "cap_id" value from the 
 superglobal variable and the "FQSN" value from the  superglobal variable. */
  $query = "SELECT first_name, last_name, gender FROM sq_members WHERE cap_id='" . $_POST['capid'] . "' && FQSN='" . $_SESSION['FQSN'] . "'";
   $result = $conn->query($query);
  if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
      $name = $row['last_name'] . " " . $row['first_name'];
      $gender = $row['gender'];
    }
    /* The below code is constructing an SQL query to insert data into a table named "pt". The query
    includes values for the columns "name", "cap_id", "FQSN", "date", and "gender". The values for
    these columns are obtained from variables and form inputs ( and ). */
    $query = "INSERT INTO pt SET name='" . $name . "', cap_id='" . $_POST['capid'] . "', FQSN='" . $_SESSION['FQSN'] . "', date='" . $date . "', gender='" . $gender . "'";
    $result = $conn->query($query);
    header("Location: ../protected/physical_testing.php?editrec=1");
  } else {
    echo "<script>alert('There was an issue with that CAPID')</script>;";
    header("Location: ../protected/physical_testing.php?editrec=1");
  }
  $conn->close();
}


/* The below code is a PHP script that generates an HTML form for adding a new row to a database table. */
/* The below code is checking if a POST request has been made with a parameter named 'new_row' and its
value is 'new_row'. If this condition is true, it executes the code inside the if statement. */
if (isset($_POST['new_row']) && $_POST['new_row'] == "new_row") {
  ?>
  <html>
  <head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../protected/style.css">
    <a href="../protected/main.php"><img src="../images/banner.png"></a>
    <title>CapHub Add Row</title>
  </head>
  <body>
    <div class="column">
      <div class="addmemberform">
        <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" accept-charset="UTF-8">
          <?php
          if(isset($errorMsg) && $errorMsg) {
            echo "<p style=\"color: red;\">*",htmlspecialchars($errorMsg),"</p>\n\n";
          }?>
          <label for="capid">CAP ID:</label> <input type="text" id="capid" name="capid" align="right" title="Must be a proper CAPID" pattern="[0-9].{5,}" value="<?PHP if(isset($_POST['capid'])) echo htmlspecialchars($_POST['capid']); ?>" required><br>

          <br>
          <input type="hidden" name="editrec" value="Edit Pt Record">
          <input type="submit" value="Add Row">
        </form>
        <div class="cancelbutton">
          <form action="../protected/sqmembers.php">
            <input type="submit" value="Cancel">
          </form>
        </div>
      </div>
    </div>
    <div class="column">
      <div id="message2">
        <h3>CAPID must be all numbers and at least 6 numbes:</h3>
        <p id="number2" class="invalid"><b>all numbers</b></p>
        <p id="length2" class="invalid">Must be <b>at least</b> 6 numbers</p>
      </div>
    </div>
  </body>
</html>
<script>
var capid1 = document.getElementById("capid");
var number2 = document.getElementById("number2");
var length2 = document.getElementById("length2");

capid1.onfocus = function() {document.getElementById("message2").style.display = "block";}
capid1.onblur = function() {document.getElementById("message2").style.display = "none";}

capid1.onkeyup = function() {
  var numbers = /[0-9]/g;
  if(capid1.value.match(numbers)) {
    number2.classList.remove("invalid");
    number2.classList.add("valid");
  } else {
    number2.classList.remove("valid");
    number2.classList.add("invalid");
  }
  if(capid1.value.length >= 6) {
    length2.classList.remove("invalid");
    length2.classList.add("valid");
  } else {
    length2.classList.remove("valid");
    length2.classList.add("invalid");
  }
}
</script>
<?php
}

//PT update row function
if (isset($_POST['row'])) {
  $data = $_POST['row'];

  require 'config_m.php';
 /* The above code is creating a SQL query to select the "time_zone" column from the "squads" table.
 The query is using the value stored in the ['FQSN'] variable as a condition to filter the
 results. */
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

  $date = date("Y/m/d");
  /* The below code is performing an SQL UPDATE query in PHP. It is updating the "pt" table with the
  values provided in the  array. The values being updated include the name, cap_id, age,
  push_ups, sit_ups, mile_run, pacer, sit_reach, updated_by, and gender. The WHERE clause specifies
  the conditions for the update, which include matching the cap_id, date, and FQSN values. */
  $query = "UPDATE pt SET name='" . $data["name"] . "', cap_id='" . $data["cap_id"] . "', age='" . $data["age"] . "', push_ups='" . $data["push_ups"] . "', sit_ups='" . $data["sit_ups"] . "', mile_run='" . $data["mile_run"] . "', pacer='" . $data["pacer_test"]  . "', sit_reach='" . $data["sit_reach"]  . "', updated_by=" . $_SESSION['capid']. ", gender='" . $data['gender'] . "' WHERE cap_id='" . $data["cap_id"] . "' && date='$date' && FQSN='" . $_SESSION["FQSN"] . "'";

  $result = $conn->query($query);

  /* The below code is performing an SQL UPDATE query in PHP. It is updating the 'gender' column in the
  'sq_members' table with the value from the ['gender'] variable. The update is being done for
  the row where the 'cap_id' column matches the value from the ['cap_id'] variable and the
  'FQSN' column matches the value from the ['FQSN'] variable. */
  $query = "UPDATE sq_members SET gender='" . $data['gender'] . "' WHERE cap_id=" . $data['cap_id'] . " && FQSN='" . $_SESSION['FQSN'] . "'";
  $result = $conn->query($query);

  /* The below code is constructing a SQL query to select data from a table called "pt_standards". The
  query is selecting the columns "age", "pacer", "mile_run", "curl_ups", "push_ups", "sit_reach", and
  "gender" from the table. The query includes a WHERE clause to filter the results based on the values
  of the "age" and "gender" columns. The values for "age" and "gender" are being passed in from a
  variable called "". */
  $query = "SELECT age, pacer, mile_run, curl_ups, push_ups, sit_reach, gender FROM pt_standards WHERE age=" . $data['age'] . " && gender='" . $data['gender'] . "'";
  $result = $conn->query($query);
  $count = 0; $count1 = 0;

  /* The below code is checking if the result from a database query has more than 0 rows. If it does,
  it loops through each row and checks if the 'age' column in the row is equal to the 'age' value in
  the array. If it is, it checks if the 'pacer' column in the row is less than or equal to the
  'pacer_test' value in the array, or if the 'mile_run' column in the row is greater than or equal
  to the 'mile_run' value in the array (if the length of 'mile_run' is */
  if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
      if ($row['age'] == $data['age']) {
        if ($row['pacer'] <= $data['pacer_test'] || (strlen($data['mile_run']) >= 3 && $row['mile_run'] >= $data['mile_run'])) {
          $count = $count + 1;
        }
        if ($row['curl_ups'] <= $data['sit_ups']) {
          $count1 = $count1 + 1;
        }
        if ($row['push_ups'] <= $data['push_ups']){
          $count1 = $count1 + 1;
        }
        if ($row['sit_reach'] <= $data['sit_reach']){
          $count1 = $count1 + 1;
        }
      }
      /* The below code is checking if the variable  is equal to 1 and the variable  is
      greater than or equal to 2. If this condition is true, it includes the 'config_m.php' file,
      constructs an SQL query to update the 'pt' table, sets the 'passed' column to 'YES' where the
      'cap_id' column matches the value of ['cap_id'] and the 'FQSN' column matches the value
      of ['FQSN']. It then executes the query and closes the database connection. */
      if ($count == 1 AND $count1 >= 2) {
        require 'config_m.php';
        $query = "UPDATE pt SET passed='YES' WHERE cap_id=" . $data['cap_id'] . " && FQSN='" . $_SESSION['FQSN'] . "'";
        $result = $conn->query($query);
        $conn->close();
      } 
      /* The below code is updating the "passed" column in the "pt" table of a database. It sets the
      value of "passed" to 'NO' for a specific row where the "cap_id" column matches the value of
      ['cap_id'] and the "FQSN" column matches the value of ['FQSN']. */
      else {
        require 'config_m.php';
        /* The below code is performing an SQL update query in PHP. It is updating the "passed" column
        of the "pt" table to 'NO' where the "cap_id" column matches the value of ['cap_id'] and
        the "FQSN" column matches the value of ['FQSN']. */
        $query = "UPDATE pt SET passed='NO' WHERE cap_id=" . $data['cap_id'] . " && FQSN='" . $_SESSION['FQSN'] . "'";
        $result = $conn->query($query);
      }
    }
  }
  $conn->close();
}

/**
 * The function "queryit" takes a SQL query as input, appends a condition based on the value of the
 * session variable "FQSN", executes the query, fetches the result rows, and returns them as a
 * JSON-encoded string.
 * 
 * @param query The query parameter is a SQL query statement that you want to execute. It can be any
 * valid SQL statement such as SELECT, INSERT, UPDATE, DELETE, etc.
 */
function queryit($query) {
  require 'config_m.php';
  $query = $query . " && FQSN='" . $_SESSION["FQSN"] . "'";
  $result = $conn->query($query);
  $conn->close();

  $rows = array();
  if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
      $rows[] = $row;
    }
  }
  echo json_encode($rows);
 # echo($query); //Check console for SQL query statment -> click on link view SQL statement in URL
}

?>
