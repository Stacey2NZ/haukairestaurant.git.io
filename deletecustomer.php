<?php
include "checksession.php";
checkUser(AC_ADMIN, AC_MANAGER);
loginStatus();
?>
<!DOCTYPE HTML>

<html>
	<head>
		<title>List customers details </title>
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

	//retrieve the customerid from the URL
	if ($_SERVER["REQUEST_METHOD"] == "GET") {
	    $id = $_GET['id'];
	    if (empty($id) or !is_numeric($id)) {
	        echo "<h2>Invalid Customer ID</h2>"; //simple error feedback
	        exit;
	    }
	}
	if (isset($_POST['submit']) and !empty($_POST['submit']) and ($_POST['submit'] == 'Delete')) {
	    $error = 0; //clear our error flag
	    $msg = 'Error: ';
	//customerID (sent via a form it is a string not a number so we try a type conversion!)
	    if (isset($_POST['id']) and !empty($_POST['id']) and is_integer(intval($_POST['id']))) {
	       $id = cleanInput($_POST['id']);
	    } else {
	       $error++; //bump the error flag
	       $msg .= 'Invalid Customer ID '; //append error message
	       $id = 0;
	    }

	//save the customer data if the error flag is still clear and customer id is > 0
	    if ($error == 0 and $id > 0) {
	        $query = "DELETE FROM `customer` WHERE customerID=?";
	        $stmt = mysqli_prepare($DBC,$query); //prepare the query
	        mysqli_stmt_bind_param($stmt,'i', $id);
	        mysqli_stmt_execute($stmt);
	        mysqli_stmt_close($stmt);
	        echo "<h2>Customer details deleted.</h2>";
					//header location goes here

	    } else {
	      echo "<h2>$msg</h2>".PHP_EOL;
	    }

	}
?>
		<!-- Header -->
			<header id="header" class="skel-layers-fixed">
			<h1><a href="index.html">OngaOngaB&B<span>Home</span></a></h1>
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
							<li><a href = "listcustomers.php">CustomerList</a></li>
							<li><a href="registercustomer.php">New Customer</a></li>
						</ul>
					</li>
				</ul>
			</nav>
			</header>

		<!-- Main -->
			<section id="main" class="wrapper style1">
				<header class="major">
					<h2>Customer View before deletion</h2>
				</header>
				<div class="container">
						<section id="content">
		<h2><a href='listcustomers.php'>[Return to the Customer listing]</a><a href='index.php'>[Return to the main page]</a></h2>
		<h1>Customer details preview before deletion</h1>
<?php

//makes sure we have the customer
if ($rowcount > 0) {
   echo "<fieldset><legend>Customer detail #$id</legend><dl>";
   $row = mysqli_fetch_assoc($result);
   echo "<dt>Name:</dt><dd>".$row['firstname']."</dd>".PHP_EOL;
   echo "<dt>Lastname:</dt><dd>".$row['lastname']."</dd>".PHP_EOL;
   echo "<dt>Email:</dt><dd>".$row['email']."</dd>".PHP_EOL;
   echo "<dt>Password:</dt><dd>".$row['password']."</dd>".PHP_EOL;
   echo '</dl></fieldset>'.PHP_EOL;
   ?><form method="POST" action="deletecustomer.php">
     <h2>Are you sure you want to delete this customer?</h2>
     <input type="hidden" name="id" value="<?php echo $id; ?>">
     <input type="submit" name="submit" value="Delete">
     <a href="listcustomers.php">[Cancel]</a>
     </form>
<?php
} else echo "<h2>No Customer found, possbily deleted!</h2>"; //suitable feedback

mysqli_free_result($result); //free any memory used by the query
mysqli_close($DBC); //close the connection once done
?>
</table>
				</div>
			</section>

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
