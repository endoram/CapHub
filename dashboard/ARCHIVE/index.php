<?php
if(empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] == "off"){
    $redirect = 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    header('HTTP/1.1 301 Moved Permanently');
    header('Location: ' . $redirect);
    exit();
}
?>

<html>
  <head>
    <link rel="stylesheet" type="text/css" href="style.css">
  </head>
  <body>
  </body>
</html>

<?php

echo "<title>CapHub</title>";
echo "<center><h1>This page is currently in development.</h1>";
echo "<br>";
echo "<h2>Check back soon.<h2>";
echo "</center>";

?>
