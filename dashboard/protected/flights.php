<?php
require '../includes/header.php';

if(isset($_POST['commander'])){
	require '../includes/config_m.php';
	$flights_list = array("Alpha", "Bravo", "Charlie", "Delta", "Echo", "Foxtrot", "Golf", "Hotel", "India", "Juliet", "Kilo", "Lima", "Mike", "November", "Oscar", "Papa", "Quebec", "Romeo", "Sierra", "Tango", "Uniform", "Victor", "Whiskey", "X-ray", "Yankee", "Zulu");
	$query = "SELECT flight_name, flight_num from cadet_coc WHERE FQSN='".$_SESSION['FQSN']."' ORDER BY id DESC LIMIT 1";
	$result = $conn->query($query);

	$comm = explode(",", $_POST['commander']);
	$serg = explode(",", $_POST['sergeant']);

    if ($result->num_rows > 0) {
    	while($row = $result->fetch_assoc()) {
    		if (strlen($row['flight_name']) <= 3) {
    			$flight = $flights_list[0];
    			$query = "INSERT INTO cadet_coc (flight_name, flight_num, FQSN, position, cap_id) VALUES ('$flight', 0, '".$_SESSION['FQSN']."', 'Flight Commander', $comm[1])";
    			$query1 = "INSERT INTO cadet_coc (flight_name, flight_num, FQSN, position, cap_id) VALUES ('$flight', 0, '".$_SESSION['FQSN']."', 'Flight Sergeant', $serg[1])";
    			$conn->query($query);
    			$conn->query($query1);
    		} else {
    			$flight = $flights_list[$row['flight_num']+1];
    			$query = "INSERT INTO cadet_coc (flight_name, flight_num, FQSN, position, cap_id) VALUES ('$flight', ".($row['flight_num']+1) .", '".$_SESSION['FQSN']."', 'Flight Commander', $comm[1])";
    			$query1 = "INSERT INTO cadet_coc (flight_name, flight_num, FQSN, position, cap_id) VALUES ('$flight', ".($row['flight_num']+1) .", '".$_SESSION['FQSN']."', 'Flight Sergeant', $serg[1])";
    			$conn->query($query);
    			$conn->query($query1);
    		}
    	}
  	}
}
?>


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
            	<?
              //Permission handleing for menu items
             	require '../includes/config_m.php';
            	$query = "SELECT position FROM cadet_coc WHERE FQSN='".$_SESSION['FQSN']."' AND cap_id='".$_SESSION['capid']."'";
				$result = $conn->query($query);
				$listPositions = array('Cadet Commander', 'CDC for Operations', 'CDC for Support', );
				if ($result->num_rows > 0) {
			    	while($row = $result->fetch_assoc()) {
			      		if (in_array($row['position'], $listPositions)) {
							echo '<li><a href="?newflight">New Flight</a><li>';
							break;
						  }
			    	}
			  	}
             	?>
          </div>
        </div>
        <div class="middle">
        	<?php
        	if(isset($_GET['newflight'])){
        		$flights_list = array("Alpha", "Bravo", "Charlie", "Delta", "Echo", "Foxtrot", "Golf", "Hotel", "India", "Juliet", "Kilo", "Lima", "Mike", "November", "Oscar", "Papa", "Quebec", "Romeo", "Sierra", "Tango", "Uniform", "Victor", "Whiskey", "X-ray", "Yankee", "Zulu");
				$query = "SELECT flight_name, flight_num from cadet_coc WHERE FQSN='".$_SESSION['FQSN']."' ORDER BY id DESC LIMIT 1";
				$result = $conn->query($query);
                if ($result->num_rows > 0) {
                	while($row = $result->fetch_assoc()) {
                		if (strlen($row['flight_name']) <= 3) {
                			$flight = $flights_list[0];
                		} else {
                			$flight = $flights_list[$row['flight_num']+1];
                		}
                	}
              	}
				?>
				<div class="container mt-5">
				    <h4>Creating  
              <select class="form-control" name="commander">
                  <?
                    foreach ($flights_list as $key => $value) {
                      echo "<option value='".$key.">". $key . "</option>";
                      echo "<option value='".$value.">". $value . "</option>";
                    }
                  ?>
                  </select> Flight: </h4>
				    <div class="row mt-4">
				      <div class="col-md-4">
				      	<p>Flight Commander:</p>
				      	<form method="post" action="flights.php" accept-charset="UTF-8">
					        <select class="form-control" name="commander">
					        <?
					          $query1 = "SELECT first_name, last_name, cap_id FROM sq_members WHERE member_type='cadet' AND retire=0 AND FQSN='".$_SESSION['FQSN']."'";
			                  $result1 = $conn->query($query1);
			                  if ($result1->num_rows > 0) {
			                    while($row1 = $result1->fetch_assoc()) {
			                      $name = $row1['first_name'] . " " . $row1['last_name'];
			                      echo "<option value='".$flight." Flight Commander,". $row1['cap_id'] . "'>" . $name . "</option>";
			                    }
			                  }
			                  ?>
					        </select>
					      </div>
					      <div class="col-md-4">
					      	<p>Flight Sergeant:</p>
					        <select class="form-control" name="sergeant">
					         <?
					          $query1 = "SELECT first_name, last_name, cap_id FROM sq_members WHERE member_type='cadet' AND retire=0 AND FQSN='".$_SESSION['FQSN']."'";
			                  $result1 = $conn->query($query1);
			                  if ($result1->num_rows > 0) {
			                    while($row1 = $result1->fetch_assoc()) {
			                      $name = $row1['first_name'] . " " . $row1['last_name'];
			                      echo "<option value='".$flight." Flight Sergeant,". $row1['cap_id'] . "'>" . $name . "</option>";
			                    }
			                  }
			                  ?>
					        </select>
				      	  </div>
					</div>
					<div class="col-md-4">
						<button type="submit" value="Save">Save</button>
					</div>
					</form>
				</div>
				<?
			}

        	?>
        	<div class="radiotable">
            <br>
            <?php
            $flights_list = array("Alpha", "Bravo", "Charlie", "Delta", "Echo", "Foxtrot", "Golf", "Hotel", "India", "Juliet", "Kilo", "Lima", "Mike", "November", "Oscar", "Papa", "Quebec", "Romeo", "Sierra", "Tango", "Uniform", "Victor", "Whiskey", "X-ray", "Yankee", "Zulu");

            require "../includes/config_m.php";
            foreach ($flights_list as $key => $value) {
            	$query = "SELECT * FROM sq_members WHERE FQSN='".$_SESSION['FQSN']."' AND flight_name='".$value."'";
            	echo "<br>";
            	$result = $conn->query($query);
            	if ($result->num_rows > 0) {
                	echo '
                  <table>
                    <colgroup>
                      <col span="7" style="background-color:lightgrey">
                    </colgroup>
                    <tr>
                      <th>First Name</th>
                      <th>Last Name</th>
                      <th>Last Signed In</th>
                      <th>Element</th>
                    </tr>';
                    while($row = $result->fetch_assoc()) {
                    	$query = "SELECT date FROM meeting_nights WHERE FQSN='".$_SESSION['FQSN']."' AND cap_id='".$row['cap_id']."' ORDER BY id DESC LIMIT 1 ";
                    	$result1 = $conn->query($query);
                    	echo "<h4>" . $row['flight_name'] . "</h4>";
                    	echo "<tr>";
               			echo "
                  <td>" . $row["first_name"] . "</td>
                  <td>" . $row["last_name"] . "</td>";
                    	if ($result1->num_rows > 0) {
 							while($row1 = $result1->fetch_assoc()) {
 								echo "<td>" . $row1["date"] . "</td>";
 							}
                    	}
                    	echo "<td>" . $row["element"] . "</td>";
              		}
              		echo "</tr> </table>";
            	}
            }
            $conn->close();?>
          </div>
        </div>
      </div>
    </div>
  </body>
  <?php
    require "../includes/footer.php";
  ?>
</html>