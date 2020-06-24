<?php
include "checksession.php";
loginStatus();
include "config.php"; //load in any variables
$DBC = mysqli_connect(DBHOST, DBUSER, DBPASSWORD, DBDATABASE);

//insert DB code from here onwards
//check if the connection was good
if (mysqli_connect_errno()) {
		echo "Error: Unable to connect to MySQL. ".mysqli_connect_error() ;
		exit; //stop processing the page further
}

//prepare a query and send it to the server
$query = 'SELECT roomID,roomname,roomtype FROM `room` ORDER BY roomtype';
$result = mysqli_query($DBC,$query);
$rowcount = mysqli_num_rows($result);
?>
<!DOCTYPE HTML>

<html>
	<head>
		<title> Room List</title>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<meta name="description" content="" />
		<meta name="keywords" content="" />
		<!--[if lte IE 8]><script src="css/ie/html5shiv.js"></script><![endif]-->
		<script src="js/jquery.min.js"></script>
		<script src="js/jquery.dropotron.min.js"></script>
		<script src="js/jquery.scrollgress.min.js"></script>
		<script src="js/jquery.scrolly.min.js"></script>
		<script src="js/jquery.slidertron.min.js"></script>
		<script src="js/skel.min.js"></script>
		<script src="js/skel-layers.min.js"></script>
		<script src="js/init.js"></script>
		<noscript>
			<link rel="stylesheet" href="css/skel.css" />
			<link rel="stylesheet" href="css/style.css" />
			<link rel="stylesheet" href="css/style-xlarge.css" />
		</noscript>
		<!--[if lte IE 9]><link rel="stylesheet" href="css/ie/v9.css" /><![endif]-->
		<!--[if lte IE 8]><link rel="stylesheet" href="css/ie/v8.css" /><![endif]-->
	</head>
	<body>

		<!-- Header -->
			<header id="header" class="skel-layers-fixed">
		<h1><a href="index.html">OngaongaB&B<span>Home</span></a></h1>
		<nav id="nav">
			<ul>
				<li><a href = "index.php">Home</li>
				<li>
					<a href="" class="icon fa-angle-down">Booking</a>
					<ul>
						<li><a href = "makeabookingsearchavailability.php">Make A Booking / Search availability</a></li>
						<li><a href="currentBookings.php">Current Bookings</a></li>
					</ul>
				</li>
				<li>
					<a href="" class="icon fa-angle-down">Rooms</a>
					<ul>
						<li class = "selected">Room list</a></li>
					</ul>
				</li>
				<li>
					<a href="" class="icon fa-angle-down">Customer</a>
					<ul>
						<li><a href="listcustomers.php">CustomerList</a></li>
						<li><a href="registercustomer.php">New Customer</a></li>
					</ul>
				</li>
			</ul>
		</nav>
			</header>

		<!-- Main -->
			<section id="main" class="wrapper style1">
				<div class="container">
					<div class="row 150%">
						<div class="8u 12u$(2)">


	<h1>Room list</h1>
	<h2><a href='addroom.php'>[Add a room]</a><a href="index.php">[Return to home page]</a></h2>
	<table border="1">
	<thead><tr><th>Room Name</th><th>Type</th><th>Action</th></tr></thead>
	<?php
	if ($rowcount > 0) {
	    while ($row = mysqli_fetch_assoc($result)) {
		  $id = $row['roomID'];
		  echo '<tr><td>'.$row['roomname'].'</td><td>'.$row['roomtype'].'</td>';
		  echo     '<td><a href="viewroom.php?id='.$id.'">[view]</a>';
		  echo         '<a href="editroom.php?id='.$id.'">[edit]</a>';
		  echo         '<a href="deleteroom.php?id='.$id.'">[delete]</a></td>';
	      echo '</tr>'.PHP_EOL;
	   }
	} else echo "<h2>No rooms found!</h2>"; //suitable feedback

	mysqli_free_result($result); //free any memory used by the query
	mysqli_close($DBC); //close the connection once done
	?>
	</table>
						</div>
					</div>
				</div>
			</section>

		<!-- Footer -->
			<footer id="footer">
				<ul class="menu">
					<li><a href="privacy.html">Privacy</a></li>
				</ul>
				<span class="copyright">
						&copy; Copyright. All rights reserved. Design by <a href="http://www.html5webtemplates.co.uk">Responsive Web Templates</a>
				</span>
			</footer>

	</body>
</html>
