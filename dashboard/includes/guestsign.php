<?php
  session_start();
  require "control_access.php";

  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require "../includes/config_m.php";
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $name = $firstname . ' ' . $lastname;

    if(empty($_POST['phonenumber:'])) {
      $phonenumber = 0;
    } else {
      $phonenumber = $_POST['phonenumber:'];
    }

    $membertype = "visiter";
    $message = $name . " signed in";
    date_default_timezone_set("America/Denver");
    $date = date("Y/m/d");
    $time = date("H:i:s");

    if(isset($_POST["eventid"])) {
      $event_id = $_POST["eventid"];
      $query = "INSERT INTO events (event_id, date_in, first_name, last_name, time_in, member_type, cap_id) VALUES ($event_id, '$date', '$firstname', '$lastname', '$time', '$membertype', $phonenumber)";
      $conn->query($query);
      $conn->close();

      //echo $query;

      $_SESSION['message'] = "Thanks for signing in!";
      header("Location: ../protected/event_handle.php?eventid=$event_id");
    } else {
      $query = "INSERT INTO meeting_nights (date, name, time_in, member_type, cap_id) VALUES ('$date', '$name', '$time', '$membertype', $phonenumber)";
      $conn->query($query);
      $conn->close();

    //  echo "Not Set";

      $_SESSION['message'] = "Thanks for signing in!";
      header("Location: ../protected/meeting_nights.php");
    }


    die;
  }
?>

<style>
input {
  width: 30%;
  padding: 6px;
  border: 1px solid #ccc;
  border-radius: 4px;
  box-sizing: border-box;
  margin-top: 6px;
  margin-bottom: 16px;
}
</style>

<html>
  <head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../protected/style.css">
    <a href="../protected/main.php"><img src="../images/banner.png"></a>
    <title>CapHub Add Member</title>
  </head>
  <body>
    <div class="column">
      <div class="addmemberform">
        <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" accept-charset="UTF-8">
          <?php
          if(isset($errorMsg) && $errorMsg) {
            echo "<p style=\"color: red;\">*",htmlspecialchars($errorMsg),"</p>\n\n";
          }?>
          <label for="firstname">First name:</label> <input type="text" name="firstname" align="right" value="<?PHP if(isset($_POST['phonenumber:'])) echo htmlspecialchars($_POST['firstname']); ?>" required><br>
          <label for="lastname">Last name:</label> <input type="text" name="lastname" align="right" value="<?PHP if(isset($_POST['phonenumber:'])) echo htmlspecialchars($_POST['lastname']); ?>" required><br>
          <label for="phonenumber:">Phone Number:(9 Digit)</label> <input type="text" id="phonenumber:" name="phonenumber:" align="right" title="Must be a proper phone number" pattern="[0-9].{8,}" value="<?PHP if(isset($_POST['phonenumber:'])) echo htmlspecialchars($_POST['phonenumber:']); ?>"><br>
          <?php
          $event_id = $_GET["event_signin"];
          echo $event_id;
          echo '<input type="hidden" value="' . $event_id . '" name="eventid">';
          ?>
          <br>
          <input type="submit" value="Sign In">
        </form>
        <div class="cancelbutton">
          <form action="../protected/meeting_nights.php">
            <input type="submit" value="Cancel">
          </form>
        </div>
      </div>
    </div>
  </body>
</html>
