<?php
include "checksession.php";
checkUser(AC_MANAGER, AC_ADMIN);
loginStatus();
?>
<!DOCTYPE HTML>

<html>
	<head>
		<title> Current Bookings</title>
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
			<h1><a href="index.php">OngaongaB&B<span>Home</span></a></h1>
			<nav id="nav">
				<ul>
					<li><a href = "index.php">Home</li>
					<li>
						<a href="" class="icon fa-angle-down">Booking</a>
						<ul>
							<li><a href = "makeabookingsearchavailability.php">Make A Booking / Search availability</a></li>
							<li class = "selected">Current Bookings</a></li>
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
					<?php
include "config.php"; //load in any variables
$DBC = mysqli_connect(DBHOST, DBUSER, DBPASSWORD, DBDATABASE);

if (mysqli_connect_errno()) {
    echo "Error: Unable to connect to MySQL. ".mysqli_connect_error() ;
    exit;
}

echo "<pre>";
//prepare a query and send it to the server
$query = 'SELECT R.roomname, R.roomID, C.customerID, C.firstname, C.lastname, B.bookingID, B.checkinDate, B.checkoutDate
FROM `bookings` B
JOIN `room` R ON B.roomID=R.roomID
JOIN `customer` C ON B.customerID = C.customerID
ORDER BY bookingID';

 $stmt = mysqli_prepare($DBC,$query); //prepare the query
mysqli_stmt_bind_param($stmt, 'isisssssi', $roomID, $roomname, $customerID, $firstname, $lastname, $checkinDate, $checkoutDate, $roomReview, $id);
mysqli_stmt_execute($stmt);
mysqli_stmt_close($stmt);
$result = mysqli_query($DBC,$query);
//check result for data
if (mysqli_num_rows($result) > 0) {

	echo '<h1> Current Bookings </h1>'.PHP_EOL;
	echo '<a href = "index.php">[Return to main page]</a>'.' '.'<a href = "makeabookingsearchavailability.php"[Make A Booking]</a>'.PHP_EOL;
    echo "Record count: ".mysqli_num_rows($result).PHP_EOL;
    while ($row = mysqli_fetch_assoc($result)) {
		$id = $row['bookingID'];
		$roomID = $row['roomID'];
		$customerID = $row['customerID'];
		$checkinDate= $row['checkinDate'];
		$checkoutDate = $row['checkoutDate'];
//I tried to get this table to present with html, but then my while loop would not work. Feedback would be great if you can say what the problem/possible solution would be
		echo '<table><thead><td>'.'BookingID'.'</td><td>'.'roomname'.'</td><td>'.'customername'.'</td><td>'.'checkinDate'.'</td><td>'.'checkoutDate'.'</td><td>'.'Actions'.'</td>';
		echo '</tr>'.PHP_EOL;
		echo '<tr><td>'.$row['bookingID'].'</td><td>'.$row['roomname'].'</td><td>'.$row['firstname'].' '.$row['lastname'].'</td><td>'.$checkinDate.'</td><td>'.$checkoutDate.'</td>';
        echo '<td><a href="viewBooking.php?id='.$id.'">[view]</a>';
		echo '<a href="editBookings.php?id='.$id.'">[edit]</a>';
		echo '<a href="deleteBooking.php?id='.$id.'">[delete]</a>';
		echo '<a href = "editAddRoomReview.php?id='.$id.'">[edit/add room review]</a></td>';
		echo '</tr>'.PHP_EOL;
		echo "<hr />";
   }

   mysqli_free_result($result); //free any memory used by the query
}
 else echo "<h2>Invalid booking! Possibly has been deleted</h2>";

echo "</pre>";

mysqli_close($DBC);
?>

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
