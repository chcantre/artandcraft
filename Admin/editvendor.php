<?php
// Update Vendor

// Need an HTML page that asks for vendor id and name.

// Then, call this page, which will:
// 1. Pull the record into the "registration" webform.
// 2. Allow edits to the field.
// 3. Write out the new information into the associated vendor_id record.

include '../code/functions.php';

$headers = "From: charles@castletonumc-artandcraftfair.org\r\nReply-to: chcantre@gmail.com";

// Host Information
$host = "artcraftfair.db.8897897.hostedresource.com";
$user = "artcraftfair";
$pw   = "AR74ndCr4f7!";
$db   = "artcraftfair";

$br = "<br/>";
$rtlf = "\r\n";

$header = buildPageStart("Update Vendor", "Update an existing vendor record.", "../Admin/Admin.html");
$ender = buildPageEnd();

echo $header;
$connection = mysqli_connect($host, $user, $pw, $db);

if (!$connection) {
	echo "Connection Failed";
    die("Connection failed: " . mysqli_connect_errno());
} else {
	echo "<h1>Update Vendor</h1>$rtlf";
	echo "<p>Update the values below that need to be changed and submit the form.</p>$rtlf";
	//Check whether the form has been submitted
		
	$vendorid = $_GET['vid'];
	$query = "SELECT vendor_id, zone, location, fname, lname, street1, street2, city, state, zip, phone1, mobile1, phone2, mobile2, email, category, addlinfo, facebook, website, electric, wall, tabletype, imagerelease, year, accepted, requests FROM Vendors WHERE vendor_id = '$vendorid'";
	$retval = mysqli_query($connection, $query);

	// Take return values and build populated web form.

	$form = outputVendorForm($retval, "../code/UpdateRegistration.php");
	echo $form;
}


?>