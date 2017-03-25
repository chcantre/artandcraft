<?php

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
	$vendorid = test_input($_POST['vendorid']);
	if (strlen($vendorid) == 0)
		$vendorid = "Unknown";		
	$name = test_input($_POST['name']);
	$phone = $_POST['phone']; // test_input($_POST['phone']);
	$email = $_POST['email']; // test_input($_POST['email']);
	$message = test_input($_POST['message']);
	$sqlmsg = normalizeContent($message);
	$messageid = "Unknown";
	$error = "OK";
	$query = "INSERT INTO Messages (vendor_id, name, phone, email, message) VALUES ('$vendorid', '$name', '$phone', '$email', '$sqlmsg')";
	if (mysqli_query($connection, $query) === TRUE) {
		$retval = mysqli_query($connection, "SELECT LAST_INSERT_ID()");
		$row = mysqli_fetch_row($retval);
		$messageid = $row[0];
	} else {
		$error = mysqli_error($connection);
	}

	// Now, mail off the message to the coordinators.
	$phone = formatPhone($phone);
	$mailText = <<< MAIL
Someone has contacted you from the Contact form on the Art and Craft Fair website.
Vendor ID: $vendorid
Name: $name
Phone: $phone
Email: $email
Message ID: $messageid
Message: $message
MAIL;

	$to = "marret@castletonumc-artandcraftfair.org, natalie@castletonumc-artandcraftfair.org";
	$subject = "Message From $name - Vendor ID: $vendorid";
	$headers = 'From: charles@castletonumc-artandcraftfair.org' . "\r\n" . 'CC: chcantre@gmail.com';
	$ok = mail($to, $subject, $mailText, $headers);
	if (!preg_match('/OK/', $error)) {
		$to = "chcantre@gmail.com";
		$subject = "ERROR: Message From $name - Vendor ID: $vendorid";
		$headers = 'From: charles@castletonumc-artandcraftfair.org';
		$text = <<<MAIL
There was an error with the message form.
Name: $name
Phone: $phone
Email: $email
Message ID: $messageid
Message: $message
ERROR: $error
MAIL;
		$ok = mail($to, $subject, $text, $headers);
	}

	$responseHTML = <<< HTML
<h2>Thank You</h2>
<p>Thanks for contacting our coordinators. Your message has been sent on to them, and they will respond shortly.</p>
<p>Message ID: $messageid</p>
HTML;

	echo $responseHTML;
}