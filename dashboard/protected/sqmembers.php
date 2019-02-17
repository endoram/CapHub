<?php
require "../includes/header.php";
require "../includes/config_m.php";

$_SESSION['table'] = 0;

if(isset($_GET['export'])){

}
if(isset($_GET['rmuser'])) {
  echo "Enter CAPID of user to remove:";
  echo "<br>";
  echo '
    <div class="deluser">
      <form action="sqmembers.php">
        <input type="text" name="capidrm">
        <input type="submit" value="Remove Users" name="rmuser1">
      </form>
    </div>';
}
if(isset($_GET['rmuser1'])) {
  require "../includes/config_m.php";
  $query = "DELETE FROM `sq_members` WHERE cap_id=" . $_GET['capidrm'];
  $conn->query($query);
  $conn->close();
}

if(isset($_GET['addmember'])) {
  header("Location: ../includes/addmember.php");
}

if(isset($_GET['firstname'])) {
  $data = "Firstname:"; handleit($data);
}
if(isset($_GET['lastname'])) {
  $data = "Lastname:"; handleit($data);
}
if(isset($_GET['capid'])) {
  $data = "CAP ID:"; handleit($data);
}
if(isset($_GET['priv'])) {
  $data = "Privlage Level:"; handleit($data);
}

function handleit($data) {
  unset($_GET['firstname, lastname, capid, priv']);

  echo '<div class="form-popup" id="myForm">';
  echo '<form method="post" action="sqmembers.php" class="form-container">';

  echo '<label for="input"><b>' . $data . '</b></label>';
  echo '<input type="text" name="input" required>';

  echo '<button type="submit" value="' . $data . '" name="sent" class="btn">Submit</button>';
  echo '<button type="button" class="btn cancel" onclick="closeForm()">Close</button>';
  echo '</form>';
  echo '</div>';
}

function submit() {
  if($_POST['sent'] == "Firstname:") {
    $firstname = $_POST['input'];
    if (preg_match('/[^A-Za-z]/', $firstname)) {
      echo "<p style='color: red'>Names don't have numbers in them - try again<p>";
    }
    else {
      $data = "first_name LIKE '" . $_POST['input'] . "%'";
      queryit($data);
    }
  }
  if($_POST['sent'] == "Lastname:") {
    $lastname = $_POST['input'];
    if (preg_match('/[^A-Za-z]/', $lastname)) {
      echo "<p style='color: red'>Names don't have numbers in them - try again<p>";
    }
    else {
      $data = "last_name LIKE '" . $_POST['input'] . "%'";
      queryit($data);
    }
  }
  if($_POST['sent'] == "CAP ID:") {
    $capid = $_POST['input'];
    if(!is_numeric($capid)) {
      echo "<p style='color: red'>Invalid Cap ID<p>";
    }
    else {
      $data = "cap_id LIKE '" . $_POST['input'] . "%'";
      queryit($data);
    }
  }
  if($_POST['sent'] == "Privlage Level:") {
    $priv = $_POST['input'];
    if(!is_numeric($priv)) {
      echo "<p style='color: red'>Invalid Privlage Level<p>";
    }
    else {
      $data = "privlage_level=" . $_POST['input'];
      queryit($data);
    }
  }
}

function queryit($data) {
  require "../includes/config_m.php";
  $query = "SELECT * FROM sq_members WHERE " . $data;
  $result = $conn->query($query);

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
        $rm_capid = $row["cap_id"];
    }
  }
  else {
    echo "<h4 style='color: darkyellow'>No Reults found</h4>";
    $conn->close();
  }
  $conn->close();
  echo "</table></div></div>";
}

if(isset($_POST['sent'])) {submit();}
?>

<script src="../libs/jquery-3.2.1.js"></script>
<script src="../libs/jquery-ui.js"></script>
<link href="../libs/tabulator.min.css" rel="stylesheet"></script>
<script src="../libs/tabulator.min.js"></script>

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
    <title>CapHub Squadron Members</title>
  </head>
  <body>
    <div class="dropdown">
      <p>Search by:</p>
      <button class="dropbtn">Options</button>
      <div class="dropdown-content">
        <p>Search by:</p>
        <a href="?firstname=1">First Name</a>
        <a href="?lastname=1">Last Name</a>
        <a href="?capid">CAP ID</a>
        <a href="?priv">Privlage</a>
      </div>
    </div>
    <br>
    <div class="row">
      <div class="leftside">
        <div class="sqmenubar">
          <ul>
            <li><a href="?export">Export</a><li>
            <li><a href="../includes/addmember.php">Add Member</a><li>
            <li><a href="../includes/update.php">Update Member</a></li>
            <li><a href="?rmuser=1">Remove Member</a><li>
          </ul>
        </div>
      </div>
      <div class="middle">
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
              {title:"CAP ID", field:"cap_id", sorter:"string"},
              {title:"First Name", field:"first_name", sorter:"string"},
              {title:"Last Name", field:"last_name", sorter:"string"},
            ],
            rowSelectionChanged:function(data, rows){
                //update selected row counter on selection change
              $("#select-stats span").text(data.length);
            },
          });
        table.setData(tabledata);
        };
        oReq.open("get", "../includes/sqmem_get-data.php", true);
        oReq.send();
        </script>
      </div>
    </div>
  </body>
</html>
