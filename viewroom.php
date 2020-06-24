<?php
include "checksession.php";
checkUser(AC_ADMIN, AC_MANAGER);
loginStatus();
?>
<!DOCTYPE HTML>
<?php
include "config.php"; //load in any variables
$DBC = mysqli_connect(DBHOST, DBUSER, DBPASSWORD, DBDATABASE);

//insert DB code from here onwards
//check if the connection was good
if (mysqli_connect_errno()) {
    echo "Error: Unable to connect to MySQL. ".mysqli_connect_error() ;
    exit; //stop processing the page further
}
$id = $_GET['id'];
if (empty($id) or !is_numeric($id)) {
 echo "<h2>Invalid Room ID</h2>"; //simple error feedback
 exit;
}
//make sure you ALWAYS use prepared queries when creating custom SQL like below
$query = 'SELECT * FROM `room` WHERE roomID='.$id;
$stmt = mysqli_prepare($DBC, $query);
mysqli_stmt_bind_param($stmt, 'i,s,s,s,s', $roomID, $roomname, $description, $roomtype, $beds);
mysqli_stmt_execute($stmt);
mysqli_stmt_close($stmt);
//headerlcation goes here
$result = mysqli_query($DBC,$query);
$rowcount = mysqli_num_rows($result);
?>
<html>
	<head>
		<title> View Room Details</title>
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
			<h1>View Room Info</a></h1>
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
							<li><a href="listrooms.php">Room list</a></li>
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
				<header class="major">
					<p>Room details</p>
				</header>
				<div class="container">

					<!-- Content -->
						<section id="content">
							<h2><a href='listrooms.php'>[Return to the Room listing]</a><a href='index.php'>[Return to the home page]</a></h2>
							<?php
							if ($rowcount > 0) {
							   echo "<fieldset><legend>Room detail #$id</legend><dl>";
							   $row = mysqli_fetch_assoc($result);
							   echo "<dt>Room name:</dt><dd>".$row['roomname']."</dd>".PHP_EOL;
							   echo "<dt>Description:</dt><dd>".$row['description']."</dd>".PHP_EOL;
							   echo "<dt>Room type:</dt><dd>".$row['roomtype']."</dd>".PHP_EOL;
							   echo "<dt>Beds:</dt><dd>".$row['beds']."</dd>".PHP_EOL;
							   echo '</dl></fieldset>'.PHP_EOL;
							} else echo "<h2>No Room found!</h2>"; //suitable feedback

							mysqli_free_result($result); //free any memory used by the query
							mysqli_close($DBC); //close the connection once done
							?>
							</table>
			</section>
		</div>
		<!-- Footer -->
			<footer id="footer">
				<ul class="menu">
					<li><a href="#">Privacy</a></li>
				</ul>
				<span class="copyright">
			&copy; Copyright. All rights reserved. Template by <a href="http://www.html5webtemplates.co.uk">Responsive Web Templates</a>
				</span>
			</footer>

	</body>
</html>
