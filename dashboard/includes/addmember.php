<?php
  /* The code is using the `require` statement to include two PHP files: "../includes/header.php" and
  "helpers.php". */
  require "../includes/header.php";
  require "helpers.php";

  /* This code block is checking if the HTTP request method is "POST". If it is, it retrieves the
  values of the form fields (firstname, lastname, capid, cadetornot) using the  superglobal
  array. */
  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $capid = $_POST['capid'];
    $cadetornot = $_POST['cadetornot'];
    if($_SESSION['privlv'] <= 1){
      $priv = "0";
      $password_password = NULL;
    }
    else {
      $priv = $_POST['privlage_level'];
      $password_password = $_POST['psw'];
    }
    adduser($firstname, $lastname, $capid, $cadetornot, $priv, $password_password);
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
    <title>CapHub Add Member</title>
  </head>
  <body>
    <div class="column">
      <!-- The code is creating a form for adding a new member. -->
      <div class="addmemberform">
        <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" accept-charset="UTF-8">
          <?php
          if(isset($errorMsg) && $errorMsg) {
            echo "<p style=\"color: red;\">*",htmlspecialchars($errorMsg),"</p>\n\n";
          }?>
          <label for="firstname">First name:</label> <input type="text" name="firstname" align="right" value="<?PHP if(isset($_POST['capid'])) echo htmlspecialchars($_POST['firstname']); ?>" required><br>
          <label for="lastname">Last name:</label> <input type="text" name="lastname" align="right" value="<?PHP if(isset($_POST['capid'])) echo htmlspecialchars($_POST['lastname']); ?>" required><br>
          <label for="capid">CAP ID:</label> <input type="text" id="capid" name="capid" align="right" title="Must be a proper CAPID" pattern="[0-9].{5,}" value="<?PHP if(isset($_POST['capid'])) echo htmlspecialchars($_POST['capid']); ?>" required><br>
          <?php if($_SESSION['privlv'] >= 2){ ?>
            <label for="privlage_level">Privlage Level:</label> <input type="text" id="privlevel" name="privlage_level" align="right" pattern="([0-2])" title="Must be a proper Privlage Level" value="<?PHP if(isset($_POST['privlage_level'])) echo htmlspecialchars($_POST['privlage_level']); ?>" required><br>
            <label for="psw">Password</label><input type="password" id="psw" name="psw" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" title="Must contain at least one number and one uppercase and lowercase letter, and at least 8 or more characters" value="<?PHP if(isset($_POST['psw'])) echo htmlspecialchars($_POST['psw']); ?>" required><br>
          <?php } ?>
          <select name="cadetornot">
            <option value="cadet">Cadet</option>
            <option value="senior">Senior</option>
          <select>
          <br>
          <input type="submit" value="Add Member">
        </form>
        <div class="cancelbutton">
          <form action="../protected/sqmembers.php">
            <input type="submit" value="Cancel">
          </form>
        </div>
      </div>
    </div>
    <div class="column">
      <div id="message2">
        <h3>CAPID must be all numbers and at least 6 numbes:</h3>
        <p id="number2" class="invalid">Not <b>all numbers</b></p>
        <p id="length2" class="invalid">Must be <b>at least</b> 6 numbers</p>
      </div>
    </div>
    <?php if($_SESSION['privlv'] >= 2){ ?>
      <div class="column">
        <div id="message1">
          <h3>Privlage Level must be between 0 and 2:</h3>
          <p id="number1" class="invalid">Not <b>between</b> 0 and 2</p>
          <p id="length1" class="invalid">Must be <b>Less</b> then 1 character</p>
        </div>
      </div>
      <div class="column">
        <div id="message">
          <h3>Password must contain the following:</h3>
          <p id="letter" class="invalid">A <b>lowercase</b> letter</p>
          <p id="capital" class="invalid">A <b>capital (uppercase)</b> letter</p>
          <p id="number" class="invalid">A <b>number</b></p>
          <p id="length" class="invalid">Minimum <b>8 characters</b></p>
        </div>
      </div>
    <?php } ?>
  </body>
    <div class="container-fluid p-0">
    <?php
      require "../includes/footer.php";
    ?>
  </div>
</html>

<?php if($_SESSION['privlv'] >= 2){ ?>
<script>
  var privinput = document.getElementById("privlevel");
  var number1 = document.getElementById("number1");
  var length1 = document.getElementById("length1");

  var passinput = document.getElementById("psw");
  var letter = document.getElementById("letter");
  var capital = document.getElementById("capital");
  var number = document.getElementById("number");
  var length = document.getElementById("length");

  privinput.onfocus = function() {document.getElementById("message1").style.display = "block";}
  privinput.onblur = function() {document.getElementById("message1").style.display = "none";}

  passinput.onfocus = function() {document.getElementById("message").style.display = "block";}
  passinput.onblur = function() {document.getElementById("message").style.display = "none";}

  privinput.onkeyup = function() {
    var numbers = /[0-2]/g;
    if(privinput.value.match(numbers)) {
      number1.classList.remove("invalid");
      number1.classList.add("valid");
    } else {
      number1.classList.remove("valid");
      number1.classList.add("invalid");
    }
    if(privinput.value.length == 1) {
      length1.classList.remove("invalid");
      length1.classList.add("valid");
    } else {
      length1.classList.remove("valid");
      length1.classList.add("invalid");
    }
  }

  passinput.onkeyup = function() {
    // Validate lowercase letters
    var lowerCaseLetters = /[a-z]/g;
    if(passinput.value.match(lowerCaseLetters)) {
      letter.classList.remove("invalid");
      letter.classList.add("valid");
    } else {
      letter.classList.remove("valid");
      letter.classList.add("invalid");
    }

    // Validate capital letters
    var upperCaseLetters = /[A-Z]/g;
    if(passinput.value.match(upperCaseLetters)) {
      capital.classList.remove("invalid");
      capital.classList.add("valid");
    } else {
      capital.classList.remove("valid");
      capital.classList.add("invalid");
    }

    // Validate numbers
    var numbers = /[0-9]/g;
    if(passinput.value.match(numbers)) {
      number.classList.remove("invalid");
      number.classList.add("valid");
    } else {
      number.classList.remove("valid");
      number.classList.add("invalid");
    }

    // Validate length
    if(passinput.value.length >= 8) {
      length.classList.remove("invalid");
      length.classList.add("valid");
    } else {
      length.classList.remove("valid");
      length.classList.add("invalid");
    }
  }

</script>
<?php } ?>

<script>
var capid1 = document.getElementById("capid");
var number2 = document.getElementById("number2");
var length2 = document.getElementById("length2");

capid1.onfocus = function() {document.getElementById("message2").style.display = "block";}
capid1.onblur = function() {document.getElementById("message2").style.display = "none";}

capid1.onkeyup = function() {
  var numbers = /[0-9]/g;
  if(capid1.value.match(numbers)) {
    number2.classList.remove("invalid");
    number2.classList.add("valid");
  } else {
    number2.classList.remove("valid");
    number2.classList.add("invalid");
  }
  if(capid1.value.length >= 6) {
    length2.classList.remove("invalid");
    length2.classList.add("valid");
  } else {
    length2.classList.remove("valid");
    length2.classList.add("invalid");
  }
}
</script>
