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

$rowheader = '"VENDORID";"YEAR";"ACCEPTED";"ZONE";"LOCATION";"LNAME";"FNAME";"COMPANY";"EMAIL";"STREET1";"STREET2";"CITY";"STATE";"ZIP";"PHONE1";"MOBILE1";"PHONE2";"MOBILE2";"CATEGORY";"ADDLINFO";"FACEBOOK";"WEBSITE";"ELECTRIC";"TABLETYPE";"WALL";"OUTSIDE";"IMAGERELEASE";"REQUESTS";' . "\n";

include '../code/functions.php';
$header = buildPageStart("Export Vendor Data", "Export all vendor data in the database.", "../Admin/Admin.html");
$ender = buildPageEnd();

echo $header;
$connection = mysqli_connect($host, $user, $pw, $db);

if (!$connection) {
	echo "Connection Failed";
    die("Connection failed: " . mysqli_connect_errno());
} else {
    // open a file stream to write to.
    $directory = "/home/content/97/8897897/html/artandcraftfair/VendorPics/";
    $year = date('Y');
    $csvfile = fopen($directory . "Vendors$year.csv", 'w') or die("Unable to open file!");

    echo "<h1>Exporting CSV File</h1>$rtlf";
    echo "<p>Exporting all vendor data for CUMC Art and Craft Fair database.</p>";

    $query = "SELECT vendor_id, year, accepted, zone, location, lname, fname, company, email, street1, street2, city, state, zip, phone1, mobile1, phone2, mobile2, category, addlinfo, facebook, website, electric, tabletype, wall, outside, imagerelease, requests FROM Vendors";
    $filerow = $rowheader;
    //echo "<p>$filerow</p>";
    fwrite($csvfile, $filerow); 
    $retval = mysqli_query($connection, $query);
    while ($row = mysqli_fetch_row($retval)) {
        $cnt = 0;
        $mobile1 = $row[15] == 1 ? "yes" : "no";
        $mobile2 = $row[17] == 1 ? "yes" : "no";
        $electric = $row[22] == 1 ? "yes" : "no";
        $outside = $row[25] == 1 ? "yes" : "no";
        $wall = $row[24] == 1 ? "yes" : "no";
        $imagerelease = $row[26] == 1 ? "yes" : "no";
        $accepted = $row[2] == 1 ? "yes" : "no";
        $addlinfo = denormalize($row[19]);
        $company = denormalize($row[7]);
        $requests = denormalize($row[27]);
        $filerow = <<< ROW
"$row[0]";"$row[1]";"$accepted";"$row[3]";"$row[4]";"$row[5]";"$row[6]";"$company";"$row[8]";"$row[9]";"$row[10]";"$row[11]";"$row[12]";"$row[13]";"$row[14]";"$mobile1";"$row[16]";"$mobile2";"$row[18]";"$addlinfo";"$row[20]";"$row[21]";"$electric";"$row[23]";"$wall";"$outside";"$imagerelease";"$requests";$rtlf
ROW;
        //echo "<p>$filerow</p>";
        fwrite($csvfile, $filerow);   
	}
    $result = fclose($csvfile);
    if ($result) {
        echo "File closed. "; 
        // Now, email the file to coordinators.
        // File to attach:
        $attachfile = $directory . "Vendors$year.csv";
        $mailresult = sendMail("charles@castletonumc-artandcraftfair.org", "chcantre@gmail.com, marret@castletonumc-artandcraftfair.org", "CSV File", "The CSV Export is attached.", $attachfile);
        echo "Result: $mailresult to Fair Coordinators";
    } else {
        echo "File failed to close properly.";
    }
    echo $ender;
	mysqli_close($connection);
}
?>