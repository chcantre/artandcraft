<?php
// List Vendor Information
$headers = "From: chcantre@gmail.com\r\nReply-to: chcantre@gmail.com";

// Host Information
$host = "artcraftfair.db.8897897.hostedresource.com";
$user = "artcraftfair";
$pw   = "AR74ndCr4f7!";
$db   = "artcraftfair";

$br = "<br/>";
$rtlf = "\r\n";

$DownloadDirectory    = 'VendorPics/';

include '../code/functions.php';
$header = buildPageStart("Vendor Info", "List vendor info from the database.", "../Admin/Admin.html");
$ender = buildPageEnd();

echo $header;
$connection = mysqli_connect($host, $user, $pw, $db);

if (!$connection) {
	echo "Connection Failed";
    die("Connection failed: " . mysqli_connect_errno());
} else {
	$vendorid = $_POST['vendorid'];
    echo "<h1>Vendor $vendorid Info</h1>$rtlf";
    $query = "SELECT vendor_id, zone, location, fname, lname, street1, street2, city, state, zip, phone1, mobile1, phone2, mobile2, email, category, addlinfo, electric, wall, tabletype, imagerelease, facebook, website, year, accepted, requests FROM Vendors WHERE vendor_id = $vendorid";
    $retval = mysqli_query($connection, $query);
    echo "<table width='100%'>$rtlf";
    echo "<tr><th>Vendor ID</th><th>Location</th><th>Name</th><th>Street</th><th>Suite-PO</th><th>Address</th><th>Phone</th><th>Alt. Phone</th><th>Email</th><th>Category</th><th>Additional Info</th><th>Electric</th><th>Wall</th><th>Table</th><th>Image Release</th><th>Facebook</th><th>Website</th><th>Requests</th></tr>";

    while ($row = mysqli_fetch_row($retval)) {
        $phone1 = formatPhone($row[10]);
        $phone2 = formatPhone($row[12]);
        $year = $row[23];
        $accepted = $row[24] == 1 ? "Accepted" : "Pending";
    	// Build a Table and stick the data into the table for display.
    	echo "<tr><td>$row[0]</td><td>$row[1]-$row[2]<br>$year<br>$accepted</td><td>$row[3] $row[4]</td>$rtlf";
    	echo "<td>$row[5]</td><td>$row[6]</td><td>$row[7], $row[8] $row[9]</td>$rtlf";
    	echo "<td>$phone1";
    	if ($row[11] == 1) { echo " (mobile)</td>"; } else { echo "</td>"; }
    	echo "<td>$phone2";
    	if ($row[13] == 1) { echo " (mobile)</td>"; } else { echo "</td>"; }
    	echo "<td>$row[14]</td><td>$row[15]</td><td>$row[16]</td>";
    	if ($row[17] == 1) { echo "<td>yes</td>"; } else { echo  "<td>no</td>"; }
    	if ($row[18] == 1) { echo "<td>yes</td>"; } else { echo  "<td>no</td>"; }
    	echo "<td>$row[19]</td>";
    	if ($row[20] == 1) { echo "<td>yes</td>"; } else { echo  "<td>no</td>"; }
    	echo "<td>$row[21]</td><td>$row[22]</td><td>$row[25]</td></tr>$rtlf";
	}
    // Now, get any images that have been uploaded and display them.
    $query2 = "SELECT FILENAME FROM VendorPics WHERE VENDOR_ID = '$vendorid'";
    $retval2 = mysqli_query($connection, $query2);
    if ($retval2) {
        $count = 0;
        echo "<tr>";
        while ($row = mysqli_fetch_row($retval2)) {
            $count++;
            $filename = $row[0];
            $href = $DownloadDirectory . $filename;
            echo "<td><a href='../".$href."'>$filename</a></td>";
        }
        $colspan = 18 - +$count;
        echo "<td colspan='$colspan'></td>$rtlf";
        echo "</tr>$rtlf";
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