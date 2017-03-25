<?php
// Clear Vendor Locations
// Host Information
$host = "artcraftfair.db.8897897.hostedresource.com";
$user = "artcraftfair";
$pw   = "AR74ndCr4f7!";
$db   = "artcraftfair";

$connection = mysqli_connect($host, $user, $pw, $db);

if (!$connection) {
	echo "Connection Failed";
    die("Connection failed: " . mysqli_connect_errno());
} else {
	//Check whether the form has been submitted
	if (array_key_exists('check_submit', $_POST)) {	
		$fairyear = $_POST['year'];

		// First, search for a record that has the submitted zone and location. 
		$query = "SELECT zone,location,vendor_id FROM Vendors WHERE year='$fairyear'";
		$retval = mysqli_query($connection, $query);
		$count = 0;
		while ($row = mysqli_fetch_row($retval)) {
			$vendorid = $row[2];
			$zone = $row[0];
			if (strlen($zone) > 0) {
				$query = "UPDATE Vendors SET zone='', location='', accepted='FALSE' WHERE year = $fairyear";
				if (mysqli_query($connection, $query) === TRUE) {
					echo "Vendor $vendorid location cleared<br>";
					$count++;
				} else {
					echo "Vendor $vendorid not cleared.";
				}
			}	
		}
		echo "$count locations cleared.<br>";		
	} else {
	    echo "You can't see this page without submitting the form.";
	}

	// Close the connection and clean up.
	mysqli_close($connection);
}
?>