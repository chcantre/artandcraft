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
$header = buildPageStart("List Vendor Info", "List vendor info from the database.", "../Admin/Admin.html");
$ender = buildPageEnd();

echo $header;
$connection = mysqli_connect($host, $user, $pw, $db);

if (!$connection) {
	echo "Connection Failed";
    die("Connection failed: " . mysqli_connect_errno());
} else {
    echo "<h1>Vendor List</h1>$rtlf";
    $query = "SELECT vendor_id, zone, location, fname, lname, street1, street2, city, state, zip, phone1, mobile1, phone2, mobile2, email, category, addlinfo, electric, wall, tabletype, imagerelease, facebook, website, year, accepted, requests FROM Vendors";
    $retval = mysqli_query($connection, $query);
    echo "<table width='100%'>$rtlf";
    echo "<tr><th>Vendor ID</th><th>Location<br>Year<br>Accepted</th><th>Name</th><th>Street</th><th>Suite-PO</th><th>Address</th><th>Phone</th><th>Alt. Phone</th><th>Email</th><th>Category</th><th>Additional Info</th><th>Electric</th><th>Wall</th><th>Table</th><th>Image Release</th><th>Facebook</th><th>Website</th><th>Requests</th></tr>";

    while ($row = mysqli_fetch_row($retval)) {
        $phone1 = formatPhone($row[10]);
        $phone2 = formatPhone($row[12]);
        $vendorid = $row[0];
        $email = $row[14];
        $fname = $row[3];
        $lname = $row[4];
        $year = $row[23];
        $accepted = $row[24] == 1 ? "Accepted": "Pending";
        $website = returnURLAnchor($row[22]);
    	// Build a Table and stick the data into the table for display.
    	echo "<tr><td><a href='../Admin/editvendor.php?vid=$row[0]'>$row[0]</a></td><td><a href='../Admin/editvendorlocation.php?vid=$row[0]&lname=$row[4]&zone=$row[1]&spot=$row[2]'>$row[1]-$row[2]</a><br>$year<br>$accepted</td><td>$row[3] $row[4]</td>$rtlf";
    	echo "<td>$row[5]</td><td>$row[6]</td><td>$row[7], $row[8] $row[9]</td>$rtlf";
    	echo "<td>$phone1";
    	if ($row[11] == 1) { echo " (mobile)</td>"; } else { echo "</td>"; }
    	echo "<td>$phone2";
    	if ($row[13] == 1) { echo " (mobile)</td>"; } else { echo "</td>"; }
    	echo "<td><a href='mailto:".$row[14]."'>$row[14]</a></td><td>$row[15]</td><td>$row[16]</td>";
    	if ($row[17] == 1) { echo "<td>yes</td>"; } else { echo  "<td>no</td>"; }
    	if ($row[18] == 1) { echo "<td>yes</td>"; } else { echo  "<td>no</td>"; }
    	echo "<td>$row[19]</td>";
    	if ($row[20] == 1) { echo "<td>yes</td>"; } else { echo  "<td>no</td>"; }
    	echo "<td>$row[21]</td>";
        echo "<td>$website</td>";
        echo "<td>$row[25]</td></tr>$rtlf";

        // Now, get the list of images the vendor has uploaded.
        $query2 = "SELECT FILENAME FROM VendorPics WHERE VENDOR_ID = '$vendorid'";
        $retval2 = mysqli_query($connection, $query2);
        echo "<tr>";
        $imageCount = 0;
        echo "<tr>";
        while ($picrow = mysqli_fetch_row($retval2)) {
            $imageCount++;
            $filename = $picrow[0];
            $href = $DownloadDirectory . $filename;
            echo "<td colspan='3'><a href='../".$href."'>$filename</a></</td>";
        }
        if ($imageCount == 0) {
            echo "<td colspan='18'>This vendor needs to provide booth pictures.</td></tr>";
        } else {
            echo "<td colspan='" . (18 - ($imageCount * 3)) . "'></td></tr>";
        }
        
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