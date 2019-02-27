<?php
require "../includes/header.php";

if(isset($_POST['sent'])) {submit();}

//Detects whitch one to display when searching
if(isset($_GET['name'])) {
  $data = "Name:";  handleit($data);
}
if(isset($_GET['capid'])) {
  $data = "CAP ID:"; handleit($data);
}
if(isset($_GET['date'])) {
  $data = "Date(YY-MM-DD):"; handleit($data);
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
      $data = "name LIKE '" . $_POST['input'] . "%'";   //Query statment
      queryit($data);     //Take data to be queryed
    }
  }

  if($_POST['sent'] == "CAP ID:") { //Validation for CAPID
    $capid = $_POST['input'];
    if(!is_numeric($capid)) {
      echo "<p style='color: red'>Invalid Cap ID<p>";
    }
    else {
      $data = "cap_id LIKE '" . $_POST['input'] . "%'";
      queryit($data);
    }
  }

  if($_POST['sent'] == "Date(YY-MM-DD):") {  //Validation for date
    if($_POST['input'] == "") {
      echo "<p style='color: red'>Invalid Date<p>";
    }
    else {
      $data = "date='" . $_POST['input'] . "%'";
      queryit($data);
    }
  }
}

function queryit($data) {           //Query the data and present it
  require "../includes/config_m.php";
  $query = "SELECT * FROM physical_testing WHERE " . $data;
  echo $query;
  $result = $conn->query($query);

  //Creating table to display information from query
  echo '<div class="sqsearch">
    <br>
    <table>
      <colgroup>
        <col span="9" style="background-color:lightgrey">
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
        <td>" . $row["pacer_test"] . "</td>
        <td>" . $row["sit_reach"] . "</td>
        <td>" . $row["date"] . "</td>
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
}
?>

<!--Script to handle opeing and closing of search box-->
<script>
function openForm() {
    document.getElementById("myForm").style.display = "block";
}

function closeForm() {
    document.getElementById("myForm").style.display = "none";
}
</script>

<script src="../libs/jquery-3.2.1.js"></script>
<script src="../libs/jquery-ui.js"></script>
<link href="../libs/tabulator.min.css" rel="stylesheet"></script>
<script src="../libs/tabulator.min.js"></script>

<?php
  if(isset($_POST['newrec'])) {
    echo  '<div class="life">
      <br>
        <input id="clickMe" type="button" value="New Row" onclick="myFunction();" />
        <input id="clickMe1" type="button" value="save" onclick="myFunction();" />
        <input id="clickMe2" type="button" name="finish" value="Finish" onclick="myFunction2();"/>
        <input id="clickMe3" type="button" name="recover" value="Recover Last Save" onclick="myFunction3();"/>
        </div>';
    echo '<div id="example-table"></div>';
    $hide = 0;
    $_SESSION["table"] = 1;
  }
  else { $hide = 1; }
?>

<html>
  <head>
    <title>PT CAPhub</title>
    <link rel="stylesheet" type="text/css" href="style.css">
  </head>
  </body>
    <script>
        var table = new Tabulator("#example-table", {
          height:"500px",
          selectable:false,
          resizableColumns:false,
          layout:"fitColumns",
          addRowPos:"top",
          columns:[
      	    {title:"Name", field:"name", editor:"input", minWidth:"150px", frozen:true},
            {title:"CAP ID", field:"cap_id", editor:"input", minWidth:"100px"},
            {title:"Age", field:"age", editor:"input", minWidth:"75"},
      	    {title:"Push Ups", field:"push_ups", editor:"input", minWidth:"100"},
      	    {title:"Sit Ups", field:"sit_ups", editor:"input", minWidth:"100"},
      	    {title:"Mile Run", field:"mile_run", editor:"input", minWidth:"100"},
      	    {title:"Pacer Test", field:"pacer_test", editor:"input", minWidth:"100"},
      	    {title:"Sit & Reach", field:"sit_reach", editor:"input", minWidth:"100"},
          ],
          rowSelectionChanged:function(data, rows){
          	$("#select-stats span").text(data.length);
          },
        });

        document.getElementById("clickMe").onclick = function myFunction() {
            table.addRow({name:"Insert Name", cap_id:" ", age:" ", push_ups:" ", sit_ups:" ", mile_run:" ", pacer_test:" ", sit_reach:" "});
        }
        document.getElementById("clickMe1").onclick = function Save() {
          var data1 = table.getData();
          data1 = JSON.stringify(data1);

          $.ajax({
            url: '../includes/sqmem_get-data.php',
            data: 'myData=' + data1 ,
            success: function(data) {
            }
          });
        }
        document.getElementById("clickMe2").onclick = function finish() {
          var data1 = table.getData();
          data1 = JSON.stringify(data1);

          $.ajax({
            url: '../includes/sqmem_get-data.php',
            data: {myData: data1, stuff: "1" },
            success: function(data) {
              window.location.replace("../protected/physical_testing.php");
            }
          });
        }
        document.getElementById("clickMe3").onclick = function recover() {
          $.ajax({
            url: '../includes/sqmem_get-data.php',
            data: 'recover=1',
            success: function(data) {
              var tabledata = data;
              table.clearData();
              table.addData(tabledata);
            }
          });
        }

      $.ajax({
        url: '../includes/sqmem_get-data.php',
        data: 'stuffmore=1',
        success: function(data) {
          var tabledata = data;
          table.addData(tabledata);
        }
      });
    </script>

    <?php if($hide == 1) {
      if(isset($errorMsg) && $errorMsg) {
        echo "<p style=\"color: red;\">*",htmlspecialchars($errorMsg),"</p>\n\n";
      }
      if(isset($message) && $message) {
        echo "<p style=\"color: green;\">*",htmlspecialchars($message),"</p>\n\n";
      }
      ?>
      <div class="newptrecord">
        <form method="post" action="../protected/physical_testing.php">
          <input type="submit" name="newrec" value="New PT Record">
        </form>
      </div>
      <br>
      <div class="dropdown">
        <button class="dropbtn">Options</button>
        <div class="dropdown-content">
          <a href="?name=1">Name</a>
          <a href="?capid=1">CAP ID</a>
          <a href="?date=1">Date</a>
        </div>
    <?php }?>
  </body>
</html>
