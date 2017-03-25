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
	if (array_key_exists('check_submit', $_POST)) {
		
		$vendorid = test_input($_POST['vendorid']);
		$lname = test_input($_POST['lname']);
		
		/* 
				vendor_id 0 		zone 1 			location 2
				fname 3 			lname 4 		street1 5 
				street2 6 			city 7			state 8
				zip 9 				phone1 10 		mobile 11 
				phone2 12 			mobile2 13 		email 14 
				category 15			addlinfo 16		facebook 17
				website 18			electric 19		wall 20 
				tabletype 21		imagerelease 22 year 23
				accepted 24         requests 25
		*/
		$query = "SELECT vendor_id, zone, location, fname, lname, street1, street2, city, state, zip, phone1, mobile1, phone2, mobile2, email, category, addlinfo, facebook, website, electric, wall, tabletype, imagerelease, year, accepted, requests FROM Vendors WHERE vendor_id = '$vendorid' AND lname = '$lname'";
    	$retval = mysqli_query($connection, $query);

    	// Take return values and build populated web form.

    	$form = outputVendorForm($retval, "../code/UpdateRegistration.php");
    	echo $form;
    }
}


?>