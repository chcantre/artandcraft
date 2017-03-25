<?php
$headers = "From: chcantre@gmail.com\r\nReply-to: chcantre@gmail.com";
$addresses = "EmailList.txt";
$handle = fopen($addresses, 'r');
while (($line = fgets($handle)) !== false)
{
	echo $line . "\n";
	$parts = preg_split('/\|/', $line);
	$email = $parts[0];
	$fname = $parts[2];
	$hsname = $parts[3];
	$lname = $parts[4];
	$street = $parts[5];
	$city  = $parts[6];
	$state = $parts[7];
	$zip   = $parts[8];
	$phone = $parts[9];

	$text = getEmailText($fname, $hsname, $lname, $street, $city, $state, $zip, $phone)
	echo $text;
	mail($email, "SHS Class of '66 Reunion", $text, $headers);
}

return;

function getEmailText($fname, $hsname, $lname, $street, $city, $state, $zip, $phone)
{
	$text = <<<TEXT
Hello, $fname,

Charles Cantrell here.

I am sending emails to all of the SHS Class of '66 classmates where I have emails. I hope this finds you well.

The 50th Year Reunion is planned for August 6th, at the Southern Dunes Golf Club at 8220 South Tibbs Avenue.  Hope you will save the date for that. 

In the meantime, I would like to verify the information that I have in my 
database. The name I have is:

	$fname $lname

Could you please confirm that I have the spelling correct. Also, the address I have for you is:

	$street
	$city, $state $zip

The phone is:

	$phone

I would appreciate it if you would email me back to confirm all this information. Thanks for your help, and I am looking forward to seeing you at the reunion.

Charles Cantrell 
TEXT;
	return $text;
}
?>
