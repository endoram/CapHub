<?php
if(isset($_POST['export'])){
  $query = $_POST['exportData'];
  $rowHeaders = array("First Name", "Last Name","CAPID", "Privilege Level");
  include "../includes/helpers.php";
  exportMe($query, $rowHeaders);
}
if(isset($_GET['export'])){
  session_start();
  $query = "SELECT first_name, last_name, cap_id, member_type FROM sq_members WHERE retire=0 AND FQSN='".$_SESSION['FQSN']."'";
  $rowHeaders = array("First Name", "Last Name","CAPID", "Member Type");
  include "../includes/helpers.php";
  exportMe($query, $rowHeaders);
}
if(isset($_POST['exportME'])){
  #Needs some more data changing to limit results to the correct output.
  $query = $_POST['exportData'];
  $rowHeaders = array("CAPID", "Name", "Date", "Member Type",);
  include "../includes/helpers.php";
  exportMe($query, $rowHeaders);
}

require "../includes/header.php";
require "../includes/config_m.php";

$_SESSION['table'] = 0;

if(isset($_GET['retire'])) {
  $_SESSION['table'] = 0;
  echo "Select all users to retire:";
  echo "<br>";
  echo '
    <button type="button" id="retire1">Retire Members</button>';
}
if(isset($_GET['retired'])) {
  $_SESSION['table'] = 11;
  echo "Select all users to bring back to active status:";
  echo "<br>";
  echo '
    <button type="button" id="retire1">Revive Members</button>';
}
if(isset($_GET['addmember'])) {
  header("Location: ../includes/addmember.php");
}


if(isset($_POST['sent'])) {
  $queryHeaders = array("first_name", "last_name", "cap_id", "privlage_level");
  $displayHeaders = array("ID", "First Name", "Last Name","CAPID", "Privilege Level");
  require "../includes/helpers.php";
  queryCreate($_POST['sent'], $_POST['query'], $_POST['page'], $displayHeaders, $queryHeaders);
}

$page = "../protected/sqmembers.php";
$queryFirst = "SELECT first_name, last_name, cap_id, privlage_level FROM sq_members WHERE FQSN='".$_SESSION['FQSN']."' && ";
 // require "../includes/helpers.php";
//Detects whitch one to display when searching
if(isset($_GET['capid'])) {
  $data = "CAP ID:";
  require "../includes/helpers.php";
  searchMe($data, $queryFirst, $page);
}
if(isset($_GET['firstname'])) {
  $data = "Firstname:";
  require "../includes/helpers.php";
  searchMe($data, $queryFirst, $page);
}
if(isset($_GET['lastname'])) {
  $data = "Lastname:";
  require "../includes/helpers.php";
  searchMe($data, $queryFirst, $page);
}
if(isset($_GET['priv'])) {
  $data = "Privilege Level:";
  require "../includes/helpers.php";
  searchMe($data, $queryFirst, $page);
}

if (isset($_GET['statistics'])) {
  include "../includes/helpers.php";
  timeZone();
  
  $query = "SELECT * FROM meeting_nights WHERE meeting_nights.ID IN (
  SELECT max(meeting_nights.ID) AS max_id FROM meeting_nights 
  INNER JOIN sq_members ON meeting_nights.cap_id = sq_members.cap_id 
  WHERE date <= '".date("Y-m-d")."' && sq_members.FQSN='".$_SESSION['FQSN']."' && sq_members.retire=0 GROUP BY sq_members.cap_id)
  ORDER BY meeting_nights.member_type";

  $searchDate = date("Y-m-d", strtotime("-30 days"));

  echo "<h4 style='text-align: center;'>The following cadets and seniors have not signed into a meeting within the last 30 days</h4>";
  require '../includes/config_m.php';
  echo '<div class="sqsearch">
    <br>
    <table>
      <colgroup>
        <col span="4" style="background-color:lightgrey">
      </colgroup>
      <tr>
      <th>CAPID</th>
      <th>Name</th>
      <th>Date</th>
      <th>Member Type</th>
      </tr>';

  $result = $conn->query($query);
  if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
      $date = $row['date'];
      if ($date < $searchDate) {
        echo "<tr>";
        echo "<td>".$row['cap_id']."</td>";
        echo "<td>".$row['name']."</td>";
        echo "<td>".$row['date']."</td>";
        echo "<td>".$row['member_type']."</td>";
        echo "</tr>";
      }
    }
  }
  echo "</table>";
  #echo '
 #       <form action="sqmembers.php" method="post">
  #          <input type="submit" name="exportME" value="Export"/>
  #          <input type="hidden" name="exportData" value="' . $query . '"/>
  #      </form>
  #      ';
}
?>

<script src="../libs/tabulator/jquery-3.2.1.js"></script>
<script src="../libs/tabulator/jquery-ui.js"></script>
<link href="../libs/tabulator/tabulator.min.css" rel="stylesheet"></script>
<script src="../libs/tabulator/tabulator.min.js"></script>

<script>
function openForm() {
    document.getElementById("myForm").style.display = "block";
}

function closeForm() {
    document.getElementById("myForm").style.display = "none";
}
</script>


<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>CapHub Squadron Members</title>
  </head>
  <body>
    <br><br>
    <div class="row p-3">
      <div class="container-fluid p-1">
        <div class="leftside">
          <div class="sqmenubar">
            <ul>
              <li><a href="?export">Export</a><li>
              <li><a href="../includes/addmember.php">Add Member</a><li>
              <li><a href="../includes/update.php">Update Member</a></li>
              <li><a href="?retire">Retire Member</a><li>
              <li><a href="?cadet_coc">Cadet COC</a><li>
              <li><a href="?statistics">Statistics</li>
            </ul>
            <div class="dropdown">
              <ul><li><button class="sqmenubutton">Search</button></li></ul>
              <div class="dropdown-content">
                <p>Search by:</p>
                <a href="?firstname=1">First Name</a>
                <a href="?lastname=1">Last Name</a>
                <a href="?capid">CAP ID</a>
                <a href="?priv">Privlage</a>
                <a href="?retired">Retired</a>
              </div>
            </div>
          </div>
        </div>
        <div class="middle">
          <?if(isset($_GET['cadet_coc'])){
            require '../includes/orgChart.php';
          }?>
          <div id="example-table"></div>
          <script>
          function reqListener () {
            console.log(this.responseText);
          }
          var oReq = new XMLHttpRequest();
          oReq.onload = function() {
            var tabledata = this.responseText;

            var table = new Tabulator("#example-table", {
              layout:"fitColumns",
              fitColumns:true,
              selectable:true,
              columns:[
                {title:"First Name", field:"first_name", sorter:"string"},
                {title:"Last Name", field:"last_name", sorter:"string"},
                {title:"CAP ID", field:"cap_id", sorter:"string"},
                {title:"Member Type", field:"member_type", sorter:"string"},
              ],
              rowSelectionChanged:function(data, rows){
                $("#select-stats span").text(data.length);
              },
            });
            table.setData(tabledata);

            document.getElementById("retire1").onclick = function retire () {
              var selectedData = table.getSelectedData();
              var size = Object.keys(selectedData).length;

              var i, data;
              for (i = 0; i < size; i ++) {
                data += selectedData[i].cap_id + ",";
              }

              data = data.substring(0, data.length - 1);
              data = data.replace("undefined", "");

              $.ajax({
                url: '../includes/sqmem_get-data.php',
                data: 'retired1=' + data,
                success: function(data) {
                  window.location.replace("../protected/sqmembers.php");
                }
              });
            }
          };
          oReq.open("get", "../includes/sqmem_get-data.php", true);
          oReq.send();
          </script>
        </div>
      </div>
    </div>
  </body>
  <?php
    require "../includes/footer.php";
  ?>
</html>
