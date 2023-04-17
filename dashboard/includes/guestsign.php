<?php
  session_start();
  if (!isset($_GET['kiosk'])) {
    if (isset($_POST['kiosk']) && $_POST['kiosk'] == 2) {
    } else {
      require "control_access.php";
    }
  }
  if (!isset($_SESSION['FQSN'])) {
    require "control_access.php";
  }

  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require "../includes/config_m.php";
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $name = $lastname . ' ' . $firstname;

    $phonenumber = 0;

    if (isset($_POST['phonenumber']) && strlen($_POST['phonenumber'] >= 3)) {
      $phonenumber = $_POST['phonenumber'];
    }
    if (strlen($_POST['email']) >= 2) {
      $email = $_POST['email'];
    }

    $membertype = "visitor";
    $message = $name . " signed in";
    require "../includes/helpers.php";
    timeZone();
    $date = date("Y/m/d");
    $time = date("H:i:s");

    $query = "INSERT INTO meeting_nights (date, name, time_in, member_type, phone_number, email, visited) VALUES
     ('$date', '$name', '$time', '$membertype', '$phonenumber', '$email', '" . $_SESSION['FQSN'] . "')";
    $conn->query($query);
    $conn->close();

    $_SESSION['message'] = "Thanks for signing in!";
    if (isset($_POST['kiosk']) && $_POST['kiosk'] == 2) {
      header("Location: ../protected/meeting_nights.php?kiosk");
      die;
    } else {
      header("Location: ../protected/meeting_nights.php");
      die;
    }
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
    <a href="../protected/main.php"><img src="../images/bannerThree.png"></a>
    <title>CapHub Add Member</title>
  </head>
  <body>
    <div class="column">
      <div class="addmemberform">
        <form method="post" action="guestsign.php" accept-charset="UTF-8">
          <?php
          if(isset($errorMsg) && $errorMsg) {
            echo "<p style=\"color: red;\">*",htmlspecialchars($errorMsg),"</p>\n\n";
          }?>
          <label for="firstname">First name:</label> <input type="text" name="firstname" align="right" value="<?PHP if(isset($_POST['phonenumber:'])) echo htmlspecialchars($_POST['firstname']); ?>" required><br>
          <label for="lastname">Last name:</label> <input type="text" name="lastname" align="right" value="<?PHP if(isset($_POST['phonenumber:'])) echo htmlspecialchars($_POST['lastname']); ?>" required><br>
          <label for="phonenumber">Phone Number:(10 Digit)</label> <input type="text" id="phonenumber" name="phonenumber" align="right" title="Must be a proper phone number" pattern="[0-9].{8,}" value="<?PHP if(isset($_POST['phonenumber:'])) echo htmlspecialchars($_POST['phonenumber:']); ?>" required><br>
          <label for="email">Email:</label> <input type="text" id="email" name="email" align="right" required><br>

          <br>
          <input type="submit" value="Sign In">
          <?php if (isset($_GET['kiosk']) or isset($_POST['kiosk'])) {
          echo '<input type="hidden" name="kiosk" value=2>';
        }?>
        </form>
        <div class="cancelbutton">
          <form action="../protected/meeting_nights.php">
            <input type="submit" value="Cancel">
            <?php if (isset($_GET['kiosk']) or isset($_POST['kiosk'])) {
          echo '<input type="hidden" name="kiosk" value=2>';
        }?>
          </form>
        </div>
      </div>
    </div>
  </body>
  <?php
  echo '
    <script src="../libs/bootstrap/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js" integrity="sha384-mQ93GR66B00ZXjt0YO5KlohRA5SY2XofN4zfuZxLkoj1gXtW8ANNCe9d5Y3eG5eD" crossorigin="anonymous"></script>
    <link href="../libs/bootstrap/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="style.css">';
    require "../includes/footer.php";
  ?>
</html>