<?php
include "checksession.php";
checkUser();
loginStatus();
echo "<pre>"; var_dump($_POST); echo "</pre>";

//simple logout
if (isset($_POST['logout'])) logout();

if (isset($_POST['login']) and !empty($_POST['login']) and ($_POST['login'] == 'Login')) {
	 include "config.php"; //load in any variables
	 $DBC = mysqli_connect(DBHOST, DBUSER, DBPASSWORD, DBDATABASE) or die();
//check if the connection was good
if (!$DBC) {
	 echo "Error: Unable to connect to MySQL.\n". mysqli_connect_errno()."=".mysqli_connect_error() ;
	 exit; //stop processing the page further
};
	 $error = 0; //clear our error flag
	 $msg = 'Error: ';
	 if (isset($_POST['username']) and !empty($_POST['username']) and is_string($_POST['username'])) {
			$un = htmlspecialchars(stripslashes(trim($_POST['username'])));
			$username = (strlen($un)>32)?substr($un,1,32):$un; //check length and clip if too big
	 } else {
			$error++; //bump the error flag
			$msg .= 'Invalid username '; //append error message
			$username = '';
	 }
 //password  - normally we avoid altering a password apart from whitespace on the ends
			$password = trim($_POST['password']);
	 //firstname
//lastname

//This should be done with prepared statements!!
	 if ($error == 0) {
			 $query = "SELECT customerID,password FROM `customer` WHERE username = '$username'";
			 $result = mysqli_query($DBC,$query);
			 if (mysqli_num_rows($result) == 1) { //found the user
					 $row = mysqli_fetch_assoc($result);
					 mysqli_free_result($result);
					 mysqli_close($DBC); //close the connection once done
 //this line would be added to the registermember.php to make a password hash before storing it
 //$hash = password_hash($password);
 //this line would be used if our user password was stored as a hashed password
					//if (password_verify($password, $row['password'])) {
					 if ($password === $row['password']) //using plaintext for demonstration only!
						 login($row['customerID'],$username);
			 } echo "<h2>Login fail</h2>".PHP_EOL;
	 } else {
		 echo "<h2>$msg</h2>".PHP_EOL;
	 }
}
?>
?>
<!DOCTYPE HTML>

<html>
	<head>
		<title> Login</title>
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
					<h2>Login Page</h2>
				</header>
				<div class="container">
						<section id="content">
<form method="POST" action="login.php">
  <p>
    <label for="username">Username: </label>
    <input type="text" id="username" name="username" maxlength="32">
  </p>
  <p>
    <label for="password">Password: </label>
    <input type="password" id="password" name="password" maxlength="32">
  </p>

   <input type="submit" name="login" value="Login">
   <input type="submit" name="logout" value="Logout">
 </form>
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
