<?php
include "checksession.php";
checkUser(AC_MANAGER, AC_ADMIN);
loginStatus();
?>
<!DOCTYPE HTML>

<html>
	<head>
		<title>Edit Room </title>
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
		include "config.php"; //load in any variables
		$DBC = mysqli_connect(DBHOST, DBUSER, DBPASSWORD, DBDATABASE);

		if (mysqli_connect_errno()) {
		  echo "Error: Unable to connect to MySQL. ".mysqli_connect_error() ;
		  exit; //stop processing the page further
		};

		//function to clean input but not validate type and content
		function cleanInput($data) {
		  return htmlspecialchars(stripslashes(trim($data)));
		}

		//retrieve the roomid from the URL
		if ($_SERVER["REQUEST_METHOD"] == "GET") {
		    $id = $_GET['id'];
		    if (empty($id) or !is_numeric($id)) {
		        echo "<h2>Invalid room ID</h2>"; //simple error feedback
		        exit;
		    }
		}
		//the data was sent using a formtherefore we use the $_POST instead of $_GET
		//check if we are saving data first by checking if the submit button exists in the array
		if (isset($_POST['submit']) and !empty($_POST['submit']) and ($_POST['submit'] == 'Update')) {
		//validate incoming data - only the first field is done for you in this example - rest is up to you do

		//roomID (sent via a form ti is a string not a number so we try a type conversion!)
		    if (isset($_POST['id']) and !empty($_POST['id']) and is_integer(intval($_POST['id']))) {
		       $id = cleanInput($_POST['id']);
		    } else {
		       $error++; //bump the error flag
		       $msg .= 'Invalid room ID '; //append error message
		       $id = 0;
		    }
		//roomname
		       $roomname = cleanInput($_POST['roomname']);
		//description
		       $description = cleanInput($_POST['description']);
		//roomtype
		       $roomtype = cleanInput($_POST['roomtype']);
		//beds
		       $beds = cleanInput($_POST['beds']);

		//save the room data if the error flag is still clear and room id is > 0
		    if ($error == 0 and $id > 0) {
		        $query = "UPDATE `room` SET roomname=?,description=?,roomtype=?,beds=? WHERE roomID=?";
		        $stmt = mysqli_prepare($DBC,$query); //prepare the query
		        mysqli_stmt_bind_param($stmt,'ssssi', $roomname, $description, $roomtype, $beds, $id);
		        mysqli_stmt_execute($stmt);
		        mysqli_stmt_close($stmt);
		        echo "<h2>Room details updated.</h2>";
		//        header('Location: http://localhost/bit608/listrooms.php', true, 303);
		    } else {
		      echo "<h2>$msg</h2>".PHP_EOL;
		    }
		}
		//locate the room to edit by using the roomID
		//we also include the room ID in our form for sending it back for saving the data
		$query = 'SELECT roomID,roomname,description,roomtype,beds FROM `room` WHERE roomID='.$id;
		$result = mysqli_query($DBC,$query);
		$rowcount = mysqli_num_rows($result);
		if ($rowcount > 0) {
		  $row = mysqli_fetch_assoc($result);
		?>
		<!-- Header -->
			<header id="header" class="skel-layers-fixed">
			<h1><span>Edit room</span></a></h1>
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

					<!-- Content -->
						<section id="content">
							<h1>Room Details Update</h1>
							<h2><a href='listrooms.php'>[Return to the room listing]</a><a href='index.php'>[Return to the main page]</a></h2>

							<form method="POST" action="editroom.php">
							  <input type="hidden" name="id" value="<?php echo $id;?>">
							   <p>
							    <label for="roomname">Room name: </label>
							    <input type="text" id="roomname" name="roomname" minlength="5" maxlength="50" value="<?php echo $row['roomname']; ?>" required>
							  </p>
							  <p>
							    <label for="description">Description: </label>
							    <input type="text" id="description" name="description" size="100" minlength="5" maxlength="200" value="<?php echo $row['description']; ?>" required>
							  </p>
							  <p>
							    <label for="roomtype">Room type: </label>
							    <input type="radio" id="roomtype" name="roomtype" value="S" <?php echo $row['roomtype']=='S'?'Checked':''; ?>> Single
							    <input type="radio" id="roomtype" name="roomtype" value="D" <?php echo $row['roomtype']=='D'?'Checked':''; ?>> Double
							   </p>
							  <p>
							    <label for="beds">Beds (1-5): </label>
							    <input type="number" id="beds" name="beds" min="1" max="5" value="1" value="<?php echo $row['beds']; ?>" required>
							  </p>
							   <input type="submit" name="submit" value="Update">
							 </form>
							<?php
							} else {
							  echo "<h2>room not found with that ID</h2>"; //simple error feedback
							}
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
