<?
/**
 * The PHP code includes functions to retrieve events and event details from a database, as well as
 * functions to check out and check in radios and update participant information.
 * 
 * @return The functions in the code are returning different types of values:
 */

include "../includes/control_access.php";

/**
 * The function "getEvents" retrieves events from a database table based on the FQSN value stored in
 * the session and returns them in ascending order of start date.
 * 
 * @return an array of associative arrays, where each associative array represents an event.
 */
function getEvents() {
    global $conn;
    $sql = "SELECT * FROM events WHERE FQSN='" . $_SESSION['FQSN'] . "' ORDER BY start_date";
    $result = $conn->query($sql);
    return $result->fetch_all(MYSQLI_ASSOC);
}


/**
 * The function "getEventDetails" retrieves the details of an event from a database based on the
 * provided event ID.
 * 
 * @param eventId The parameter `` is the unique identifier of the event for which you want to
 * retrieve the details. It is used in the SQL query to filter the events table and fetch the details
 * of the specific event with the matching event_id.
 * 
 * @return an associative array containing the details of the event with the specified event ID.
 */
function getEventDetails($eventId) {
    global $conn;
    $sql = "SELECT * FROM events WHERE event_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $eventId);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}


/**
 * The function retrieves all participants of a specific event from a database.
 * 
 * @param eventId The `eventId` parameter is the ID of the event for which you want to retrieve the
 * participants.
 * 
 * @return an array of participants for a specific event.
 */
function getEventParticipants($eventId) {
    include '../includes/config_m.php';
    $sql = "SELECT * FROM participants WHERE event_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $eventId);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_all(MYSQLI_ASSOC);
}


/**
 * The function `checkoutRadio` updates the status of a radio to "OUT" and logs the checkout
 * information in the database.
 * 
 * @param radio_id The `radio_id` parameter is the ID of the radio that is being checked out. It is
 * used to identify the specific radio in the database.
 * @param cap_id The `cap_id` parameter is the ID of the member who is checking out the radio.
 */
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


/**
 * The function `checkinRadio` updates the status of a radio to "IN" and logs the check-in time and
 * date, as well as updates the participant's radio status to "NO".
 * 
 * @param radio_id The radio_id parameter is the unique identifier for the radio that is being checked
 * in.
 * @param cap_id The `cap_id` parameter is the ID of a participant in the system. It is used to
 * retrieve the participant's name from the database and update their radio status.
 */
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