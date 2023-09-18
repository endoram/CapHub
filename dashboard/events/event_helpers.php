<?
include "../includes/control_access.php";
// Function to retrieve a list of events
function getEvents() {
    global $conn;
    $sql = "SELECT * FROM events WHERE FQSN='" . $_SESSION['FQSN'] . "' ORDER BY start_date";
    $result = $conn->query($sql);
    return $result->fetch_all(MYSQLI_ASSOC);
}
// Function to retrieve event details
function getEventDetails($eventId) {
    global $conn;
    $sql = "SELECT * FROM events WHERE event_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $eventId);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}

// Function to retrieve event participants
function getEventParticipants($eventId) {
    include '../includes/config_m.php';
    $sql = "SELECT * FROM participants WHERE event_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $eventId);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_all(MYSQLI_ASSOC);
}

// Function to check out radio from comms and participant while logging in commlog
function checkoutRadio($radio_id, $cap_id) {
	require "../includes/config_m.php";
	require "../includes/helpers.php";

    $query = "SELECT * FROM sq_members WHERE cap_id='$cap_id'";
    $result = $conn->query($query);

    $name = arp($cap_id);
    $date = date("Y-m-d");
    $time = date('H:i:s');

    $query = "UPDATE comms SET in_out='OUT', name='$name', out_date='$date' WHERE radio_id='$radio_id'";
    $query1 = "INSERT INTO comms_log (`FQSN`, `updated_by`, `radio_id`, `name`, `in_out`, `date`, `time`) VALUES ('" . $_SESSION['FQSN'] . "','".$cap_id."', '$radio_id', '$name', 'OUT', '$date', '$time')";
    $query2 = "UPDATE participants SET radio='YES' WHERE cap_id='".$cap_id."'";

    $conn->query($query);
    $conn->query($query1);
	$conn->query($query2);
    $conn->close();
}

function checkinRadio($radio_id, $cap_id) {
	require "../includes/config_m.php";
	require "../includes/helpers.php";

	$name = arp($cap_id);
	$date = date('Y-m-d');
	$time = date('H:i:s');

	$query = "UPDATE comms SET in_out='IN', name='' WHERE radio_id='$radio_id'";
	$query1 = "INSERT INTO comms_log (`FQSN`, `updated_by`, `radio_id`, `in_out`, `date`, `time`) VALUES ('" . $_SESSION['FQSN'] . "','".$_SESSION["capid"]."', '$radio_id', 'IN', '$date', '$time')";
	$query2 = "UPDATE participants SET radio='NO' WHERE cap_id='".$cap_id."'";

	$conn->query($query);
	$conn->query($query1);
	$conn->query($query2);
	$conn->close();
}
?>