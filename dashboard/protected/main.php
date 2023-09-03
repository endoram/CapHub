<?php
require "../includes/header.php";
?>

<html>
  <head>
    <title>CapHub MainPage</title>
  </head>
  <body>
    <div class="container-fluid p-4">
      <div class="row">
        <div class="col">
          <div class="news">
            <h3 name="version">CAPhub v1.8B</h3>
            <dl>
              <dt><b>Change Log-</b>August 31st, 2023</dt>
                <ul>
                  <li>Added new statistics under squadron.</li>
                  <li>Removed Kiosk Mode... For now.</li>
                </ul>
              <br />
              <dt><b>In Development:</b></dt>
              <ul>
                <li>Fixing Kiosk Mode</li>
                <li>Flight Management Tools</li>
                <li>Custom search and export feature</li>
              </ul>
              <br />
              <dt><b>Squadron</b></dt>
                <dd>Allows you to add, remove, search, and retire members.</dd>
                <br />
              <dt><b>Meetings</b></dt>
                <dd>Allows cadets to sign themselves in and out of meetings.</dd>
                <dd>Logs are searchable by date, CAP ID, and name.</dd>
                <br />
              <dt><b>Comms</b></dt>
                <dd>Keeps track of radios that are checked out or in.</dd>
                <dd>Lets you know who has checked them out.</dd>
                <br />
              <dt><b>PT</b></dt>
                <dd>Allows members to track PT tests.</dd>
                <dd>Logs are searchable by name, CAP ID, date and date range.</dd>
                <br />
              <dt><b>Help</b></dt>
                <dd>Let us know how we can make CAPhub better.</dd>
                <br />
              <dt><b>Log Out</b></dt>
                <dd>Logs you out of website.</dd>
            </dl>
          </div>
        </div>
      </div>
    </div>
  </body>
    <div class="container-fluid p-0">
    <?php
      require "../includes/footer.php";
    ?>
  </div>
</html>
