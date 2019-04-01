<?php
require "../includes/header.php";
?>

<html>
  <head>
    <title>CapHub MainPage</title>
  </head>
  <body>
    <div class="userid">
      <?php echo "Logged in as: " . $_SESSION['name'];?>
      <br>
      <?php echo "Privlage Level: " . $_SESSION['privlv'];?>
    </div>
    <div class="news">
      <h3>1.2:</h3>
      <div class="lists">
        <dl>
          <dt><b>Whats New:</b></dt>
            <ul>
              <li>Date selector for seaching by date</li>
              <li>A way to retire squadron members</li>
            </ul>
          <br>
          <br>
          <dt><b>Squadron page:</b></dt>
            <dd>Allows you to add members, remove members, search members</dd>
            <dd>and retire members.</dd>
            <br>
          <dt><b>Meetings page:</b></dt>
            <dd>Allows cadets to sign themselves in and out of meetings.</dd>
            <dd>Logs are searchable by date, CAP ID and name.</dd>
            <br>
          <dt><b>PT page:</b></dt>
            <dd>Members can now enter PT data without transfer of paper.</dd>
            <dd>Logs are searchable by name, CAP ID and date.</dd>
          <dt><b>Comms page:</b></dt>
            <dd>keep track of radios that are checked out or in.</dd>
            <dd>Lets you know who has check them out.</dd>
        </dl>
        <br>
        <b>Top Navigation Bar:</b>
        <ul>
          <li>Help Page</li>
          <li>Log Out - Logs you out of website.</li>
        </ul>
      </div>
    </div>
  </body>
</html>
