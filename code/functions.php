<?php

$br = "<br/>";
$rtlf = "\r\n";

function testSpot($zone, $location) {
	$retval = FALSE;
	switch ($zone) {
		case '1':
			if (($location >= 1) && ($location <= 30)) { $retval = TRUE; }
			break;

		case '2':
			if ((($location >= 32) && ($location <= 41)) ||
				(($location >= 44) && ($location <= 54))) { 
					$retval = TRUE; 
			}
			break;

		case '3':
			if ((($location >= 55) && ($location <= 58)) ||
				(($location >= 60) && ($location <= 68))) { 
					$retval = TRUE; 
			}
			break;

		case '4':
			if (($location >= 69) && ($location <= 94)) { $retval = TRUE; }
			break;
		
		default:
			if ($zone == 'clear') { $retval = TRUE; }
			break;
	}
	return $retval;
}

function test_input($data) {
  $data = trim($data);
  $data = htmlentities($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  $data = normalizeContent($data);
  return $data;
}

function normalizeContent($content) {
	$content = str_replace("'", "&rsquo;", $content);
	$content = str_replace('"', "&quot;", $content);	
	return $content;
}

function denormalize($content) {
	$content = str_replace("&rsquo;", "'", $content);
	$content = str_replace("&quot;", '"', $content);	
	return $content;
}

function stripFilenameBadChars($string) {
	$string = str_replace("#", "_", $string);
	$string = str_replace(" ", "_", $string);
	$string = str_replace("%", "_", $string);
	$string = str_replace("&", "_", $string);
	$string = str_replace("\\", "_", $string);
	$string = str_replace("{", "_", $string);
	$string = str_replace("}", "_", $string);
	return $string;
}

function returnURLAnchor($string) {
	$protocol = "http://";
	$url = $string;
	if (preg_match("/\./", $string)) {
		if (!preg_match("/http:\/\//", $url)) {
			$url = $protocol . $string;
		}
		$string = "<a href='$url'>$string</a>";
	}
	return $string;
}

function filesEmpty($files) {
	if (strlen($files['FileInput1']['name'] ) || 
		strlen($files['FileInput2']['name']) || 
		strlen($files['FileInput3']['name']) || 
		strlen($files['FileInput4']['name'])) {
			return false;
		} else {
			return true;
		}
}

function normalizeCategory($category) {
	$category = strtolower($category);
	switch ($category) {
		case "wood work":
			$category = "woodwork";
			break;
		case "leather work":
			$category = "leatherwork";
			break;
		case "personal care":
			$category = "personalcare";
			break;
		case "home decor":
			$category = "homedecor";
			break;
		case "miscellaneous":
			$category = "misc";
			break;
		case "ceramics and pottery":
		case "pottery":
			$category = "ceramic";
			break;
	}
	return $category;
}

function formatPhone($phone) {
	if (!preg_match('/-/', $phone)) {
		if (strlen($phone) == 10)
			return substr($phone, 0, 3)."-".substr($phone, 3, 3)."-".substr($phone, 6);
		elseif (strlen($phone) == 11) 
			return substr($phone, 0, 1)."-".substr($phone, 1, 3)."-".substr($phone, 4, 3)."-".substr($phone, 7);
		elseif (strlen($phone) == 7)
			return substr($phone, 0, 3)."-".substr($phone, 3);
	} else {
		return $phone;
	}

}

function buildPageEnd() {
	$ender =<<<ENDER
        </div>
    </body>
</html>
ENDER;
	return $ender;
}

function buildPageStart($title, $description, $href = "index.html") {
	$header =<<<HEADER
<!doctype html>

<html lang="en">
<head>
  <meta charset="utf-8">

  <title>$title</title>
  <meta name="description" content="$description">
  <meta name="author" content="CCantrell">
  <link rel="stylesheet" type="text/css" href="../css/CUMCAandCstyles.css">
  <link rel="shortcut icon" type="image/png" href="http://www.castletonumc-artandcraftfair.org/images/circle_favicon.png" />
</head>

<body>
	<div class="heading">
	   <a href="$href"><img srcset="../images/AdminHEADING_sm.png 550w, ../images/AdminHEADING_sm.png 1125w, ../images/AdminHEADING.png 2250w" src="../images/AdminHEADING.png" alt="Admin Header" />
	   </a>
	</div>
	<div class="content">
		<ul class="header">
		  <li class="header"><a class="header" href="../mission.html">Our Mission</a></li>
		  <li class="header"><a class="header" href="../attend.html">Attend</a></li>
		  <li class="header"><a class="header" href="../vendor.html">Participate</a></li>
		  <li class="header"><a class="header" href="../contact.html">Contact</a></li>
		</ul>
HEADER;
	return $header;
}

function buildVendorPageStart($title, $description, $href = "index.html") {
	$header =<<<HEADER
<!doctype html>

<html lang="en">
<head>
  <meta charset="utf-8">

  <title>$title</title>
  <meta name="description" content="$description">
  <meta name="author" content="CCantrell">
  <link rel="stylesheet" type="text/css" href="../css/CUMCAandCstyles.css">
</head>

<body>
	<div class="heading">
	   <a href="../index.html"><img srcset="../images/WebsiteHEADING_sm.png 550w, ../images/WebsiteHEADING_sm.png 1125w, ../images/WebsiteHEADING.png 2250w" src="../images/WebsiteHEADING.png" alt="CUMC Art and Craft Web Header" />
	   </a>
	</div>
	<div class="content">
		<ul class="header">
		  <li class="header"><a class="header" href="../mission.html">Our Mission</a></li>
		  <li class="header"><a class="header" href="../attend.html">Attend</a></li>
		  <li class="header"><a class="header" href="../vendor.html">Participate</a></li>
		  <li class="header"><a class="header" href="../contact.html">Contact</a></li>
		</ul>
HEADER;
	return $header;
}

function outputVendorForm($fields, $action) {
	$row = mysqli_fetch_row($fields);
	if ($row[11] == 1)
		$mobilebox1 = "<input type='checkbox' name='mobile1' checked>";
	else
		$mobilebox1 = "<input type='checkbox' name='mobile1'>";

	if ($row[13] == 1)
		$mobilebox2 = "<input type='checkbox' name='mobile2' checked>";
	else
		$mobilebox2 = "<input type='checkbox' name='mobile2'>";

	if ($row[19] == 1)
		$electricRow = "<td>Electric Needed:</td><td><input type='checkbox' name='electric' checked></td>";
	else
		$electricRow = "<td>Electric Needed:</td><td><input type='checkbox' name='electric'></td>";

	if ($row[21] == 1)
		$wallRow = "<td>Wall Needed:</td><td><input type='checkbox' name='wall' checked></td>";
	else
		$wallRow = "<td>Wall Needed:</td><td><input type='checkbox' name='wall'></td>";

	if ($row[22] == 1)
		$releaseRow = "<td><input type='checkbox' name='imagerelease' checked></td>";
	else
		$releaseRow = "<td><input type='checkbox' name='imagerelease'></td>";

	if ($row[24] == 1)
		$accepted = "<td><input type='checkbox' name='accepted' checked></td>";
	else
		$accepted = "<td><input type='checkbox' name='accepted'></td>";

	$stateControl = getStateControl($row[8]);
	$tableControl = getTableControl($row[21]);
	$categoryControl = getCategoryControl($row[15]);

	$form = <<< FORM
<form name="vendorinfo" action="$action" method="POST">
	<input type="hidden" name="check_submit" value="1" />
	<input type="hidden" name="vendorid" value="$row[0]" />
	<table>
		<tr><td>Vendor ID:</td><td>$row[0]</td></tr>
		<tr><td>Location:</td><td>$row[1]-$row[2]</td></tr>
		<tr><td>Year:</td><td><input type="text" name="year" value="$row[23]"></td></tr>
		<tr><td>Accepted</td>$accepted</tr>
		<tr><td>Name:</td><td><input type="text" name="fname" value="$row[3]"> <input type="text" name="lname" value="$row[4]"></td></tr>
		<tr><td>Street:</td><td><input type="text" name="street1" value="$row[5]"></td></tr>
		<tr><td>Street 2:</td><td><input type="text" name="street2" value="$row[6]"></td></tr>
		<tr><td>City, State, Zip:</td><td><input type="text" name="city" value="$row[7]">, 
			$stateControl
			<input type="text" name="zip" value="$row[9]"></td></tr>
		<tr><td>Phone 1:</td><td><input type="text" name="phone1" value="$row[10]"> (Mobile?) $mobilebox1</td></tr>
		<tr><td>Phone 2:</td><td><input type="text" name="phone2" value="$row[12]"> (Mobile?) $mobilebox2</td></tr>
		<tr><td>Email:</td><td><input type="text" name="email" value="$row[14]"></td></tr>
		<tr><td>Craft Category:</td>
			<td>
			$categoryControl
			</td>
		</tr>
		<tr>
			<td>Product Description/Add'l Info:</td>
			<td><textarea name="addlinfo" rows="3" cols="40">$row[16]</textarea>
			</td>
		</tr>
		<tr>
			<td>Facebook ID or Page:</td><td><input type="text" name="facebook" value="$row[17]"></td>
		</tr>
		<tr>
			<td>Web Site:</td><td><input type="text" name="website" value="$row[18]"></td>
		</tr>
		<tr>
			$electricRow
		</tr>
		<tr>
			<td>Table Needed:</td>
			<td>
				$tableControl
			</td>
		</tr>
		<tr>
			$wallRow
		</tr>
		<tr>
			<td>I agree that the fair may use <br/>pictures and images of me,<br/> my booth, and products for the <br/>purposes of promoting the fair.</td>
			$releaseRow
		</tr>
		<tr>
			<td>Special Requests</td><td><textarea name="requests" row="5" col="40">$row[25]</textarea></td></tr>
		<tr><td><input type="submit" value="Submit"></td><td><input type="reset" value="Reset"></td></tr>
	</table>
</form>
FORM;
	return $form;
}

function getTableControl($val) {

	$tableControl = '<select name="tabletype">';
	switch ($val) {
		case 'none':
			$tableControl .= <<< SELECT
<option value="none" selected>None</option>
<option value="8">8 foot</option>
<option value="6">6 foot</option>
SELECT;
			break;
		case '8':
			$tableControl .= <<< SELECT
<option value="none">None</option>
<option value="8" selected>8 foot</option>
<option value="6">6 foot</option>
SELECT;
			break;

		case '6':
			$tableControl .= <<< SELECT
<option value="none">None</option>
<option value="8">8 foot</option>
<option value="6" selected>6 foot</option>
SELECT;
			break;

		default:
			$tableControl .= <<< SELECT
<option value="none">None</option>
<option value="8">8 foot</option>
<option value="6">6 foot</option>
SELECT;
			break;
	}
	
	$tableControl .= "</select>\r\n";
	return $tableControl;
}

function getStateControl($state) {
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
		$query = "SELECT state, name FROM States";
		$retval = mysqli_query($connection, $query);
		mysqli_close($connection);

		$control = <<< CONTROL
<select name="state">
	<option></option>
CONTROL;

		while ($row = mysqli_fetch_row($retval)) {
			if ($state == $row[0])
				$control .= "<option value='$row[0]' selected>$row[1]</option>\r\n";
			else
				$control .= "<option value='$row[0]'>$row[1]</option>\r\n";
		}
		$control .= "</select>\r\n";
 	
		return $control;
	}

}

function getCategoryControl($category) {
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
		$query = "SELECT CategoryValue, CategoryType FROM CategoryTypes";
		$retval = mysqli_query($connection, $query);
		mysqli_close($connection);
	}
	$control = <<< CONTROL
<select name="category">
	<option></option>
CONTROL;

	while ($row = mysqli_fetch_row($retval)) {
		if ($category == $row[0])
			$control .= "<option value='$row[0]' selected>$row[1]</option>\r\n";
		else
			$control .= "<option value='$row[0]'>$row[1]</option>\r\n";
	}
	$control .= "</select>$rtlf";

	return $control;
}

function outputUpdateResults()	{

}
	
	
function validEmail($eddress)
{
	$isValid = true;
	$atIndex = strrpos($eddress, "@");
	if (is_bool($atIndex) && !$atIndex) {
        	$isValid = false;
	} else {
		$domain = substr($eddress, $atIndex+1);
		$local = substr($eddress, 0, $atIndex);
		$localLen = strlen($local);
		$domainLen = strlen($domain);
		if ($localLen < 1 || $localLen > 64) {
			// local part length exceeded
			$isValid = false; 
		} else if ($domainLen < 1 || $domainLen > 255) {
			// domain part length exceeded
			$isValid = false; 
		} else if ($local[0] == '.' || $local[$localLen-1] == '.') {
			// local part starts or ends with '.'
			$isValid = false;
		} else if (preg_match('/\\.\\./', $local)) {
			// local part has two consecutive dots
			$isValid = false;
		} else if (!preg_match('/^[A-Za-z0-9\\-\\.]+$/', $domain)) {
			// character not valid in domain part
			$isValid = false;
		} else if (preg_match('/\\.\\./', $domain)) {
			// domain part has two consecutive dots
			$isValid = false;
		} else if (!preg_match('/^(\\\\.|[A-Za-z0-9!#%&`_=\\/$\'*+?^{}|~.-])+$/',
			    str_replace("\\\\","",$local))) {
			// character not valid in local part unless 
			// local part is quoted
			if (!preg_match('/^"(\\\\"|[^"])+"$/', str_replace("\\\\","",$local))) {
				$isValid = false;
			}
		}
		if ($isValid && !(checkdnsrr($domain,"MX") || checkdnsrr($domain,"A"))) {
			// domain not found in DNS
			$isValid = false;
		}
	}
	return $isValid;
}

function sendMail($email_from, $email_to, $email_subject, $email_txt, $fileatt="") {

	$headers = "From: " . $email_from;
	
	if (file_exists($fileatt)) {
		$fileparts = explode("/", $fileatt);
		$fileatt_name = $fileparts[count($fileparts)-1];

		$extparts = explode(".", $fileatt);	
		$ext = $extparts[count($extparts)-1];
		switch ($ext) {
			case 'csv':
				$fileatt_type = "text/csv"; // File Type
				break;
			case 'php':
				$fileatt_type = "text/plain"; // File Type
				break;
			case 'pdf': 
				$fileatt_type = "application/pdf"; // File Type
				break;
			case 'zip': 
				$fileatt_type = "application/zip"; // File Type
				break;
			case 'doc': 
				$fileatt_type = "application/msword"; // File Type
				break;
			case 'css': 
				$fileatt_type = "application/css"; // File Type
				break;
			case 'jpg': 
				$fileatt_type = "application/jpg"; // File Type
				break;
			case 'jpeg': 
				$fileatt_type = "application/jpg"; // File Type
				break;
			default: 
				$fileatt_type = "application/octet-stream"; // File Type
		}

		$file = fopen($fileatt,'rb');
		$data = fread($file, filesize($fileatt));
		fclose($file);
	}

	$semi_rand = md5(time());
	$mime_boundary = "==Multipart_Boundary_x{$semi_rand}x";

	$headers .= "\nMIME-Version: 1.0\n" .  
		    "Content-Type: multipart/mixed;\n" .  
		    " boundary=\"{$mime_boundary}\"";

	$email_message = "This is a multi-part message in MIME format.\n\n" .  
	                  "--{$mime_boundary}\n" .
                          "Content-Type: text/html; charset=\"iso-8859-1\"\n" .
                          "Content-Transfer-Encoding: 7bit\n\n" .
                          $email_txt . "\n\n";

	if (isset($data)) {
		$data = chunk_split(base64_encode($data));

		$email_message .= "--{$mime_boundary}\n" .
				  "Content-Type: {$fileatt_type};\n" .
				  " name=\"{$fileatt_name}\"\n" .
				  "Content-Disposition: attachment;\n" .
				  " filename=\"{$fileatt_name}\"\n" .
				  "Content-Transfer-Encoding: base64\n\n" .
				  $data . "\n\n" .
				  "--{$mime_boundary}--\n";

	}

	$mail_sent = @mail($email_to, $email_subject, $email_message, $headers);
	$msg = $mail_sent ? "Mail sent" : "Mail failed";
	return $msg;
	
}
