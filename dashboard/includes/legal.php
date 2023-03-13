<?php
//require "../includes/control_access.php";

if(isset($_POST['capid'])) {
	require '../includes/config_m.php';
	$query = "UPDATE sq_members SET TOS=1 WHERE cap_id='" . $_POST['capid'] . "'";
	$result = $conn->query($query);
}

if (isset($_SESSION['capid'])){
	require '../includes/config_m.php';
	$query = "SELECT TOS FROM sq_members WHERE cap_id='" . $_SESSION['capid'] . "'";

	$result = $conn->query($query);
	if ($result->num_rows > 0) {
		while($row = $result->fetch_assoc()) {
			if ($row['TOS'] == 0) {
				echo '<div id="overlay" onclick="off()">
				<div id="legalText">
				<h2>By continuing to use this website, you acknowledge that you have read, understood, and agreed to the following disclaimer.
				<br>
				</h2><h5>(Click anywhere to accept)</h5>
		
				The information provided on this website is for general informational purposes only. While we strive to provide accurate and up-to-date 
				information, we make no representations or warranties of any kind, express or implied, about the completeness, accuracy, reliability,
				suitability, or availability with respect to the website or the information, products, services, or related graphics contained on the
				website for any purpose. Any reliance you place on such information is therefore strictly at your own risk.
				<br><br>
				In no event will we be liable for any loss or damage including without limitation, indirect or consequential loss or damage, or any loss or 
				damage whatsoever arising from loss of data or profits arising out of or in connection with the use of this website.
				<br><br>
				Through this website, you may be able to link to other websites which are not under our control. We have no control over the nature,
				content, and availability of those sites. The inclusion of any links does not necessarily imply a recommendation or endorse the views
				expressed within them.
				<br><br>
				Every effort is made to keep the website up and running smoothly. However, we take no responsibility for and will not be liable for the
				website being temporarily unavailable due to technical issues beyond our control.
				<br><br>
				CAPhub is not affiliated with Civil Air Patrol (CAP) in any way and has no connection or association with eServices. <br>
				CAPhub is an independent organization that operates separately from CAP and eServices. 
				<br><br>
				Any references made to CAP or eServices on this website are for informational purposes only and do not imply endorsement or sponsorship by CAP or eServices. 
				<br>
				CAPhub Copyright &#169;'.date('Y').'
				</div>
				</div> 
	';
			}

		}
		$conn->close();
	}
	else {
		echo "<script>alert('No Results Found');</script>";
		$conn->close();
	}
}
?>

<script src="../libs/tabulator/jquery-3.2.1.js"></script>
<script src="../libs/tabulator/jquery-ui.js"></script>
<script type="text/javascript">
	function off() {
		document.getElementById("overlay").style.display = "none";
		jQuery.ajax({
			type: "POST",
			url: '../includes/legal.php',
			data: 'capid='+<?php echo $_SESSION['capid'];?>,
		});
	}
</script>