<?php
// Register a Vendor
$headers = "From: chcantre@gmail.com\r\nReply-to: chcantre@gmail.com";
include 'functions.php';

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

		$fname = test_input($_POST['fname']);
		$lname = test_input($_POST['lname']);
		$street1 = test_input($_POST['street1']);
		$street2 = test_input($_POST['street2']);
		$city = test_input($_POST['city']);
		$state = test_input($_POST['state']);
		$zip = test_input($_POST['zip']);
		$phone1 = test_input($_POST['phone1']);
		if (isset($_POST['mobile1'])) {
			$mobile1 = true;
		} else {
			$mobile1 = false;
		}
		$phone2 = test_input($_POST['phone2']);
		if (isset($_POST['mobile2'])) {
			$mobile2 = true;
		} else {
			$mobile2 = false;
		}	   
		$email = test_input($_POST['email']);
		$category = test_input($_POST['category']);
		$addlinfo = test_input($_POST['addlinfo']);
		$facebook = test_input($_POST['facebook']);
		$website = test_input($_POST['website']);
		if (isset($_POST['electric'])) {
			$electric = true;
		} else {
			$electric = false;
		}
		if (isset($_POST['wall'])) {
			$wall = true;
		} else {
			$wall = false;
		}
		$tabletype = test_input($_POST['tabletype']);
		if (isset($_POST['imagerelease'])) {
			$imagerelease = true;
		} else {
			$imagerelease = false;
		}
		
		$query = "INSERT INTO Vendors (fname, lname, street1, street2, city, state, zip, phone1, mobile1, phone2, mobile2, email, category, addlinfo, facebook, website, electric, wall, tabletype, imagerelease) VALUES ('$fname', '$lname', '$street1', '$street2', '$city', '$state', '$zip', '$phone1', '$mobile1', '$phone2', '$mobile2', '$email', '$category', '$addlinfo', '$facebook', '$website', '$electric', '$wall', '$tabletype', '$imagerelease')";

		if (mysqli_query($connection, $query) === TRUE) {
			$result = array();
			$retval = mysqli_query($connection, "SELECT LAST_INSERT_ID()");
			$row = mysqli_fetch_row($retval);
			$vendorid = $row[0];
			if(count($_FILES)) {
				$result = doFileUpload($vendorid);
				// Now, add the files to the database.
				addFiles2DB($connection, $vendorid, $result);
			}
			$header = buildVendorPageStart("Thank You", "Thank the vendor for registering.");
			$ender = buildPageEnd();
			echo $header;
			echo "<h1>Thank You</h1>";
			echo "<p>Thank you for registering to participate in the Castleton UMC Art and Craft Fair. One of the coordinators will be in touch with your shortly to discuss your participation.</p>";
			echo "<p><strong>Your Vendor Application ID is: $vendorid.</strong> Please make note of this ID.</p>";
			echo "<p>File Upload Results: <br />";
			foreach ($result as $r) {
				if (preg_match('/ERROR/', $r)) {
					echo "$r<br />";
				} else {
					echo "Success: $r</br>";
				}
			}
			echo "</p>";
			echo "<p>The Fair is a juried fair, so your participation is not guaranteed. But, we work to include a high-quality, diverse selection of arts and crafts.";
			echo $footer;
		} else {
			$header = buildVendorPageStart("Error", "There was an error.");
			$ender = buildPageEnd();
			echo $header;
			echo "<h1>Sorry, there was an error.</h1>";
			echo "Error: " . $query . "<br>" . mysqli_error($connection);
			echo "<p>You can try again. Or, <a href='http://castletonumc-artandcraftfair.org/contact.html'>contact</a> one of the coordinators for assistance.</p>";
			echo $footer;		
		}
	} else {
	    echo "You can't see this page without submitting the form.";
	}

	// Close the connection and clean up.
	mysqli_close($connection);
}

function doFileUpload($vendorid) {
	############ Edit settings ##############
	$UploadDirectory	= '/home/content/97/8897897/html/artandcraftfair/VendorPics/'; //specify upload directory ends with / (slash)
	##########################################

	// Still need to add names of vendor image files to the database VendorPics table.

	#var_dump($_FILES);
	$result = array();
	
	for ($i = 1; $i <= 4; $i++) {

		if (strlen($_FILES["FileInput$i"]["name"]) == 0) {
			continue;
		} else {

			//Is file size is less than allowed size.
			if ($_FILES["FileInput$i"]["size"] > 5242880) {
				$result[$i-1] = "File " . $_FILES["FileInput$i"]["name"] . " is too big!";
			} else {
				//allowed file type Server side check
				switch(strtolower($_FILES["FileInput$i"]['type']))
				{
					//allowed file types
		            case 'image/png': 
					case 'image/gif': 
					case 'image/jpeg': 
					case 'image/pjpeg':
					case 'image/tiff':
						$File_Name   = strtolower($_FILES["FileInput$i"]['name']);
						$NewFileName = "Vendor_" . $vendorid ."_". $File_Name; //new file name					
						if(move_uploaded_file($_FILES["FileInput$i"]['tmp_name'], $UploadDirectory.$NewFileName ))
						{
							$result[$i-1] = $NewFileName;
						} else {
							$result[$i-1] = "ERROR: " . $_FILES["FileInput$i"]['name'];
						}
						break;
					default: 
						$result[$i-1] = 'ERROR: Unsupported file type! ' . $_FILES["FileInput$i"]['type']; //output error
						
				}
			}	
		}	
	}
	return $result;
}

function addFiles2DB($connection, $vendorid, $result) {
	// Need to set up some kind of error checking on the insert. 
	// Maybe, if it fails, send an email to System Admin.
	foreach ($result as $r) {
		$query = "INSERT INTO VendorPics (VENDOR_ID, FILENAME) VALUES ('$vendorid', '$r')";
		mysqli_query($connection, $query);
	}
}

?>
