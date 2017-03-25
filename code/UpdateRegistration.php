<?php
// Register a Vendor
$headers = "From: chcantre@gmail.com\r\nReply-to: chcantre@gmail.com";

// Host Information
$host = "artcraftfair.db.8897897.hostedresource.com";
$user = "artcraftfair";
$pw   = "AR74ndCr4f7!";
$db   = "artcraftfair";

include 'functions.php';
$header = buildPageStart("Update Results", "Results of updating a vendor record.", "../Admin/Admin.html");
$ender = buildPageEnd();

echo $header;

$connection = mysqli_connect($host, $user, $pw, $db);

if (!$connection) {
	echo "Connection Failed";
    die("Connection failed: " . mysqli_connect_errno());
} else {
	//Check whether the form has been submitted
	if (array_key_exists('check_submit', $_POST)) {

		$year = $_POST['year'];
		$accepted = isset($_POST['accepted']) ? true : false;
		$vendorid = $_POST['vendorid'];
		$fname = $_POST['fname'];
		$lname = $_POST['lname'];
		$street1 = $_POST['street1'];
		$street2 = $_POST['street2'];
		$city = $_POST['city'];
		$state = $_POST['state'];
		$zip = $_POST['zip'];
		$phone1 = $_POST['phone1'];
		$mobile1 = isset($_POST['mobile1']) ? true : false;
		$phone2 = $_POST['phone2'];
		$mobile2 = isset($_POST['mobile2']) ? true : false;	   
		$email = $_POST['email'];
		$category = $_POST['category'];
		$addlinfo = $_POST['addlinfo'];
		$facebook = $_POST['facebook'];
		$website = $_POST['website'];
		$electric = isset($_POST['electric']) ? true : false;
		$wall = isset($_POST['wall']) ? true : false;
		$tabletype = $_POST['tabletype'];
		$imagerelease = isset($_POST['imagerelease']) ? true : false;
		$requests = $_POST['requests'];
		echo "<table width='33%'>$rtlf";
    	echo "<tr><th>Field</th><th>Value</th></tr>";
    	echo "<tr><td>Vendor ID:</td><td>$vendorid</td></tr>";
    	echo "<tr><td>Year</td><td>$year</td></tr>";
    	if ($accepted) {
    		echo "<tr><td>Accepted:</td><td>Yes</td></tr>";
    	} else {
    		echo "<tr><td>Accepted:</td><td>Pending</td></tr>";
    	}
		echo "<tr><td>First Name:</td><td>$fname</td></tr>";
		echo "<tr><td>Last Name:</td><td>$lname</td></tr>";
		echo "<tr><td>Address:</td><td>$city, $state $zip</td></tr>";
		echo "<tr><td>PO Box/Suite:</td><td>$street2</td></tr>";
		echo "<tr><td>Phone 1:</td><td>$phone1</td></tr>";
		if ($mobile1) {
			echo "<tr><td>Mobile 1:</td><td>Yes</td></tr>";
		} else {
			echo "<tr><td>Mobile 1:</td><td>No</td></tr>";
		}
		echo "<tr><td>Phone 2:</td><td>$phone2</td></tr>";
		if ($mobile2) {
			echo "<tr><td>Mobile 2:</td><td>Yes</td></tr>";
		} else {
			echo "<tr><td>Mobile 2:</td><td>No</td></tr>";
		}
		echo "<tr><td>Email:</td><td>$email</td></tr>";
		echo "<tr><td>Category:</td><td>$category</td></tr>";
		echo "<tr><td>Additional Info:</td><td>$addlinfo</td></tr>";
		echo "<tr><td>Facebook:</td><td>$facebook</td></tr>";
		echo "<tr><td>Website:</td><td>$website</td></tr>";
		if ($electric) {
			echo "<tr><td>Electric:</td><td>Yes</td></tr>";
		} else {
			echo "<tr><td>Electric:</td><td>No</td></tr>";
		}
		if ($wall) {
			echo "<tr><td>Wall:</td><td>Yes</td></tr>";
		} else {
			echo "<tr><td>Wall:</td><td>No</td></tr>";
		}
		echo "<tr><td>Table:</td><td>$tabletype</td></tr>";
		if ($imagerelease) {
			echo "<tr><td>Image Release:</td><td>Yes</td></tr>";
		} else {
			echo "<tr><td>Image Release:</td><td>No</td></tr>";
		}
		echo "<tr><td>Requests:</td><td>$requests</td></tr>";
		echo "</table>";
	   $query = <<< SQL
UPDATE Vendors 
SET fname='$fname', lname='$lname', street1='$street1', street2='$street2', city='$city', 
state='$state', zip='$zip', phone1='$phone1', mobile1='$mobile1', phone2='$phone2', 
mobile2='mobile2', email='$email', category='$category', addlinfo='$addlinfo', 
facebook='$facebook', website='$website', electric='$electric', wall='$wall', 
tabletype='$tabletype', imagerelease='$imagerelease', year='$year', accepted='$accepted', requests='$requests' WHERE vendor_id = $vendorid;
SQL;

		if (mysqli_query($connection, $query) === TRUE) {
			echo "<p>Record updated successfully</p>";
			echo "<p>Update another <a href='../Admin/UpdateVendor.html'>vendor</a>.</p>";
		} else {
			echo "Error: " . $query . "<br/>" . mysqli_error($connection);
		}
		echo $ender;

	} else {
	    echo "You can't see this page without submitting the form.";
	}

	// Close the connection and clean up.
	mysqli_close($connection);
}
?>