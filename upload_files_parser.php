<?php
// Register a Vendor
include '/home/content/97/8897897/html/artandcraftfair/code/functions.php';

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
	$newDocument = "";
	if (array_key_exists('check_submit', $_POST)) {

		$fname = ucfirst(test_input($_POST['fname']));
		$lname = ucfirst(test_input($_POST['lname']));
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
		$email = strtolower(test_input($_POST['email']));
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

		$sqlPhone1 = preg_replace('/^0-9/', "", $phone1);
		$sqlPhone2 = preg_replace('/^0-9/', "", $phone2);

		$year = date('Y');
		$accepted = false;
		$zone = '0';
		$location = '0';
		$query = "INSERT INTO Vendors (year, accepted, fname, lname, street1, street2, city, state, zip, phone1, mobile1, phone2, mobile2, email, category, addlinfo, facebook, website, electric, wall, tabletype, imagerelease, zone, location) VALUES ('$year', '$accepted', '$fname', '$lname', '$street1', '$street2', '$city', '$state', '$zip', '$sqlPhone1', '$mobile1', '$sqlPhone2', '$mobile2', '$email', '$category', '$addlinfo', '$facebook', '$website', '$electric', '$wall', '$tabletype', '$imagerelease', '$zone', '$location')";

		if (mysqli_query($connection, $query) === TRUE) {
			$result = array();
			$retval = mysqli_query($connection, "SELECT LAST_INSERT_ID()");
			$row = mysqli_fetch_row($retval);
			$vendorid = $row[0];
			if(!filesEmpty($_FILES)) {
				$result = doFileUpload($vendorid);
				// Now, add the files to the database.
				addFiles2DB($connection, $vendorid, $result);
			} else {
				$result[0] = "You will need to provide pictures of your booth to complete your application.";
			}

			$phone1 = formatPhone($phone1);
			$phone2 = formatPhone($phone2);
			$mailText = <<< MAIL
Vendor ID: $vendorid
First Name: $fname
Last Name: $lname
Phone 1: $phone1
Phone 2: $phone2
Email: $email
Category: $category
Additional Info: 
$addlinfo

http://castletonumc-artandcraftfair.org/Admin/RetrieveAVendor.html

MAIL;
			$to = "marret@castletonumc-artandcraftfair.org, marretcantrell@gmail.com, natalie@castletonumc-artandcraftfair.org";
			$subject = "New Vendor ID: $vendorid";
			$headers = 'From: charles@castletonumc-artandcraftfair.org' . "\r\n" . " -f charles@castletonumc-artandcraftfair.org";
			$ok = mail($to, $subject, $mailText, $headers);

			$newDocument = <<< HTML
<h1>Thank You</h1>
<p>Thank you for registering to participate in the Castleton UMC Art and Craft Fair. One of the coordinators will be in touch with your shortly to discuss your participation.</p>
<p><strong>Your Vendor Application ID is: $vendorid.</strong> Please make note of this ID.</p>
<p>File Upload Results: <br />
HTML;

			foreach ($result as $r) {
				if ((preg_match('/ERROR/', $r)) || filesEmpty($_FILES)) {
					$newDocument .= "$r<br />";
				} else {
					$newDocument .= "Success: $r</br>";
				}
			}
			$newDocument .= "</p>";
			$newDocument .= "<p>The Fair is a juried fair, so your participation is not guaranteed. But, we work to include a high-quality, diverse selection of arts and crafts.";
		} else {
			$newDocument = "There was an error: $query";
			$newDocument .= buildPageEnd();
		}
	} else {
	    $newDocument = "You can't see this page without submitting the form.";
	}

	// Send email to vendor
	if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
		$to = $email;
		$subject = "Castleton UMC Art and Craft Fair Registration";
		$headers = 'From: charles@castletonumc-artandcraftfair.org' . "\r\n" . " -f charles@castletonumc-artandcraftfair.org";
		$vendorMailText = <<<TEXT
$fname $lname,
Thanks for registering for the Castleton UMC Art and Craft Fair. 

Your vendor registration number is $vendorid. Please make a note of this.

We will review your application to be a vendor at the fair and be in touch with you soon.
TEXT;
		mail($to, $subject, $vendorMailText, $headers);
	} else {
		$newDocument .= "<p>We were unable to validate your email, so, we did not send a confirmation email.</p>";
	}
	
	echo $newDocument;

	// Close the connection and clean up.
	mysqli_close($connection);
}

function doFileUpload($vendorid) {
	############ Edit settings ##############
	$UploadDirectory	= '/home/content/97/8897897/html/artandcraftfair/VendorPics/'; //specify upload directory ends with / (slash)
	##########################################

	// var_dump($_FILES);
	$result = array();
	
	for ($i = 1; $i <= 4; $i++) {

		if (strlen($_FILES["FileInput$i"]["name"]) == 0) {
			continue;
		} else {

			//Is file size is less than allowed size.
			if ($_FILES["FileInput$i"]["size"] > 5242880) {
				$result[$i-1] = "File " . $_FILES["FileInput$i"]["name"] . " is too big!";
			}
		
			//allowed file type Server side check
			switch(strtolower($_FILES["FileInput$i"]['type']))
			{
					//allowed file types
		            case 'image/png': 
					case 'image/gif': 
					case 'image/jpeg': 
					case 'image/pjpeg':
					case 'image/tiff':
						break;
					default:
						$result[$i-1] = 'Unsupported file type!' . $_FILES["FileInput$i"]['type']; //output error
			}
			
			$File_Name          = stripFilenameBadChars(strtolower($_FILES["FileInput$i"]['name']));
			$NewFileName 		= "Vendor_" . $vendorid ."_". $File_Name;
			
			if(move_uploaded_file($_FILES["FileInput$i"]['tmp_name'], $UploadDirectory.$NewFileName ))
			{
				$result[$i-1] = $NewFileName;
			} else {
				$result[$i-1] = "ERROR: " . $_FILES["FileInput$i"]['name'];
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
