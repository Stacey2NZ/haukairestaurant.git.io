<?php
include "checksession.php";
checkUser(AC_MANAGER, AC_ADMIN);
loginStatus();
?>
<!DOCTYPE HTML>

<html>
	<head>
		<title> Delete Room</title>
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
			<h1>Delete<span>Room</span></a></h1>
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

				<div class="container">
						<section id="content">
							<?php
							include "config.php"; //load in any variables
							$DBC = mysqli_connect(DBHOST, DBUSER, DBPASSWORD, DBDATABASE);

							//insert DB code from here onwards
							//check if the connection was good
							if (mysqli_connect_errno()) {
							    echo "Error: Unable to connect to MySQL. ".mysqli_connect_error() ;
							    exit; //stop processing the page further
							}

							//function to clean input but not validate type and content
							function cleanInput($data) {
							  return htmlspecialchars(stripslashes(trim($data)));
							}

							//retrieve the Roomid from the URL
							if ($_SERVER["REQUEST_METHOD"] == "GET") {
							    $id = $_GET['id'];
							    if (empty($id) or !is_numeric($id)) {
							        echo "<h2>Invalid Room ID</h2>"; //simple error feedback
							        exit;
							    }
							}

							//the data was sent using a formtherefore we use the $_POST instead of $_GET
							//check if we are saving data first by checking if the submit button exists in the array
							if (isset($_POST['submit']) and !empty($_POST['submit']) and ($_POST['submit'] == 'Delete')) {
							    $error = 0; //clear our error flag
							    $msg = 'Error: ';
							//RoomID (sent via a form it is a string not a number so we try a type conversion!)
							    if (isset($_POST['id']) and !empty($_POST['id']) and is_integer(intval($_POST['id']))) {
							       $id = cleanInput($_POST['id']);
							    } else {
							       $error++; //bump the error flag
							       $msg .= 'Invalid Room ID '; //append error message
							       $id = 0;
							    }

							//save the Room data if the error flag is still clear and Room id is > 0
							    if ($error == 0 and $id > 0) {
							        $query = "DELETE FROM `room` WHERE roomID=?";
							        $stmt = mysqli_prepare($DBC,$query); //prepare the query
							        mysqli_stmt_bind_param($stmt,'i', $id);
							        mysqli_stmt_execute($stmt);
							        mysqli_stmt_close($stmt);
							        echo "<h2>Room details deleted.</h2>";
											//header location goes here

							    } else {
							      echo "<h2>$msg</h2>".PHP_EOL;
							    }

							}
							//make sure you ALWAYS use prepared queries when creating custom SQL like below
							$query = 'SELECT * FROM `room` WHERE roomID='.$id;
							$result = mysqli_query($DBC,$query);
							$rowcount = mysqli_num_rows($result);
							$stmt = mysqli_prepare($DBC,$query); //prepare the query
						 mysqli_stmt_bind_param($stmt,'i', $id);
						 mysqli_stmt_execute($stmt);
						 mysqli_stmt_close($stmt);
							?>
							<h1>Room details preview before deletion</h1>
							<h2><a href='listrooms.php'>[Return to the Room listing]</a><a href='index.php'>[Return to the home page]</a></h2>
							<?php

							//makes sure we have the Room
							if ($rowcount > 0) {
							    echo "<fieldset><legend>Room detail #$id</legend><dl>";
							    $row = mysqli_fetch_assoc($result);
							    echo "<dt>Room name:</dt><dd>".$row['roomname']."</dd>".PHP_EOL;
							    echo "<dt>Description:</dt><dd>".$row['description']."</dd>".PHP_EOL;
							    echo "<dt>Room type:</dt><dd>".$row['roomtype']."</dd>".PHP_EOL;
							    echo "<dt>Beds:</dt><dd>".$row['beds']."</dd>".PHP_EOL;
							    echo '</dl></fieldset>'.PHP_EOL;
							   ?><form method="POST" action="deleteroom.php">
							     <h2>Are you sure you want to delete this Room?</h2>
							     <input type="hidden" name="id" value="<?php echo $id; ?>">
							     <input type="submit" name="submit" value="Delete">
							     <a href="listrooms.php">[Cancel]</a>
							     </form>
							<?php
							} else echo "<h2>No Room found, possbily deleted!</h2>"; //suitable feedback

							mysqli_free_result($result); //free any memory used by the query
							mysqli_close($DBC); //close the connection once done
							?>

						</section>

				</div>
			</section>

		<!-- Footer -->
			<footer id="footer">
				<ul class="menu">
					<li><a href="privacy.html">Privacy</a></li>
				</ul>
				<span class="copyright">
			&copy; Copyright. All rights reserved. Template by <a href="http://www.html5webtemplates.co.uk">Responsive Web Templates</a>
				</span>
			</footer>

	</body>
</html>