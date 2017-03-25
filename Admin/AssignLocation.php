<?php
// Assign Vendor Location
$headers = "From: chcantre@gmail.com\r\nReply-to: chcantre@gmail.com";

// Host Information
$host = "artcraftfair.db.8897897.hostedresource.com";
$user = "artcraftfair";
$pw   = "AR74ndCr4f7!";
$db   = "artcraftfair";

$br = "<br/>";
$rtlf = "\r\n";

include "../code/functions.php";
$header = buildPageStart("Assign Vendor Location", "Assign a vendor a booth space.", "../Admin/Admin.html");
$ender = buildPageEnd();

echo $header;

$ender = <<<ENDER
        </div>
    </body>
</html>
ENDER;

$connection = mysqli_connect($host, $user, $pw, $db);

if (!$connection) {
	echo "Connection Failed";
    die("Connection failed: " . mysqli_connect_errno());
} else {
	echo "<h1>Vendor Assignment</h1>$rtlf";
	//Check whether the form has been submitted
	if (array_key_exists('check_submit', $_POST)) {
		
		$vendorid = $_POST['vendorid'];
		$lname = $_POST['lname'];
		$zone = $_POST['zone'];
		$location = $_POST['location'];
		$accepted = TRUE;
		$fairyear = date('Y');

		echo "Vendor ID: $vendorid<br/>";
		echo "Last Name: $lname<br/>";
		echo "Zone: $zone<br/>";
		echo "Spot: $location<br/>";

		$OK = testSpot($zone, $location);
		if (!$OK) {
			echo "<p>Location not assigned.</p>";
			echo "<p>The zone and/or spot value is not correct. Zone: $zone. Spot: $location.</p>$rtlf";
		} else {
			// First, search for a record that has the submitted zone and location. 
			$query = "SELECT zone,location,vendor_id FROM Vendors WHERE zone = '$zone' AND location = '$location'";
			$result1 = mysqli_query($connection, $query);
			if (canChange($result1, $zone, $location)) {
				// If that location hasn't been assigned, then  
				// verify we are assigning a location to vendor that exists.
				$query = "SELECT vendor_id, lname FROM Vendors WHERE vendor_id = $vendorid";
				$result2 = mysqli_query($connection, $query);

				if (!$result2) {
					// We didn't find that vendor_id, so post an error message.
					echo "We didn't find that Vendor ID.";
				} else {
					$row = mysqli_fetch_row($result2);
					// We found the vendor ID, so verify that they have the right last name.
					if ($row[1] == $lname) {
						// Set the zone and location.
						if ($zone == 'clear') { $zone = ""; $location = ""; $accepted=""; }
						$query = "UPDATE Vendors SET zone='$zone', location='$location', accepted='$accepted', year='$fairyear' WHERE vendor_id = $vendorid";
						if (mysqli_query($connection, $query) === TRUE) {
							if ($zone == '') {
								echo "Location successfully cleared.";
							} else {
								echo "Location successfully assigned for year $fairyear.";
							}
						} else {
							echo "Error: " . $query . "<br>" . mysqli_error($connection);
						}
					 }	else {
					 	// The last name doesn't match. So warn the user.
					 	echo "The vendor with that Vendor ID has a different last name.";
					 	echo "Vendor ID: " . $row[0] . " Last Name: " . $row[1];
					 }	
				}
			} else {
				echo "That location has been assigned to another vendor.<br/>";
				while ($row = mysqli_fetch_row($result1)) {
					echo "Vendor ID: " . $row[2];
				}
			}
		}		
	} else {
	    echo "You can't see this page without submitting the form.";
	}
	echo "<p><a href='../Admin/AssignLocation.html'>Assign another location.</a></p>";
    echo $ender;
	// Close the connection and clean up.
	mysqli_close($connection);
}

function canChange($result, $zone, $location) {
	$retval = FALSE;

	if (((strlen($zone) == 0) && (strlen($location) == 0)) || 
		(($zone == 0) && ($location == 0))) {
		$retval = TRUE;
	} elseif(mysqli_num_rows($result) == 0) {
			$retval = TRUE;
	}
	return $retval;
}


?>