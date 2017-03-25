<?php
// Load DB with existing data.
$headers = "From: chcantre@gmail.com\r\nReply-to: chcantre@gmail.com";
include '../code/functions.php';

// Host Information
$host = "artcraftfair.db.8897897.hostedresource.com";
$user = "artcraftfair";
$pw   = "AR74ndCr4f7!";
$db   = "artcraftfair";

$br = "<br/>";

$connection = mysqli_connect($host, $user, $pw, $db);

if (!$connection) {
	echo "Connection Failed";
    die("Connection failed: " . mysqli_connect_errno());
} else {
	if(isset($_FILES["csvFile"]) && $_FILES["csvFile"]["error"]== UPLOAD_ERR_OK)
	{
		//Is file size is less than allowed size.
	    if ($_FILES["csvFile"]["size"] > 5242880) {
	        die("File size is too big!");
	    }

	    // Get the file stream.

	    $filename = $_FILES['csvFile']['name'];
	    $csvfile = $_FILES['csvFile']['tmp_name'];
		$report = "";
		$handle = fopen($csvfile, "r") or die("Unable to open file!");
		if ($handle) {
			$fairyear = date('Y');
			$counter = 1;
			$recordCount = 1;
			$cvsheader = fgetcsv($handle, 0, ";", '"', "\\");
	        // Is the first field a Vendor ID? If so, do UPDATE.
	        // Otherwise, do an INSERT query.
	        $querystart = "";
	        $querytype = "";
	        $report .= "<p>" . $csvheader[0] . "</p>";
	        if (preg_match("/VENDOR/", $cvsheader[0])) {
	        	$querystart = "UPDATE Vendors SET ";
	        	$querytype = "UPDATE";
	        } else {
	        	$querystart = "INSERT INTO Vendors (";
	        	foreach ($cvsheader as $field) {
		    		$querystart .= strtoupper($field) . ", ";
		    	}
		    	$querystart = preg_replace("/, $/", ") ", $querystart);
		    	$querystart .= "VALUES (";
	        	$querytype = "INSERT";
	        }

		    while (($row = fgetcsv($handle, 0, ";", '"', "\\")) !== false) {
		        // Process a row ...
		        // Process Accepted, Phone, Mobile, Electric, Wall, Image Release.
		        $vendorid = "";
		    	$query = $querystart;
		    	$fieldcount = 0;
		    	foreach ($cvsheader as $field) {
		    		$field = strtoupper($field);
		    		$fieldvalue = "";
		    		switch ($field) {
		    			case "VENDORID":
		    				$vendorid = $row[$fieldcount];
		    				break;
		    			case "ACCEPTED":
		    			case "ELECTRIC":
		    			case "WALL":
		    			case "IMAGERELEASE":
		    			case "MOBILE1":
		    			case "MOBILE2":
		    			case "OUTSIDE":
		    				$fieldvalue = (substr(strtolower(trim($row[$fieldcount])), 0, 1) == "y" ? 1 : 0);
		    				break;
		    			case "PHONE1":
		    			case "PHONE2":
		    				$fieldvalue = preg_replace('/-/', '', $row[$fieldcount]);
		    				break;
		    			case "CATEGORY":
		    				$fieldvalue = normalizeCategory($row[$fieldcount]);
		    				break;
		    			default:
		    				$fieldvalue = test_input($row[$fieldcount]);
		    				break;
		    		}
		    		if ($querytype == "UPDATE") {
		    			if ($field != "VENDORID") $query .= "$field='$fieldvalue', ";
		    		} elseif ($querytype = "INSERT") {
		    			$query .= "'" . $fieldvalue . "', ";
		    		}
		    		$fieldcount++;
		    	}
			    		
		    	if ($querytype == "UPDATE") {
		    		$query = preg_replace("/, $/", "", $query);
		    		$query .= " WHERE VENDOR_ID = '$vendorid';";
	    		} elseif ($querytype = "INSERT") {
	    			$query = preg_replace("/, $/", ");", $query);
	    		}

	    		//$report .= "<p>" . $query . "</p>";

	    		
	    		if (mysqli_query($connection, $query) === TRUE) {
	    			if (strlen($vendorid) == 0) {
						$retval = mysqli_query($connection, "SELECT LAST_INSERT_ID()");
						$row = mysqli_fetch_row($retval); 
						$vendorid = $row[0];
						$report .= "Record " . $recordCount++ . " inserted OK :: ";
						$report .= "   NEW VENDOR CREATED: $vendorid <br />";
					} else {
						$report .= "Record " . $recordCount++ . " updated OK :: Vendor ID: $vendorid UPDATED <br />";
					}
				} else {
					$error = mysqli_error($connection);
					$report .= "<p>" . $query . "</p>";
					$report .= "Record " . $recordCount++ . " update or insert FAILED: $error <br />";
				}	
						
		    }

		    if (!feof($handle)) {
		        echo "Error: unexpected fgets() fail\n";
		    }
	    	fclose($handle);
	    }

		$header = buildPageStart("List Vendor Info", "List vendor info from the database.", "../Admin/Admin.html");
		$ender = buildPageEnd();

		echo $header;
	    echo $report;
	    echo $footer;

		// Close the connection and clean up.
		mysqli_close($connection);
	} else {
		var_dump($_FILES);
		var_dump($_POST);
		die ("File not selected.");
	}
}
?>