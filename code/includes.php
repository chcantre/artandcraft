<?php

function buildPageEnd() {
	$ender = <<<ENDER
        </div>
    </body>
</html>
ENDER;
	return $ender;
}

function buildPageStart($title, $description) {
	$header = <<< HEADER
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
	   <a href="index.html"><img srcset="../images/WebsiteHEADING_sm.png 550w, ../images/WebsiteHEADING_sm.png 1125w, ../images/WebsiteHEADING.png 2250w" src="../images/WebsiteHEADING.png" alt="CUMC Art and Craft Web Header" />
	   </a>
	</div>
	<div class="content">
		<ul class="header">
		  <li class="header"><a class="header" href="mission.html">Our Mission</a></li>
		  <li class="header"><a class="header" href="attend.html">Attend</a></li>
		  <li class="header"><a class="header" href="">Participate</a></li>
		  <li class="header"><a class="header" href="contact.html">Contact</a></li>
		</ul>
HEADER;
	return $header;
}