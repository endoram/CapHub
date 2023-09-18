<?php
/* The above code is a PHP script that handles updating member information in a squadron database. */
require "../includes/header.php";
require "config_m.php";
$y = 0;
$x = 0;


/* This code block is responsible for updating member information in a squadron database. It first
checks if the request method is POST and then retrieves the values from the form fields (firstname,
lastname, capid, cadetornot, priv, password_password). */
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $firstname = $_POST['firstname'];
  $lastname = $_POST['lastname'];
  $capid = $_POST['capid'];
  $cadetornot = $_POST['cadetornot'];
  $priv = $_POST['privlage_level'];
  $password_password = $_POST['password'];
  $cap_ID = $capid;

 /* The code block is checking if the form field with the name "cap-id" is set in the POST request. If
 it is set, it then checks if the value of the  variable is empty or not numeric. If it is
 empty or not numeric, it sets the  variable to "Invalid Cap ID". If the  variable is
 not empty and is numeric, it creates an SQL update query to update the "cap_id" field in the
 "sq_members" table. The query is added to the  array. */
  $querys = array();
  if (isset($_POST['cap-id'])) {
    if ($capid == "" or !is_numeric($capid)) {
      $errorMsg = "Invalid Cap ID";
    }
    else {
      $querys[] = $query0 = "UPDATE sq_members SET cap_id = $cap_ID WHERE cap_id = " . $_SESSION['cap_id'] ;
    }
  }

 /* The code block is checking if the form field with the name "fname" is set in the POST request. If
 it is set, it then checks if the value of the variable  is NULL or contains any
 characters other than letters (A-Z, a-z). */
  if (isset($_POST['fname'])) {
    if ($firstname == NULL or preg_match('/[^A-Za-z]/', $firstname)) {
      $errorMsg = "Invalid Firstname";
    }
    else {
      $querys[] = $query1 = "UPDATE sq_members SET first_name = '$firstname' WHERE cap_id = " . $_SESSION['cap_id'] ;
    }
  }

 /* This code block is checking if the form field with the name "lname" is set in the POST request. If
 it is set, it then checks if the value of the variable  is NULL or contains any characters
 other than letters (A-Z, a-z). */
  if (isset($_POST['lname'])) {
    if ($lastname == NULL or preg_match('/[^A-Za-z]/', $lastname)) {
      $errorMsg = "Invalid lastname";
    }
    else {
      $querys[] = $query2 = "UPDATE sq_members SET last_name = '$lastname' WHERE cap_id = " . $_SESSION['cap_id'] ;
    }
  }

  /* This code block is checking if the form field with the name "cadet" is set in the POST request. If
  it is set, it then checks if the value of the variable  is an empty string. If it is
  empty, it sets the  variable to "Invalid cadet or senior option". */
  if (isset($_POST['cadet'])) {
    if ($cadetornot == ""){
      $errorMsg = "Invalid cadet or senior option";
    }
    if (!in_array($cadetornot, array('cadet','senior', 'Cadet', 'Senior'), true )) {
      $errorMsg = "Invalid cadet or senior option";
    }
    else {
      $querys[] = $query3 = "UPDATE sq_members SET member_type ='" . "$cadetornot" . "' WHERE cap_id=" . $_SESSION['cap_id'] ;
    }
  }

 /* This code block is checking if the form field with the name "priv" is set in the POST request. If
 it is set, it then checks if the form field with the name "privlage_level" is also set. If both
 fields are set, it checks if the value of the variable  is an empty string or not in the array
 ['0','1', '2', '3']. */
  if (isset($_POST['priv'])){
    if(isset($_POST['privlage_level'])){
      if ($priv == "" or !in_array($priv, array('0','1', '2', '3'), true )) {
        $errorMsg = "Invalid privlage level";
      }
      else {
        $querys[] = $query4 = "UPDATE sq_members SET privlage_level = $priv WHERE cap_id = " . $_SESSION['cap_id'] ;
      }
    }
  }

  /* This code block is checking if the form field with the name "pass" is set in the POST request. If it
  is set, it then checks if the form field with the name "password" is also set. If both fields are
  set, it checks if the value of the variable  is an empty string. If it is empty,
  it sets the  variable to "Invalid password". If the  variable is not
  empty, it generates a random 20-byte string using the random_bytes() function and converts it to a
  hexadecimal string using the bin2hex() function. It then concatenates the generated string with the
  value of the  variable. The resulting string is hashed using the SHA-256 algorithm
  using the hash() function. The resulting hashed password is then used in an SQL update query to
  update the "user_passSHA" and "hash" fields in the "sq_members" table, where the "cap_id" field
  matches the value stored in the ['cap_id'] variable. The SQL update query is added to the
  array. */
  if (isset($_POST['pass'])) {
    if (isset($_POST['password'])){
      if ($password_password == "") {
        $errorMsg = "Invalid password";
      }
      else {
        $bytes = random_bytes(20);
        $hash = bin2hex($bytes);
        $pass = $hash . $password_password;
        $hashedPassSHA = hash('sha256', $pass);
        $querys[] = $query5 = "UPDATE sq_members SET user_passSHA='$hashedPassSHA', hash='$hash' WHERE cap_id='". $_SESSION['cap_id'] . "'";
      }
    }
  }

  /* The code block is iterating over the `` array, which contains SQL update queries. For each
  query, it requires the "config_m.php" file, which likely contains the database connection code. It
  then executes the query using the `query()` method of the database connection object ``.
  After executing the query, it sets the variable `` to 1. If the user's privilege level
  (`['privlv']`) is greater than or equal to 3, it also echoes the query. */
  foreach ($querys as $value) {
    require "config_m.php";
    $conn->query($value);
    $y = 1;
    if($_SESSION['privlv'] >= 3){
      echo $value;
    }
  }

  /* This code block is checking if the variable `` is equal to 1. If it is, it proceeds to check if
  the variable `` is not empty. If it is not empty, it sets the variable `` to a string
  that includes a SQL WHERE clause. The WHERE clause filters the results of a SELECT query to only
  include rows where the `cap_id` column starts with the value of `` and the `FQSN` column is
  equal to the value stored in `['FQSN']`. */
  if ($y == 1) {
    if ($cap_ID != '') {
      $data = "WHERE cap_id LIKE '" . $cap_ID . "%' && FQSN=" . $_SESSION['FQSN'];
    }
    else {
      $data = "WHERE cap_id LIKE " . $_SESSION['cap_id'];
    }
    queryit($data);
  }
}

/**
 * The above PHP function performs a database query to retrieve member information based on a CAPID
 * input, and then displays the results in an HTML table.
 * 
 * @param data The `` parameter is a string that is used to construct the SQL query. It is
 * appended to the SELECT statement to filter the results based on certain conditions. In this case, it
 * is used to filter the results based on the CAPID (Civil Air Patrol ID) entered by the user.
 */
function queryit($data) {
  require "config_m.php";
  $query = "SELECT * FROM sq_members " . $data;
  $result = $conn->query($query);

  $today = date("D M j G:i:s T Y");
  $log = $today . ": Added user " . $firstname . " " . $lastname . " By " . $_SESSION['name'];
  $logfile = "../squadrons/" . $_SESSION['something'] . "/log.txt";
  file_put_contents($logfile, $log, FILE_APPEND);

  echo '<div class="sqsearch">
    <br>
    <table>
      <colgroup>
        <col span="3" style="background-color:lightgrey">
        <col style="background-color:red">
      </colgroup>
      <tr>
        <th>First Name</th>
        <th>Last Name</th>
        <th>CAPID</th>
        <th>Priv</th>
      </tr>';

  if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        echo "<tr>
        <td>" . $row["first_name"] . "</td>
        <td>" . $row["last_name"] . "</td>
        <td>" . $row["cap_id"] . "</td>
        <td>" . $row["privlage_level"] . "</td>
        </tr>";
    }
    echo "</table></div></div>";
    $conn->close();
  }
  else {
    echo "<h4 style='color: darkyellow'>No Reults found</h4>";
    $conn->close();
  }
}


/* The below code is checking if the HTTP request method is POST. If it is, it checks if the 'CAPid'
parameter is set in the POST data. If it is, it assigns the value of 'CAPid' to the variable 
and sets  to 1. */
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  /* The below code is checking if a POST variable named 'CAPid' is set. If it is set, it assigns the
  value of the POST variable to the variable . It then sets the variable  to 1. */
  if (isset($_POST['CAPid'])) {
    $CAPid = $_POST['CAPid'];
    $x = 1;

    /* The below code is checking if the variable  is not empty or if it is a numeric value. If
    either condition is true, it sets the  variable to a SQL query string that includes a WHERE
    clause to filter records based on the cap_id column matching the value of . It also sets
    the ['cap_id'] variable to the value of . Finally, it calls the queryit()
    function with the  variable as an argument. */
    if (!$CAPid == "" or is_numeric($CAPid)) {
      $data = "WHERE cap_id LIKE '" . $CAPid . "%'";
      $_SESSION['cap_id'] = $CAPid;
      queryit($data);
    }
    else { $errorMsg = "Invaild CAPID";}
  }
}


/* The below code is a PHP script that handles a form submission. It checks if the request method is
POST and if the submit button was clicked. If both conditions are true, it displays a form with
various input fields and checkboxes. */
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  if (isset($_POST['submit'])) {
    ?>
    <style>
      .hideme {
        display: none;
      }
    </style>
    <!-- The above code is a PHP code snippet that generates an HTML form for updating member
    information. -->
    <div class="addmemberform">
      <p>Select the checkbox next to the item you wish to update.</p>
      <p>Type in the information you want to set to it.</p>
      <p>Finish by clicking "Update Member"</p>
      <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" accept-charset="UTF-8">
        <?php
        if(isset($errorMsg) && $errorMsg) {
          echo "<p style=\"color: red;\">*",htmlspecialchars($errorMsg),"</p>\n\n";
        }?>
        <label for="firstname">First name:</label> <input type="text" name="firstname" align="right" value=<?PHP if(isset($_POST['firstname'])) echo htmlspecialchars($_POST['firstname']); ?>>
        <input type=checkbox value="fname" name="fname"><br>
        <label for="firstname">Last name:</label> <input type="text" name="lastname" align="right" value=<?PHP if(isset($_POST['lastname'])) echo htmlspecialchars($_POST['lastname']); ?>>
        <input type=checkbox value="lname" name="lname"><br>
        <label for="capid">CAP ID:</label> <input type="text" name="capid" align="right" value="<?PHP if(isset($_POST['capid'])) echo htmlspecialchars($_POST['capid']); ?>">
        <input type=checkbox value="cap-id" name="cap-id"><br>
        <?php if($_SESSION['privlv'] >= 2){ ?>
          <label for="privlage_level">Privilege Level:</label> <input type="text" name="privlage_level" align="right" value="<?PHP if(isset($_POST['privlage_level'])) echo htmlspecialchars($_POST['privlage_level']); ?>">
          <input type=checkbox value="priv" name="priv"><br>
          <label for="passsword">Password:</label> <input type="password" name="password" align="right" value="<?PHP if(isset($_POST['password'])) echo htmlspecialchars($_POST['password']); ?>">
          <input type=checkbox value="pass" name="pass"><br>
        <?php } ?>
        <select name="cadetornot">
          <option value="cadet">Cadet</option>
          <option value="senior">Senior</option>
        <select>
        <input type=checkbox value="cadet" name="cadet"><br>
        <input type="submit" value="Update Member">
      </form>
    </div>
    <div class="cancelbutton">
      <form action="../protected/sqmembers.php">
        <input type="submit" value="Cancel">
      </form>
    </div>
<?php
  }
}
?>


<html>
  <head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../protected/style.css">
    <title>CapHub Update Member</title>
  </head>
  <body>
    <div class="hideme">
      <?php
        if(isset($errorMsg) && $errorMsg) {
          echo "<p style=\"color: red;\">*",htmlspecialchars($errorMsg),"</p>\n\n";
        }?>
     <!-- The below code is a PHP code snippet. It checks if the variable  is equal to 1. If it is, it
     displays a heading "Is this the user you want?" and a form with a submit button labeled
     "Confirmed". The form is submitted to the same PHP script that is currently being executed
     (['PHP_SELF']). -->
      <?php if ($x == 1){ ?>
          <h3>Is this the user you want?</h3>
            <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" accept-charset="UTF-8">
            <input type="submit" value="Confirmed" name="submit">
          </form>
      <?php } ?>
      <h3>Update Squadron member:</h3>
      <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" accept-charset="UTF-8">
        <label for="CAPid">Enter CAPID:</label> <input type="text" name="CAPid" align="right" value=<?PHP if(isset($_POST['CAPid'])) echo htmlspecialchars($_POST['CAPid']); ?>><br>
        <input type="submit" value="submit">
      </form>
      <div class="cancelbutton">
        <form action="../protected/sqmembers.php">
          <input type="submit" value="cancel">
        </form>
      </div>
    </div>
  </body>
 <div class="navbar fixed-bottom">
    <div class="container-fluid p-0">
    <?php
     /* The below code is including the "footer.php" file from the "../includes" directory. */
      require "../includes/footer.php";
    ?>
    </div>
  </div>
</html>
