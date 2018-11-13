<?php
require("./includes/config.inc");

foreach($_POST as $key=>$post_data){
	echo "You posted:" . $key . " = " . $post_data . "<br>";
}

$options = [
    'cost' => 11,
];

$email = ($_POST['email']);
$passwordenter = ($_POST['password']);

$hash = password_hash($passwordenter, PASSWORD_BCRYPT, $options);
echo $hash;


$sql = "SELECT password FROM BoiseCAP073.users WHERE email='". $email . "'";
$result = $mysqli->query($sql);

if(!$result) {
	echo"IT IS NULL";
}

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $mysqlpass = $row["password"];
        echo "<br>";
        echo $mysqlpass;
    }
} else {
    echo "0 results";
}

if ($hash == $mysqlpass) {
	echo"Loged In";
}
else {
	echo"Invalid Login";
}


$mysqli->close();
?>
