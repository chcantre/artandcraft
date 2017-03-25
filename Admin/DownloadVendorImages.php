<?php
 
ignore_user_abort(true);
set_time_limit(0); // disable the time limit for this script

include '/home/content/97/8897897/html/artandcraftfair/code/functions.php';
 
    ############ Edit settings ##############
    $SourceDirectory    = '/home/content/97/8897897/html/artandcraftfair/VendorPics/';
    ##########################################


    $pgStart = buildPageStart("Download Vendor Images", "Download vendor images from server.", "../Admin/Admin.html");
    $pgEnd = buildPageEnd();

    echo $pgStart;
    if ($dh = opendir($SourceDirectory)) {
        while (($file = readdir($dh)) !== false) {

            if (!is_dir($file)) {
                echo "filename: " . $file . "<br>";
            }
        }
        closedir($dh);
    }
    echo $pgEnd;

    // Present the list of files to be selected for download: checkboxes ?

    // Get the list of selected files.

    // Use the download director - or do we need to ask.

    // Download the files.

    // Report results.


/*
$dl_file = preg_replace("([^\w\s\d\-_~,;:\[\]\(\).]|[\.]{2,})", '', $_GET['download_file']); // simple file name validation
$dl_file = filter_var($dl_file, FILTER_SANITIZE_URL); // Remove (more) invalid characters
$fullPath = $path.$dl_file;
 
if ($fd = fopen ($fullPath, "r")) {
    $fsize = filesize($fullPath);
    $path_parts = pathinfo($fullPath);
    $ext = strtolower($path_parts["extension"]);
    switch ($ext) {
        case "pdf":
        header("Content-type: application/pdf");
        header("Content-Disposition: attachment; filename=\"".$path_parts["basename"]."\""); // use 'attachment' to force a file download
        break;
        // add more headers for other content types here
        default;
        header("Content-type: application/octet-stream");
        header("Content-Disposition: filename=\"".$path_parts["basename"]."\"");
        break;
    }
    header("Content-length: $fsize");
    header("Cache-control: private"); //use this to open files directly
    while(!feof($fd)) {
        $buffer = fread($fd, 2048);
        echo $buffer;
    }
}
fclose ($fd);
exit;

*/