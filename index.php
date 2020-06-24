<?php
include "checksession.php";
checkUser();
loginStatus();
?>
<!DOCTYPE HTML>
<html>
	<head>
		<title> Ongaonga B&B Home</title>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<meta name="description" content="" />
		<meta name="keywords" content="" />
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
	</head>
	<body class="landing">
			<header id="header" class="alt skel-layers-fixed">
				<h1>Ongaonga <span>B&B</span></a></h1>
				<nav id="nav">
					<ul>
						<li class = "selected">Home</li>
						<li>
							<a href="" class="icon fa-angle-down">Booking</a>
							<ul>
								<li><a href="makeabookingsearchavailability.php">Make A Booking / Search availability</a></li>
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

		<!-- Banner -->
			<section id="banner">
				<div class="inner">
					<h2>Ongaonga B&B</h2>
					<p>The retired couple Mr and Mrs Smith have a large beautiful homestead near Ongaonga. As they live
by themselves they have decided to turn their large home into a ‘bed and breakfast’ (B&B)</p>
					<ul class="actions">
						<li><a href="login.php" class="button big scrolly">Login</a></li>
						<li><a href = "registercustomer.php" class = "button big scrolly"> Register</a></li>
					</ul>
				</div>
			</section>
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
