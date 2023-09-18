<?php
/* The code below is a PHP code snippet that generates the HTML markup for a footer section of a
web page. It includes a container with a dark blue background and white text. Inside the container,
there is a text section with a disclaimer about the affiliation of CAPhub with Civil Air Patrol
(CAP) and eServices. It also includes a link to view the full disclaimer. Below the text section,
there is a copyright notice with the current year and a link to the CAPhub website. */
?>

<!-- Footer -->
<div class="container-fluid p-3">
<footer class="p-3 mb-2" style="background-color: darkblue; color:white;">
  <!-- Grid container -->
  <div class="container-fulid p-0 small center">
    <!-- Section: Text -->
	  <p class="small text-center">CAPhub is not affiliated with Civil Air Patrol (CAP) in any way and 
	has no connection or association with eServices. CAPhub is an 
	independent organization that operates separately from CAP and 
	eServices. Any references made to CAP or eServices on this website are 
	for informational purposes only and do not imply endorsement or 
	sponsorship by CAP or eServices. Click <a style="color: white;" href="../includes/legal.php?legal" value="legal">here</a> to view full disclaimer.</p>
	</div>
    <!-- Section: Text -->
  
  <!-- Grid container -->

  <!-- Copyright -->
  <div class="text-center p-0" style="background-color: rgba(0, 0, 0, 0.2);">
    Copyright &#169; <?php echo date('Y');?> CAPhub
    <a class="text-white" href="https://mdbootstrap.com/">caphub.org</a>
  </div>
  <!-- Copyright -->
</footer>
</div>
<!-- Footer -->