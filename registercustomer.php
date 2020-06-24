<?php
include "checksession.php";
checkUser(AC_MANAGER, AC_MANAGER, AC_AUTHENTICATED, AC_GUEST);
loginStatus();
?>
<!DOCTYPE HTML>

<html>
	<head>
		<title>New Customer Registration </title>
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
		echo "<pre>";
		var_dump($_POST);
		echo "</pre>";
		function cleanInput($data) {
		  return htmlspecialchars(stripslashes(trim($data)));
		}
		if (isset($_POST['submit']) and !empty($_POST['submit']) and ($_POST['submit'] == 'Register')) {
		    include "config.php"; //load in any variables
		    $DBC = mysqli_connect(DBHOST, DBUSER, DBPASSWORD, DBDATABASE);

		    if (mysqli_connect_errno()) {
		        echo "Error: Unable to connect to MySQL. ".mysqli_connect_error() ;
		        exit; //stop processing the page further
		    };
		$error = 0; //clear our error flag
		    $msg = 'Error: ';
		    if (isset($_POST['firstname']) and !empty($_POST['firstname'])
		        and is_string($_POST['firstname'])) {
		       $fn = cleanInput($_POST['firstname']);
		 //check length and clip if too big
		       $firstname = (strlen($fn) > 50)?substr($fn,1,50):$fn;
		    } else {
		       $error++; //bump the error flag
		       $msg .= 'Invalid firstname '; //append error message
		       $firstname = '';
		    }
		    $msg = 'Error: ';
		    if (isset($_POST['lastname']) and !empty($_POST['lastname'])
		        and is_string($_POST['lastname'])) {
		       $ln = cleanInput($_POST['lastname']);
		 //check length and clip if too big
		       $lastname = (strlen($ln) > 50)?substr($ln,1,50):$ln;
		    } else {
		       $error++; //bump the error flag
		       $msg .= 'Invalid lastname '; //append error message
		       $lastname = '';
		    }
		 $msg = 'Error: ';
		    if (isset($_POST['email']) and !empty($_POST['email'])
		        and is_string($_POST['email'])) {
		       $em = cleanInput($_POST['email']);
		 //check length and clip if too big
		       $email = (strlen($em) > 100)?substr($em,1,100):$em;
		    } else {
		       $error++; //bump the error flag
		       $msg .= 'Invalid email '; //append error message
		       $email = '';
		    }
		 $msg = 'Error: ';
		    if (isset($_POST['username']) and !empty($_POST['username'])
		        and is_string($_POST['username'])) {
		       $un = cleanInput($_POST['username']);
		 //check length and clip if too big
		       $username = (strlen($un) > 35)?substr($un,8,35):$un;
		    } else {
		       $error++; //bump the error flag
		       $msg .= 'Invalid username '; //append error message
		       $username = '';
		    }
		 $msg = 'Error: ';
		    if (isset($_POST['password']) and !empty($_POST['password'])
		        and is_string($_POST['password'])) {
		       $pw = cleanInput($_POST['password']);
		 //check length and clip if too big
		       $password = (strlen($pw) > 35)?substr($pw,8,35):$pw;
		    } else {
		       $error++; //bump the error flag
		       $msg .= 'Invalid password '; //append error message
		       $password = '';
		    }
		    if ($error == 0) {
		        $query = "INSERT INTO `customer` (firstname,lastname,email,username,password) VALUES (?,?,?,?,?)";
		        $stmt = mysqli_prepare($DBC,$query); //prepare the query
		        mysqli_stmt_bind_param($stmt,'sssss', $firstname, $lastname, $email,$username,$password);
		        mysqli_stmt_execute($stmt);
		        mysqli_stmt_close($stmt);
		        echo "<h2>New Customer registration complete!</h2>";
						//headerlocation goes here
		    } else {
		      echo "<h2>$msg</h2>".PHP_EOL;
		    }
		    mysqli_close($DBC); //close the connection once done
		}
		?>
		<!-- Header -->
			<header id="header" class="skel-layers-fixed">
			<h1>New Customer Registration</h1>
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
							<li class = "selected">New Customer</li>
						</ul>
					</li>
				</ul>
			</nav>
			</header>

		<!-- Main -->
			<section id="main" class="wrapper style1">
				<div class="container">
<h2><a href='index.php'>[Return to the Home Page]</a></h2>

<form method="POST" action="registercustomer.php">
  <p>
    <label for="firstname">First Name: </label>
    <input type="text" id="firstname" name="firstname" minlength="5" maxlength="50" required>
  </p>
  <p>
    <label for="lastname">Last Name: </label>
    <input type="text" id="lastname" name="lastname" minlength="5" maxlength="50" required>
  </p>
  <p>
    <label for="email">Email: </label>
    <input type="email" id="email" name="email" maxlength="100" size="50" required>
   </p>
  <p>
    <label for="username">Username: </label>
    <input type="text" id="username" name="username" minlength="8" maxlength="35" required>
  </p>
  <p>
    <label for="password">Password: </label>
    <input type="password" id="password" name="password" minlength="8" maxlength="35" required>
  </p>

   <input type="submit" name="submit" value="Register">
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
