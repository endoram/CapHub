<?php
//require "../includes/control_access.php";

/* The code block `if (isset(['legal']))` checks if the `legal` parameter is present in the URL
query string. If it is present, it calls the `showLegal()` function and then echoes an HTML code
block. */
if (isset($_GET['legal'])) {
	showLegal();
	echo "<html>
  <head>
    <!-- Google Tag Manager -->
    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
    new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
    j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
    'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
    })(window,document,'script','dataLayer','GTM-5X833TX');</script>
    <!-- End Google Tag Manager -->
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <link rel='stylesheet' type='text/css' href='style.css'>
  </head>
  <body>
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src='https://www.googletagmanager.com/ns.html?id=GTM-5X833TX'
    height='0' width='0' style='display:none;visibility:hidden'></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->
  </body>
</html>";
}

/* This code block is checking if the `['capid']` variable is set in the `` superglobal array. If
it is set, it connects to the database using the `config_m.php` file and executes an SQL query to
update the `TOS` (Terms of Service) column in the `sq_members` table. It sets the value of `TOS` to
1 (indicating that the user has accepted the terms of service) where the `cap_id` matches the value
of `['capid']`. */
if(isset($_POST['capid'])) {
	require '../includes/config_m.php';
	$query = "UPDATE sq_members SET TOS=1 WHERE cap_id='" . $_POST['capid'] . "'";
	$result = $conn->query($query);
}

/**
 * The function "showLegal" displays a disclaimer overlay on a website and allows the user to accept it
 * by clicking anywhere on the overlay.
 */
function showLegal() {
	echo '
	<div id="overlay" onclick="off()">
	<div class="container-fluid">
	<div class="overflow-auto vertical-center" 
  style="max-width: 350; max-height: 500; align-items: center;">
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
				<a href="../protected/main.php">Continue</a>
				</div>
			</div>
			</div>
		</div> 
	';
}

/* This code block is checking if the `['capid']` variable is set. If it is set, it connects
to the database using the `config_m.php` file and executes a query to select the `TOS` (Terms of
Service) value from the `sq_members` table where the `cap_id` matches the `['capid']`
value. */
if (isset($_SESSION['capid'])){
	require '../includes/config_m.php';
	$query = "SELECT TOS FROM sq_members WHERE cap_id='" . $_SESSION['capid'] . "'";

	$result = $conn->query($query);
	if ($result->num_rows > 0) {
		while($row = $result->fetch_assoc()) {
			if ($row['TOS'] == 0) {
				showLegal();
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
/* The `off()` function is a JavaScript function that is called when the user clicks anywhere on the
overlay. It hides the overlay by setting the `display` property of the overlay element to "none". */
	function off() {
		document.getElementById("overlay").style.display = "none";
		jQuery.ajax({
			type: "POST",
			url: '../includes/legal.php',
			data: 'capid='+<?php echo $_SESSION['capid'];?>,
		});
	}
</script>