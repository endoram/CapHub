<?php
require "../includes/header.php";
$_SESSION['table'] = 10;


if(isset($_GET['ribbions'])){
  $_SESSION['table'] = 12;
}
if(isset($_GET['insignia'])){
  $_SESSION['table'] = 13;
}
if(isset($_GET['blues'])){
  $_SESSION['table'] = 14;
}
if(isset($_GET['abus'])){
  $_SESSION['table'] = 15;
}


if(isset($_GET['additems']) or isset($_GET['removeitems'])){
  echo '<div class="form-popup" id="myForm">';
  echo '<form method="post" action="../includes/sqmem_get-data.php" class="form-container">';
  if(isset($_GET['additems'])) {
    echo "<h4>Add Item:</h4>";
    editdescription();
    edittype();
    size();
    qty();
    location();
  }
  else {
    echo "<h4>Remove Item:</h4>";
    edittype();
    editdescription();
    size();

  }

  if(isset($_GET['additems'])) {echo '<button type="submit" value="something" name="sent" class="btn">Submit</button>';}
  else {echo '<button type="submit" value="something" name="sentremove" class="btn">Submit</button>';}
  echo '<button type="button" class="btn cancel" onclick="closeForm()">Close</button>';
  echo '</form>';
  echo '</div>';
}



function qty() {
  echo '<label for="input"><b>      Quantity:   </b></label>';
  echo "<select name='editqty'>";
  for ($x = 0; $x <= 25; $x++) {
    echo "<option value=" . $x . ">" . $x . "</option>";
  }
  echo '</select>';
}

function size() {
  echo '<label for="input"><b>      Size:   </b></label>';
  echo '<input type="text" name="size" required>';
}


function location() {
  echo '<label for="input"><b>      Location:   </b></label>';
  echo '<input type="text" name="location" required>';
}

function editdescription() {
  if(isset($_GET['additems'])) {
    echo '<label for="input"><b>      Description:   </b></label>';
    echo '<input type="text" name="description" required>';
  }
  if(isset($_GET['removeitems'])) {
    echo '<label for="input"><b>      Description:   </b></label>';
    require "../squadrons/" . $_SESSION['squad'] . "/config_m.php";
    $conn = new mysqli($mysql_host, $mysql_user, $mysql_password, $mysql_database) or die("Database Connection Failed : " . mysql_error());

    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }
    $query = "SELECT description FROM inventory";
    if(isset($_GET['abu'])) {$query = "SELECT description FROM inventory WHERE type=abu";}
    if(isset($_GET['blues'])) {$query = "SELECT description FROM inventory WHERE type=blues";}
    if(isset($_GET['ribbion'])) {$query = "SELECT description FROM inventory WHERE type=ribbion";}
    if(isset($_GET['insignia'])) {$query = "SELECT description FROM inventory WHERE type=insignia";}
    $result = $conn->query($query);

    echo "<select name='edittype' id='edittype'>";
    if ($result->num_rows > 0) {
      while($row = $result->fetch_assoc()) {
        echo $row['description'];
        echo "<option value=" . "inventory.php?" . $row['description'] . ">" . $row['description'] . "</option>";
      }
      $conn->close();
    }
    else {
      echo "<option value='No Results'>No Results</option>";
      $conn->close();
    }
    echo '</select>';
  }
}



function edittype() {
  echo '<label for="input"><b>      Type:   </b></label>';
  echo '
    <select name="type" id="type">
      <option value=ribbion>Ribbions</option>
      <option value=insignia>Insignia</option>
      <option value=abu>ABUs</option>
      <option value=blues>Blues</option>
    </select>
  ';
}
?>

<script>

$(document).ready(function() {

  $("#type").change(function() {

    var el = $(this);

    if (el.val() === "Insignia") {
      $("#edittype").append("   <option>SHIPPED</option>");
    } else if (el.val() === "ribbion") {
      $("#edittype option:last-child").remove();
    }
  });

});


function openForm() {
    document.getElementById("myForm").style.display = "block";
}

function closeForm() {
    document.getElementById("myForm").style.display = "none";
}
</script>


<script src="../libs/tabulator/jquery-3.2.1.js"></script>
<script src="../libs/tabulator/jquery-ui.js"></script>
<link href="../libs/tabulator/tabulator.min.css" rel="stylesheet"></script>
<script src="../libs/tabulator/tabulator.min.js"></script>

<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>CapHub Inventory</title>
  </head>
  <body>
    <div class="row">
      <div class="leftside">
        <div class="sqmenubar">
          <ul>
            <li><a href="?ribbions">Ribbions</a></li>
            <li><a href="?insignia">Insignia</a></li>
            <li><a href="?blues">Blues</a></li>
            <li><a href="?abus">ABUs</a></li>
            <li><a href="?updateqty">Update Quantity</a></li>
            <li><a href="?additems">Add Items</a></li>
            <li><a href="?removeitems">Remove Items</a></li>
          </ul>
        </div>
      </div>
      <div class="middle">
        <form method="post" action="../includes/sqmem_get-data.php" accept-charset="UTF-8">
          <input type="submit" value="Search">
          <select name="description">
            <?php
              require "../squadrons/" . $_SESSION['squad'] . "/config_m.php";
              $conn = new mysqli($mysql_host, $mysql_user, $mysql_password, $mysql_database) or die("Database Connection Failed : " . mysql_error());

              if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
              }

              switch($_SESSION['table']) {
                case 11: break;
                case 12: $query = "SELECT * FROM inventory WHERE type='ribbion'"; break;
                case 13: $query = "SELECT * FROM inventory WHERE type='insignia'"; break;
                case 14: $query = "SELECT * FROM inventory WHERE type='blues'"; break;
                case 15: $query = "SELECT * FROM inventory WHERE type='abu'"; break;
                default:
                  $_SESSION['table'] = 10;
                  $query = "SELECT description FROM inventory";
              }

              $result = $conn->query($query);

              if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                  echo $row['description'];
                  echo "<option value=" . $row['description'] . ">" . $row['description'] . "</option>";
                }
                $conn->close();
              }
              else {
                echo "<option value='No Results'>No Results</option>";
                $conn->close();
              }
            ?>
          </select>
        <script>
          funtion testme() {
            var oReq1 = new XMLHttpRequest();
            oReq1.onload = function() {
              var tabledata = this.responseText;
              var table = new Tabulator("#example-table", {
                data:tabledata,
                autoColumns:true,
              });

              table.setData(tabledata);
            };
            oReq1.open("post", "../includes/sqmem_get-data.php", true);
            oReq1.send();
          }
        </script>
        <div id="example-table"></div>
        <?php switch($_SESSION['table']) {
                case 11: break;
                case 12: break;
                case 13: break;
                case 14: break;
                case 15: break;
                default: $_SESSION['table'] = 10;
              }?>
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
              {title:"Description", field:"description", sorter:"string"},
              //{title:"Type", field:"type", sorter:"string"},
              {title:"Size", field:"size", sorter:"string"},
              {title:"Quantity", field:"quantity", sorter:"string"},
              {title:"Location", field:"location", sorter:"string"},
            ],
            rowSelectionChanged:function(data, rows){
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
