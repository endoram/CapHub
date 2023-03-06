<?php
if (session_status() == PHP_SESSION_NONE) {session_start();}
require "control_access.php";

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

if ($_SESSION['table'] == 0) {
  $query = "SELECT * FROM sq_members WHERE hide=0 and retire=0";
  queryit($query);
}

if ($_SESSION['table'] == 11) {
  $query = "SELECT * FROM sq_members WHERE hide=0 and retire=1";
  queryit($query);
}

#Physical Testing table generation on new pt record
if (isset($_POST['stuffmore']) == 1) {
  require 'config_m.php';
  $_SESSION['table'] == 5;

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

  $query = "SELECT date FROM pt WHERE date='$date' && FQSN='" . $_SESSION["FQSN"] . "'";
  $result = $conn->query($query);
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

  $query = "SELECT first_name, last_name, gender FROM sq_members WHERE cap_id='" . $_POST['capid'] . "' && FQSN='" . $_SESSION['FQSN'] . "'";
   $result = $conn->query($query);
  if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
      $name = $row['last_name'] . " " . $row['first_name'];
      $gender = $row['gender'];
    }
    $query = "INSERT INTO pt SET name='" . $name . "', cap_id='" . $_POST['capid'] . "', FQSN='" . $_SESSION['FQSN'] . "', date='" . $date . "', gender='" . $gender . "'";
    $result = $conn->query($query);
    header("Location: ../protected/physical_testing.php?editrec=1");
  } else {
    echo "<script>alert('There was an issue with that CAPID')</script>;";
    header("Location: ../protected/physical_testing.php?editrec=1");
  }
  $conn->close();
}


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
  $query = "UPDATE pt SET name='" . $data["name"] . "', cap_id='" . $data["cap_id"] . "', age='" . $data["age"] . "', push_ups='" . $data["push_ups"] . "', sit_ups='" . $data["sit_ups"] . "', mile_run='" . $data["mile_run"] . "', pacer='" . $data["pacer_test"]  . "', sit_reach='" . $data["sit_reach"]  . "', updated_by=" . $_SESSION['capid']. ", gender='" . $data['gender'] . "' WHERE cap_id='" . $data["cap_id"] . "' && date='$date' && FQSN='" . $_SESSION["FQSN"] . "'";

  $result = $conn->query($query);

  $query = "UPDATE sq_members SET gender='" . $data['gender'] . "' WHERE cap_id=" . $data['cap_id'] . " && FQSN='" . $_SESSION['FQSN'] . "'";
  $result = $conn->query($query);

  $query = "SELECT age, pacer, mile_run, curl_ups, push_ups, sit_reach, gender FROM pt_standards WHERE age=" . $data['age'] . " && gender='" . $data['gender'] . "'";
  $result = $conn->query($query);
  $count = 0; $count1 = 0;
  if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
      if ($row['age'] == $data['age']) {
        if ($row['pacer'] <= $data['pacer_test'] || (strlen($data['mile_run']) >= 3 && $row['mile_run'] >= $data['mile_run'])) {
          echo strlen($data['mile_run']);
          echo " ";
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
      if ($count == 1 AND $count1 >= 2) {
        require 'config_m.php';
        $query = "UPDATE pt SET passed='YES' WHERE cap_id=" . $data['cap_id'] . " && FQSN='" . $_SESSION['FQSN'] . "'";
        echo $query;
        $result = $conn->query($query);
        $conn->close();
      } else {
        require 'config_m.php';
        $query = "UPDATE pt SET passed='NO' WHERE cap_id=" . $data['cap_id'] . " && FQSN='" . $_SESSION['FQSN'] . "'";
        $result = $conn->query($query);
      }
    }
  }
  $conn->close();
}

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
