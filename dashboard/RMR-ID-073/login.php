<?php
require "../header.php";
?>


<html>
  <head>
    <link rel="stylesheet" type="text/css" href="css/index.css">
  </head>
  <body>
    <div class="loginform">

      <form action="proc_login.php" method="get">
        <label for="EMIAL">Email:</label> <input type="text" id="EMAIL" name="email" align="right"><br>
        <label for="PASSWORD">Password:</label> <input type="password" id="PASSWORD" name="password" align="right"><br>
        <input type="submit" value="Login">
      </form>
  </div>
  </body>
</html>
