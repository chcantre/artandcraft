<?php
// Allow Coordinator to Mark All Accepted Vendors

// Then, call this page, which will:
// 1. Pull the record into the "registration" webform.
// 2. Allow edits to the field.
// 3. Write out the new information into the associated vendor_id record.

$headers = "From: chcantre@gmail.com\r\nReply-to: chcantre@gmail.com";

// Host Information
$host = "artcraftfair.db.8897897.hostedresource.com";
$user = "artcraftfair";
$pw   = "AR74ndCr4f7!";
$db   = "artcraftfair";

$br = "<br/>";
$rtlf = "\r\n";

include '../code/functions.php';
$header = buildPageStart("Accept Vendors", "Mark all accepted vendors.", "../Admin/Admin.html");
$footer = buildPageEnd();

$connection = mysqli_connect($host, $user, $pw, $db);

if (!$connection) {
	echo "Connection Failed";
    die("Connection failed: " . mysqli_connect_errno());
} else {
	//Check whether the form has been submitted
		
	/* 
			vendor_id 0 		zone 1 			slot 2
			fname 3 			lname 4 		year 5
			accepted 6
	*/

	$query = "SELECT vendor_id, zone, location, fname, lname, year, accepted FROM Vendors";
	$retval = mysqli_query($connection, $query);

	echo $header;
	echo "<h1>Mark Accepted Vendors</h1>$rtlf";
	echo "<p>Click checkboxes of accepted vendors.</p>$rtlf";
	echo "<p>Set the zone and slot to assign vendor location.</p>$rtlf";

	echo "<form name='acceptvendors' action='' method='POST'>$rtlf";
	echo "<input type='hidden' name='check_submit' value='1' />$rtlf";
	echo "<table>$rtlf";

	// now, start getting record rows and loop through, outputting vendor data as you go.
	while ($row = mysqli_fetch_row($retval)) {
		if ($row[6] == 1) {
			$acceptrow = "<tr><td>Accepted:</td><td><input type='checkbox' name='accepted' checked></td></tr>$rtlf";
		} else {
			$acceptrow = "<tr><td>Accepted:</td><td><input type='checkbox' name='accepted' ></td></tr>$rtlf";
		}
		$cell = <<< CELL
<tr><td>Vendor ID:</td><td>$row[0]</td></tr>
<tr><td>Name:</td><td>$row[3] $row[4]</td></tr>
<tr><td>Zone:</td><td><input type="text" name="zone" value="$row[1]"></td></tr>
<tr><td>Slot:</td><td><input type="text" name="slot" value="$row[2]"></td></tr>
$acceptrow
CELL;

		echo $cell;
	}
	echo "</table>$rtlf";
    echo $footer;
}


?>