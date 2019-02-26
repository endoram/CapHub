<?php
require "../includes/header.php";
require "../includes/config_m.php";

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

if(isset($_POST['myData'])){
 $obj = $_POST['myData'];
 echo $obj;
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

    <?php if($hide == 1) {?>
      <div class="newptrecord">
        <form method="post" action="../protected/physical_testing.php">
          <input type="submit" name="input" value="New PT Record">
        </form>
      </div>
      <br>
      <div class="dropdown">
        <button class="dropbtn">Options</button>
        <div class="dropdown-content">
          <a href="?firstname=1">First Name</a>
          <a href="?lastname=1">Last Name</a>
          <a href="?capid">CAP ID</a>
          <a href="?priv">Privlage</a>
          <a href="?recover=1">Recover Last Save</a>
        </div>
    <?php }?>
  </body>
</html>
