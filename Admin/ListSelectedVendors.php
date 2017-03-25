<?php
// List Vendor Information
$headers = "From: chcantre@gmail.com\r\nReply-to: chcantre@gmail.com";
$DownloadDirectory    = 'VendorPics/';

// Host Information
$host = "artcraftfair.db.8897897.hostedresource.com";
$user = "artcraftfair";
$pw   = "AR74ndCr4f7!";
$db   = "artcraftfair";

$br = "<br/>";
$rtlf = "\r\n";

include '../code/functions.php';
$header = buildPageStart("List Selected Vendors", "List the selected vendors.", "../Admin/Admin.html");
$ender = buildPageEnd();
$fairyear = date('Y');

echo $header;
$connection = mysqli_connect($host, $user, $pw, $db);

if (!$connection) {
	echo "Connection Failed";
    die("Connection failed: " . mysqli_connect_errno());
} else {
    echo "<h1>Vendor List for $fairyear</h1>$rtlf";
    echo "<p>This is the list of vendors for the fair, as of " . date('M d, Y') . " at " . date('h:i a') . ".</p>";
    /* 
        vendorid 0      zone 1          spot 2        fname 3       lname 4
        phone1 5        mobile1 6       phone2 7      mobile1 8     email 9
        category 10     addlinfo 11     table 12             
    */
    $query = "SELECT vendor_id, zone, location, fname, lname, phone1, mobile1, phone2, mobile2, email, category, addlinfo, tabletype FROM Vendors WHERE accepted = TRUE AND year='$fairyear' ORDER BY zone ASC, location ASC;";
    $retval = mysqli_query($connection, $query);
    echo "<table width='100%'>$rtlf";
    echo "<tr><th>Vendor ID</th><th>Location</th><th>Name</th><th>Phone</th><th>Alt. Phone</th><th>Email</th><th>Category</th><th>Additional Info</th><th>Table</th></tr>";

    while ($row = mysqli_fetch_row($retval)) {
        $year = $row[23];
        $vendorid = $row[0];
        $zone = $row[1]; $spot = $row[2];
        $fname = $row[3];
        $lname = $row[4];
        $email = $row[9];
        $phone1 = formatPhone($row[5]);
        $mobile1 = $row[6] == 1 ? "(mobile)" : "";
        $phone2 = formatPhone($row[7]);
        $mobile2 = $row[8] == 1 ? "(mobile)" : "";        
        $category = $row[10];
        $addlinfo = $row[11];
        $tabletype = $row[12];

    	// Build a Table and stick the data into the table for display.
    	echo "<tr><td>$vendorid</td><td>$zone-$spot</td><td>$fname $lname</td>$rtlf";
    	echo "<td>$phone1 $mobile1</td><td>$phone2 $mobile2</td><td>$email</td><td>$category</td>$rtlf";
        echo "<td>$addlinfo</td><td>$tabletype</td></tr>$rtfl";      
	}
	echo "</table>$rtlf";
    echo $ender;

    // Should we write out a CSV file here as well, and mail it to the user?
    // Consider security. Don't let the user enter an email address. We know who should get the file.
    // Mail it to them.	

	// Close the connection and clean up.
	mysqli_close($connection);
}
?>