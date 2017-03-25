<!doctype html>

<html lang="en">
<head>
  <meta charset="utf-8">

  <title>Review Vendor Images</title>
  <meta name="description" content="Review vendor images.">
  <meta name="author" content="CCantrell">
  <link rel="shortcut icon" type="image/png" href="http://www.castletonumc-artandcraftfair.org/images/circle_favicon.png" />
  <link rel="stylesheet" type="text/css" href="../css/CUMCAandCstyles.css">
</head>

<body>
	<div class="heading">
	   <a href="Admin.html"><img srcset="../images/AdminHEADING_sm.png 550w, ../images/AdminHEADING_sm.png 1125w, ../images/AdminHEADING.png 2250w" src="../images/AdminHEADING.png" alt="Admin Header" />
	   </a>
	</div>
	<div class="content">
		<ul class="header">
		  <li class="header"><a class="header" href="../mission.html">Our Mission</a></li>
		  <li class="header"><a class="header" href="../attend.html">Attend</a></li>
		  <li class="header"><a class="header" href="../vendor.html">Participate</a></li>
		  <li class="header"><a class="header" href="../contact.html">Contact</a></li>
		</ul>
		<h1>Vendor Images</h1>
		<p>Enter the vendor id to pull up that vendor's record.</p>
		<form name="vendorimages" action="../code/DisplayImages.php" method="POST">
			<input type="hidden" name="check_submit" value="1" />
			<table>
				<tr><td>Vendor ID:</td><td><input type="text" name="vendorid"></td></tr>
				<tr"><td><input type="submit" value="Submit"></td><td><input type="reset" value="Reset"></td></tr>
			</table>
		</form>
	</div>
</body>
</html>