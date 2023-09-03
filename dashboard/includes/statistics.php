<?php
  require "../includes/header.php";
  require "../includes/helpers.php";
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
	              <li><a href="?rankStats">Rank Statistics</a></li>
	              <li><a href="?signStats">Sign-In Statistics</a></li>
	            </ul>
	          </div>
	        </div>
	        <div class="middle p-2">
				<div class="col-xs-6">
		        	<?php
					if (isset($_POST['data'])) {
						$data = isset($_POST['data']) ? $_POST['data'] : '';
						$Lines = explode("\n", $data);
						$z = 0;

						$ranksHead = ["Airman Basic", "Airman", "Airman First Class", "Senior Airman", "Staff Sgt.", "Tech Sgt.", "Master Sgt.", "Senior Master Sgt.", "Chief Master Sgt.", "2dLt", "First Lt.", "Captain", "Major", "Lt Colonel", "Colonel"];
					 	$ranksUsed = ["CADET", "C/Amn", "C/A1C", "C/SrA", "C/SSgt", "C/TSgt", "C/MSgt", "C/SMSgt", "C/CMSgt", "C/2dLt", "C/1stLt", "C/Capt", "C/Maj", "C/LtCol", "C/Col"];

						function percentage($part, $whole) {
						    $Percentage = 100 * floatval($part) / floatval($whole);
						    return round($Percentage, 1);
						}
						?>
						<div class="table-responsive">
					        <hr>
					        <h2>Quick Overview</h2>
					        <table border="1">
					            <tr>
					                <th>Rank</th>
					                <th>Count</th>
					                <th>Percentage</th>
					            </tr>
						        <?php
									$counter = 0; $airman = 0; $sgt = 0; $officer = 0;
									$airmanCount = 0; $sgtCount = 0; $officerCount = 0;
									foreach ($ranksUsed as $x) {
									    $count = 0;
									    $y = 0;
									    foreach ($Lines as $line) {
									        $count++;
									        if (strpos($line, $x) !== false) {
									            $y++;
									        }
									    }
									    $percentage = percentage($y, $count);
									    if ($counter <= 3) {
											$airman = $airman + $percentage;
											$airmanCount = $airmanCount + $y;
									    }
									    elseif ($counter >= 4 && $counter <= 8) {
											$sgt = $sgt + $percentage;
											$sgtCount = $sgtCount + $y;
									    }
									    if ($counter >=9  && $counter <= 14) {
											$officer =  $officer + $percentage;
											$officerCount = $officerCount + $y;
									    }
									    $counter++;

						                echo "<tr>";
						              	echo "<td>$x</td>";
						               	echo "<td>$y/$count</td>";
						            	echo "<td>$percentage%</td>";
						            	echo "</tr>";
									}
							    ?>
							</table>
						</div>
						<br>
						<div class="table-responsive">
							<table border="1">
					            <tr>
					                <th>Rank</th>
					                <th>Count</th>
					                <th>Percentage</th>
					            </tr>
								<?php
									echo "<tr>";
						            	echo "<td>Amn</td>";
						                echo "<td>$airmanCount/$count</td>";
						                echo "<td>$airman%</td>";
						            echo "</tr>";
									echo "<tr>";
						                echo "<td>Sgt</td>";
						                echo "<td>$sgtCount/$count</td>";
						                echo "<td>$sgt%</td>";
						            echo "</tr>";
									echo "<tr>";
						                echo "<td>Officer</td>";
						                echo "<td>$officerCount/$count</td>";
						                echo "<td>$officer%</td>";
						            echo "</tr>";
								?>
							</table>
						</div>
						<br>

				        <hr>
				        <h2>Detailed List</h2>
				        <table border="1">
				            <tr>
								<th>CAP-ID</th>
				                <th>Rank</th>
				                <th>First Name</th>
				                <th>Last Name</th>
				            </tr>

				    <?php
							foreach ($ranksUsed as $x) {
							    $y = 0;
								echo "<tr>";
				                echo "<td colspan='4' style='background-color: lightblue;'><b>" . $ranksHead[$z] . "</b></td>";
				                echo "</tr>";

							    foreach ($Lines as $line) {
							        if (strpos($line, $x) !== false) {
							            $y++;
									    list($cadetNumber, $rank, $name) = explode(" ", $line, 3);
									    preg_match('/\b(\d+)\b/', $cadetNumber, $capids);
									    $rank = preg_replace('/\b\d+(\S+)\s+/', '', $cadetNumber);

									    $cadetData = explode(" ", $line);
									    $firstName = $cadetData[1];
									    $lastName = end($cadetData);

					                    echo "<tr>";
					                    echo "<td>$capids[0]</td>";
					                    echo "<td>$rank</td>";
					                    echo "<td>$firstName</td>";
										echo "<td>$lastName</td>";

										if ($_SESSION['privlv'] == 3) {
											$results = adduser($firstName, $lastName, $capids[0], 'cadet', '0', '');
											if ($results == "Done") {
												echo "Added Cadet: " . $capids[0] ." ". $firstName ." ".  $lastName . " cadet";
												echo "<br>";
											}
										}
					                    echo "</tr>";
							        }
							    }
							    $z++;
							}
						echo "</table>";
					}

		        	if (isset($_GET['rankStats'])) {
				  		echo '<h1>Cadet Rank Analysis</h1>
						    <form action="statistics.php" method="post">
						      <label for="data">1. Log into eServices</label><br/>
						      <label for="data">2. Click on cadets in your unit and copy all the CAPID and Rank/Name fields</label><br/>
						      <label for="data">3. Paste copied data and click Submit</label></br>
						      <textarea name="data" id="data" rows="10" cols="100"></textarea><br/>
						      <input type="submit" value="Submit"/>
						    </form>';
		        	}

					if (isset($_GET['signStats'])) {
					  timeZone();

 					  $query = "SELECT * FROM meeting_nights WHERE meeting_nights.ID IN (
					  SELECT max(meeting_nights.ID) AS max_id FROM meeting_nights 
					  INNER JOIN sq_members ON meeting_nights.cap_id = sq_members.cap_id 
					  WHERE date <= '".date("Y-m-d")."' && sq_members.FQSN='".$_SESSION['FQSN']."' && sq_members.retire=0 GROUP BY sq_members.cap_id)
					  ORDER BY meeting_nights.member_type";

					  $searchDate = date("Y-m-d", strtotime("-90 days"));

					  echo "<h4 style='text-align: center;'>The following cadets and seniors have not signed into a meeting within the last 90 days</h4>";
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
					  echo "</table><br>";


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
					  #      <form action="sqmembers.php" method="post">
					  #          <input type="submit" name="exportME" value="Export"/>
					  #          <input type="hidden" name="exportData" value="' . $query . '"/>
					  #      </form>
					  #      ';
					}
					?>
				</div>
			</div>
		</div>
        </div>
    </div>
  </body>
  <?php
  	require "../includes/footer.php";
  ?>
</html>
