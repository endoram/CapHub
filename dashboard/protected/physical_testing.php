<?php
require "../includes/header.php";

if (isset($_POST['export'])) {
  $rowHeaders = array('Name', 'CAP ID', 'Age', 'Pushups', 'Situps', 'Mile Run', 'Pacer', 'Sit & Reach', 'Date', 'Gender', 'Passed');
  require "../includes/helpers.php";
  queryMe($_POST['exportData'], $rowHeaders);
}


if(isset($_GET['date_range'])) {
  $data = "date_range";
?>
  <script src="../libs/calendar/datepicker.min.js"></script>
  <script>
    const picker = datepicker('.form-popup', {
      alwaysShow: true
    })
  </script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js" defer></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<?
echo '<div class="form-popup" id="myForm">';
echo '<form method="post" action="physical_testing.php" class="form-container">';
echo '<label for="input"><b> Select a start date:</b></label>';

echo '<input type="text" name="daterange"/>';
?>
<script>
$(function() {
  $('input[name="daterange"]').daterangepicker({
    opens: 'left',
    locale: {format: 'YYYY/MM/DD'}
  }, function(start, end, label) {
    console.log("A new date selection was made: " + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD'));
  });
});
</script>

<?
echo '<button type="submit" value="' . $data . '" name="sent" class="btn">Submit</button>';
echo '<button type="button" class="btn cancel" onclick="closeForm()">Close</button>';
echo '</form>';
echo '</div>';
}

if(isset($_POST['sent'])) {submit();}

//Detects whitch one to display when searching
if(isset($_GET['name'])) {
  $data = "Name:";  handleit($data);
}
if(isset($_GET['capid'])) {
  $data = "CAP ID:"; handleit($data);
}
if(isset($_GET['date'])) {
  $data = "date";
?>
  <script src="../libs/calendar/datepicker.min.js"></script>
  <script>
    const picker = datepicker('.form-popup', {
      alwaysShow: true
    })
  </script>
<?
echo '<div class="form-popup" id="myForm">';
echo '<form method="post" action="physical_testing.php" class="form-container">';

echo '<label for="input"><b> Select a date:</b></label>';
echo '<input type="date" name="input" required>';

echo '<button type="submit" value="' . $data . '" name="sent" class="btn">Submit</button>';
echo '<button type="button" class="btn cancel" onclick="closeForm()">Close</button>';
echo '</form>';
echo '</div>';
}
if(isset($_GET['priv'])) {
  $data = "Privlage Level:"; handleit($data);
}

function handleit($data) {
  unset($_GET['firstname, lastname, capid']);

  echo '<div class="form-popup" id="myForm">';
  echo '<form method="post" action="physical_testing.php" class="form-container">';

  echo '<label for="input"><b>' . $data . '</b></label>';
  echo '<input type="text" name="input" required>';

  echo '<button type="submit" value="' . $data . '" name="sent" class="btn">Submit</button>';
  echo '<button type="button" class="btn cancel" onclick="closeForm()">Close</button>';
  echo '</form>';
  echo '</div>';
}

function submit() {                 //Input validation
  if($_POST['sent'] == "Name:") {   //Validation for names
    $firstname = $_POST['input'];
    if (preg_match('/[^A-Z a-z]/', $firstname)) {
      echo "<p style='color: red'>Names don't have numbers in them - try again<p>";
    }
    else {
      $data = "name LIKE '" . $_POST['input'] . "%' && FQSN='" . $_SESSION['FQSN'] . "' ORDER BY date, name";;   //Query statment
      queryit($data);     //Take data to be queryed
    }
  }

  if($_POST['sent'] == "CAP ID:") { //Validation for CAPID
    $capid = $_POST['input'];
    if(!is_numeric($capid)) {
      echo "<p style='color: red'>Invalid Cap ID<p>";
    }
    else {
      $data = "cap_id LIKE '" . $_POST['input'] . "%' && FQSN='" . $_SESSION['FQSN'] . "' ORDER BY date, name";
      queryit($data);
    }
  }

  if($_POST['sent'] == "date") {  //Validation for date
    $date = $_POST['input'];
    if(!isset($date)) {
      echo "<p style='color: red'>Invalid Date<p>";
    }
    else {
      $data = "DATE(date)= '" . $date . "' && FQSN='" . $_SESSION['FQSN'] . "' ORDER BY date, name";
      queryit($data);
    }
  }

  if($_POST['sent'] == "date_range") {  //Validation for date range
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
      $data = "date BETWEEN '" . $dates[0] . "' AND '" . $dates[2] . "' && FQSN='" . $_SESSION['FQSN'] . "' ORDER BY date, name";
      queryit($data);
    }
  }
}


function queryit($data) {           //Query the data and present it
  require "../includes/config_m.php";
  $query = "SELECT name, cap_id, age, push_ups, sit_ups, mile_run, pacer, sit_reach, date, gender, passed FROM pt WHERE " . $data;
  $result = $conn->query($query);

  //Creating table to display information from query
  echo '<div class="sqsearch">
    <br>
    <table>
      <colgroup>
        <col span="11" style="background-color:lightgrey">
      </colgroup>
      <tr>
        <th>Name</th>
        <th>CAP ID</th>
        <th>Age</th>
        <th>Push Ups</th>
        <th>Sit Ups</th>
        <th>Mile Run</th>
        <th>Pacer Test</th>
        <th>Sit & Reach</th>
        <th>Date Time</th>
        <th>Passed</th>
        <th>Gender</th>
      </tr>';

  if ($result->num_rows > 0) {    //If the query is not empty
    while($row = $result->fetch_assoc()) {
        echo "<tr>
        <td>" . $row["name"] . "</td>
        <td>" . $row["cap_id"] . "</td>
        <td>" . $row["age"] . "</td>
        <td>" . $row["push_ups"] . "</td>
        <td>" . $row["sit_ups"] . "</td>
        <td>" . $row["mile_run"] . "</td>
        <td>" . $row["pacer"] . "</td>
        <td>" . $row["sit_reach"] . "</td>
        <td>" . $row["date"] . "</td>
        <td>" . $row["passed"] . "</td>
        <td>" . $row['gender'] . "</td>
        </tr>";
        $rm_capid = $row["cap_id"];
    }
    $conn->close();
  }
  else {
    echo "<h4 style='color: darkyellow'>No Reults found</h4>";
    $conn->close();
  }
  echo "</table></div></div>";
  /*echo '
          <form action="physical_testing.php" method="post">
              <input type="submit" name="export" value="Export"/>
              <input type="hidden" name="exportData" value="' . $query . '"/>
          </form>
          ';*/
}
?>


<script src="../libs/tabulator/jquery-3.2.1.js"></script>
<script src="../libs/tabulator/jquery-ui.js"></script>

<link href="../libs/tabulator-5.4.4/tabulator.min.css" rel="stylesheet">
<script type="text/javascript" src="../libs/tabulator-5.4.4/tabulator.min.js"></script>

<?php
  if(isset($_POST['newrec'])) {
    $hide = 0;
    echo '<div class="life">
      <br>
        <div class=container-life>';
     //   echo '<button onClick="window.location.reload();">Refresh Page</button>';
          echo '<form method="post" action="../includes/sqmem_get-data.php" class="form-container">';
            echo '<button id="clickMe" type="submit" value="new_row" name="new_row" class="btn">Add Person</button>';
            echo '<p style="color:red"><b>Inputs must contain numbers only. A red box indicates an issue with your input.</b></p>';
          echo '</form>';
echo '</div></div>';
    echo '<div id="example-table"></div>';
    
    $_SESSION["table"] = 1;
  } else { $hide = 1; }
  if(isset($_POST['editrec']) || isset($_GET['editrec'])) {
    echo '<div class="life">
      <br>
        <div class=container-life>';
      //  echo '<button onClick="window.location.reload();">Refresh Page</button>';
          echo '<form method="post" action="../includes/sqmem_get-data.php" class="form-container">';
            echo '<button id="clickMe" type="submit" value="new_row" name="new_row" class="btn">Add Person</button>';
            echo '<p style="color:red"><b>Inputs must contain numbers only. A red box indicates an issue with your input.</b></p>';
          echo '</form>';
echo '</div></div>';
    echo '<div id="example-table"></div>';
    $hide = 0;
    $_SESSION["table"] = 2;
  } else { $hide = 1; }
?>


<script>
function openForm(id) {
  document.getElementById(id).style.display = "block";
}

function closeForm(id) {
  document.getElementById(id).style.display = "none";
}
</script>

<html>
  <head>
    <title>PT CAPhub</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <link rel="stylesheet" href="../libs/calendar/datepicker.min.css">
  </head>
  </body>
    <script src="../libs/luxon/luxon.min.js"></script>
    <script>
        var table = new Tabulator("#example-table", {
          height:"500px",
          selectable:false,
          resizableColumns:false,
          layout:"fitColumns",
          addRowPos:"top",
          columns:[
      	    {title:"Name", field:"name", editor:"input", minWidth:"150px", frozen:true},
            {title:"Gender", field:"gender", editor:"list", editorParams:{values:{"Female":"Female", "Male":"Male"}}},
            {title:"Age", field:"age", editor:"list", editorParams:{values:{"12":"12", "13":"13", "14":"14", "15":"15", "16":"16", "17":"17", "18":"18+"}}},
      	    {title:"Push Ups", field:"push_ups", editor:"input", editParams:{mask:"999"}, minWidth:"100", validator:"numeric", validator:"integer"},
      	    {title:"Sit Ups", field:"sit_ups", editor:"input", editParams:{mask:"999"}, minWidth:"100", validator:"numeric", validator:"integer"},
      	    {title:"Mile Run", field:"mile_run", editor:"input", editorParams:{mask:"99999"}, minWidth:"100"},
      	    {title:"Pacer Test", field:"pacer_test", editor:"input", editParams:{mask:"99"}, minWidth:"100", validator:"numeric", validator:"integer"},
      	    {title:"Sit & Reach", field:"sit_reach", editor:"input", editParams:{mask:"99"}, minWidth:"100", validator:"numeric"},
            {title:"Passed", field:"passed", minWidth:"100", visible:false},
          ],
          rowSelectionChanged:function(data, rows){
          	$("#select-stats span").text(data.length);
          },
        });

        table.on("cellEdited", function(cell){
          var row = cell.getRow();
          var rowData = row.getData();
          //console.log(rowData);

          $.ajax({
            type: 'POST',
            url: '../includes/sqmem_get-data.php',
            data: {row:rowData},
            success: function(data) {
         //     console.log(data);
            }
          });
        });

      $.ajax({
        type: 'POST',
        url: '../includes/sqmem_get-data.php',
        data: 'stuffmore=1',
        success: function(data) {
          var tabledata = data;
          table.addData(tabledata);
        }
      });
    </script>

    <?php
    if($hide == 1) {
      if(isset($errorMsg) && $errorMsg) {
        echo "<p style=\"color: red;\">*",htmlspecialchars($errorMsg),"</p>\n\n";
      }
      if(isset($message) && $message) {
        echo "<p style=\"color: green;\">*",htmlspecialchars($message),"</p>\n\n";
      }

      require "../includes/config_m.php";
      $date=date('20y-m-d');
      $query = "SELECT date FROM pt WHERE date='$date' && FQSN='" . $_SESSION["FQSN"] . "'";
      $result = $conn->query($query);

      if ($result->num_rows > 0) {    //If the query is not empty
        echo '<div class="newptrecord">
        <form method="post" action="../protected/physical_testing.php">
          <input type="submit" name="editrec" value="Edit PT Record">
        </form>
      </div>';
      } else {echo "<div class='newptrecord'>
      <h4 style='text-align: center;'>It's recommended to only create a new PT record once everyone has signed in!</h4>
        <form method='post' action='../protected/physical_testing.php'>
          <input type='submit' name='newrec' value='New PT Record'>
        </form>
      </div>";}
      ?>
      <br>
      <div class="dropdown">
        <button class="dropbtn">Search By</button>
        <div class="dropdown-content">
          <a href="?name=1">Name</a>
          <a href="?capid=1">CAP ID</a>
          <a href="?date=1">Date</a>
          <a href="?date_range=1">Date Range</a>
        </div>
      </div>
    <?php }
  require "../includes/config_m.php";
  $query = "SELECT age, push_ups, curl_ups, mile_run, pacer, sit_reach, gender FROM pt_standards";
  $result = $conn->query($query);

  //Creating table to display information from query
  echo '<div class="sqsearch">
    <h4 style="text-align: center;">PT standards according to CAPP 60-50  February, 2018: </h4>
    <table>
      <colgroup>
        <col span="7" style="background-color:lightgrey">
      </colgroup>
      <tr>
        <th>Age</th>
        <th>Pacer</th>
        <th>Mile Run</th>
        <th>Curl Ups</th>
        <th>Push Ups</th>
        <th>Sit & Reach</th>
        <th>Gender</th>
      </tr>';

  if ($result->num_rows > 0) {    //If the query is not empty
    while($row = $result->fetch_assoc()) {
        echo "<tr>
        <td>" . $row["age"] . "</td>
        <td>" . $row["pacer"] . "</td>
        <td>" . $row["mile_run"] . "</td>
        <td>" . $row["curl_ups"] . "</td>
        <td>" . $row["push_ups"] . "</td>
        <td>" . $row["sit_reach"] . "</td>
        <td>" . $row['gender'] . "</td>
        </tr>";
    }
    $conn->close();
  }
  else {
    echo "<h4 style='color: darkyellow'>No Reults found</h4>";
    $conn->close();
  }
  echo "</table></div></div>";
  ?>

  </body>
</html>
