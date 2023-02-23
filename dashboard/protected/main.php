<?php
require "../includes/header.php";
?>

<html>
  <head>
    <title>CapHub MainPage</title>
  </head>
  <body>
    <div class="news">
      <h3 name="version">CAPhub v1.5.5</h3>
      <div class="lists">
        <dl>
          <dt><b>Change Log:</b></dt>
            <ul>
              <li>Added ability to un-retire members</li>
              <li>Added Date Range search for Meetings</li>
              <li>Fixed export to .csv bug (DF-1.5.1)</li>
              <li>Updated password handling on back end</li>
              <li>Moved search dropdown to left menu bar for Squadron page</li>
              <li>Raised Meeting sign-in box</li>
              <li>Switched search results to Last, First for Meetings page</li>
              <li>Added Settings page with timezone selector in Main menu</li>
              <li>Reconfigured data handling with database</li>
              <li>CAP members from one squadron can now log into another squadron's meeting</li>
            </ul>
          <br>
          <dt><b>Squadron:</b></dt>
            <dd>Allows you to add, remove, search, and retire members.</dd>
            <br>
          <dt><b>Meetings:</b></dt>
            <dd>Allows cadets to sign themselves in and out of meetings.</dd>
            <dd>Logs are searchable by date, CAP ID, and name.</dd>
            <br>
          <dt><b>Comms:</b></dt>
            <dd>Keeps track of radios that are checked out or in.</dd>
            <dd>Lets you know who has checked them out.</dd>
            <br>
          <!--
          <dt><b>PT page:</b></dt>
            <dd>Members can now enter PT data without transfer of paper.</dd>
            <dd>Logs are searchable by name, CAP ID and date.</dd>
            <br>
          -->
        <dt><b>Top Navigation Bar:</b></dt>
        <ul>
          <li>Help Page - Report a Bug</li>
          <li>Log Out - Logs you out of website.</li>
        </ul>
        </dl>
      </div>
    </div>
  </body>
</html>
