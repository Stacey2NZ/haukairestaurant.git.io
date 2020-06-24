<?php
include "checksession.php";
checkUser(AC_ADMIN, AC_MANAGER);
loginStatus();
?>
<!DOCTYPE HTML>

<html>
	<head>
		<title>Add a new room </title>
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
		<?php
		//function to clean input but not validate type and content
		function cleanInput($data) {
		  return htmlspecialchars(stripslashes(trim($data)));
		}

		//the data was sent using a formtherefore we use the $_POST instead of $_GET
		//check if we are saving data first by checking if the submit button exists in the array
		if (isset($_POST['submit']) and !empty($_POST['submit']) and ($_POST['submit'] == 'Add')) {
		    include "config.php"; //load in any variables
		    $DBC = mysqli_connect("127.0.0.1", DBUSER, DBPASSWORD, DBDATABASE);

		    if (mysqli_connect_errno()) {
		        echo "Error: Unable to connect to MySQL. ".mysqli_connect_error() ;
		        exit; //stop processing the page further
		    };

		//validate incoming data
		    $error = 0; //clear our error flag
		    $msg = 'Error: ';
		    if (isset($_POST['roomname']) and !empty($_POST['roomname']) and is_string($_POST['roomname'])) {
		       $fn = cleanInput($_POST['roomname']);
		       $roomname = (strlen($fn)>50)?substr($fn,1,50):$fn; //check length and clip if too big
		       //we would also do context checking here for contents, etc
		    } else {
		       $error++; //bump the error flag
		       $msg .= 'Invalid roomname '; //append eror message
		       $roomname = '';
		    }

		//description
		       $description = cleanInput($_POST['description']);
		//roomtype
		       $roomtype = cleanInput($_POST['roomtype']);
		//beds
		       $beds = cleanInput($_POST['beds']);

		//save the room data if the error flag is still clear
		    if ($error == 0) {
		        $query = "INSERT INTO `room` (roomname,description,roomtype,beds) VALUES (?,?,?,?)";
		        $stmt = mysqli_prepare($DBC,$query); //prepare the query
		        mysqli_stmt_bind_param($stmt,'sssd', $roomname, $description, $roomtype,$beds);
		        mysqli_stmt_execute($stmt);
		        mysqli_stmt_close($stmt);
		        echo "<h2>New room added to the list</h2>";
						//header location goes here
		    } else {
		      echo "<h2>$msg</h2>".PHP_EOL;
		    }
		    mysqli_close($DBC); //close the connection once done
		}
		?>
		<!-- Header -->
			<header id="header" class="skel-layers-fixed">
			<h1><span>Add new Room</span></a></h1>
			<nav id="nav">
				<ul>
					<li><a href = "index.php">Home</li>
					<li>
						<a href="" class="icon fa-angle-down">Booking</a>
						<ul>
							<li><a href = "makeabookingsearchavailability.php">Make A Booking / Search availability</li>
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
					<h2><a href='listrooms.php'>[Return to the room listing]</a><a href='index.php'>[Return to the main page]</a></h2>

<form method="POST" action="addroom.php">
  <p>
    <label for="roomname">Room name: </label>
    <input type="text" id="roomname" name="roomname" minlength="5" maxlength="50" required>
  </p>
  <p>
    <label for="description">Description: </label>
    <input type="text" id="description" size="100" name="description" minlength="5" maxlength="200" required>
  </p>
  <p>
    <label for="roomtype">Room type: </label>
    <input type="radio" id="roomtype" name="roomtype" value="S"> Single
    <input type="radio" id="roomtype" name="roomtype" value="D" Checked> Double
   </p>
  <p>
    <label for="beds">Beds (1-5): </label>
    <input type="number" id="beds" name="beds" min="1" max="5" value="1" required>
  </p>

   <input type="submit" name="submit" value="Add">
 </form>
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
