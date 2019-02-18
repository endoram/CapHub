<?php
require "../includes/header.php";
require "../includes/config_m.php";
$_SESSION["table"] = 1;

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
?>

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
  if(isset($_POST['input'])) {
    echo  '<div id="life">
        <input id="clickMe" type="button" value="New Row" onclick="myFunction();" />
        <input id="clickMe1" type="button" value="save" onclick="myFunction();" />
        </div>';
    echo '<div id="example-table"></div>';
    $hide = 0;
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
      function reqListener () {
        console.log(this.responseText);
      }
      var oReq = new XMLHttpRequest();
      oReq.onload = function() {
        var tabledata = this.responseText;
      //  alert(tabledata);

        var table = new Tabulator("#example-table", {
          layout:"fitColumns",
          fitColumns:true,
          addRowPos:"top",
          columns:[
      	    {title:"Name", field:"name", editor:"input", width:200},
            {title:"Age", field:"age", editor:"input", width:150},
      	    {title:"Push Ups", field:"push_ups", editor:"input", width:150},
      	    {title:"Sit Ups", field:"sit_ups", editor:"input", width:150},
      	    {title:"Mile Run", field:"mile_run", editor:"input", width:200},
      	    {title:"Pacer Test", field:"pacer_test", editor:"input", width:200},
      	    {title:"Sit & Reach", field:"sit_reach", editor:"input", width:250},
          ],
          rowSelectionChanged:function(data, rows){
              //update selected row counter on selection change
          	$("#select-stats span").text(data.length);
          },
          dataEdited:function(data){
            var data1 = table.getData();
            console.log(data1);
          },
        });

        document.getElementById("clickMe").onclick = function myFunction() {
            table.addRow({});
        }
        document.getElementById("clickMe1").onclick = function save() {
          var data1 = table.getData();
          console.log(data1);
          alert(data1);
        }

        table.setData(tabledata);
    };
    oReq.open("get", "../includes/sqmem_get-data.php", true);
    oReq.send();
    </script>

    <?php if($hide == 1) {?>
      <div class="newptrecord">
        <form method="post" action="../protected/physical_testing.php">
          <input type="submit" name="input" value="New PT Record">
        </form>
      </div>
      <br>
      <div class="dropdown">
        <button class="dropbtn">Search Opt.</button>
        <div class="dropdown-content">
          <p>Search by:</p>
          <a href="?firstname=1">First Name</a>
          <a href="?lastname=1">Last Name</a>
          <a href="?capid">CAP ID</a>
          <a href="?priv">Privlage</a>
        </div>
      </div>
    <?php }?>

  </body>
</html>
