<?php
require "../includes/header.php";
?>

<html>
  <head>
    <title>CapHub MainPage</title>
  </head>
  <body>
    <div class="news">
      <h3 name="version">CAPhub v1.6</h3>
      <div class="lists">
        <dl>
          <dt><b>Change Log:</b></dt>
            <ul>
              <li>Added PT testing page</li>
              <li>Added ability to un-retire members</li>
              <li>Added Date Range search</li>
              <li>Fixed export to .csv bug (DF-1.5.1)</li>
              <li>Moved search dropdown to left menu bar for Squadron page</li>
              <li>Raised Meeting sign-in box</li>
              <li>Switched search results to Last, First</li>
              <li>Added Settings page with timezone selector in Settings page</li>
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
          <dt><b>PT page:</b></dt>
            <dd>Allows members to track PT tests.</dd>
            <dd>Logs are searchable by name, CAP ID, date and date range.</dd>
            <br>
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
