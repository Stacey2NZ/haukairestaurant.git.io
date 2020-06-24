<?php
include "checksession.php";
checkUser(AC_ADMIN, AC_MANAGER);
loginStatus();
?>
<!DOCTYPE HTML>

<html>
	<head>
		<title>Update customers details </title>
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
			 echo "Error: Unable to connect to MYSQL. " .mysqli_connect_error();
			 exit; //stop processing the page further
		 };

		 //This line is for debugging purposes so that we can see the actual POST/GET data
		 echo "<pre>";
		 var_dump($_POST); var_dump($_GET);
		 echo "</pre>";

		 //function to clean input but not vaildate datatype and content
		 function cleanInput($data) {
			 return htmlspecialchars(stripslashes(trim($data)));
		 };
		 if ($_SERVER["REQUEST_METHOD"] == "GET") {
			 $id = $_GET['id'];
			 if (empty($id) or !is_numeric($id)) {
				 echo "<h2> Invalid customer number</h2>"; //simple error feedback
				 exit;
			 }
		 }
		 // the data was sent using a form therefore we use the $_POST instead of $_GET
		 // check if we are saving data first by checking if the submit button exists in the array
		 if (isset($_POST['submit']) and !empty($_POST['submit'])
		    and ($_POST['submit'] == 'Update'))
		 {
			 //validate incoming data
			 $error = 0;
			 $msg = 'Error: ';

			 if (isset($_POST['id']) and !empty($_POST['id']) and is_integer(intval($_POST['id'])))
			 {
				 $id = cleaninput($_POST['id']);
			 }
			 else {
				 $error++;
				 $msg .= 'Invalid Input';
				 $id = 0;
			 }
			 //firstname
			    if (isset($_POST['firstname']) and !empty($_POST['firstname'])
		        and is_string($_POST['firstname'])) {
		       $firstname = cleanInput($_POST['firstname']);
		    } else {
		       $error++; //bump the error flag
		       $msg .= 'Invalid input '; //append error message
		       $firstname = 0;
		    }
			 //lastname
			    if (isset($_POST['lastname']) and !empty($_POST['lastname'])
		        and is_string($_POST['firstname'])) {
		       $lastname = cleanInput($_POST['lastname']);
		    } else {
		       $error++; //bump the error flag
		       $msg .= 'Invalid input '; //append error message
		       $lastname = 0;
		    }
			 //email
			 if (isset($_POST['email']) and !empty($_POST['email']) and is_string($_POST['email']))
			 {
				 $email = cleanInput($_POST['email']);
			 }
			 else {
				 $error++;
				 $msg .= 'Invalid Input';
				 $email = 0;
			 }
			 //username
			 if (isset($_POST['username']) and !empty($_POST['username']) and is_string($_POST['username']))
			 {
				 $username = cleaninput($_POST['username']);
			 }
			 else {
				 $error++;
				 $msg .= 'Invalid Input';
				 $username = 0;
			 }
			if ($error == 0 and $id > 0)
			{
		$query = "UPDATE `customer` SET firstname = ?, lastname = ?, email = ?,username = ? WHERE customerID = ?";
		$stmt = mysqli_prepare($DBC,$query); //prepare the query
		mysqli_stmt_bind_param($stmt, 'ssssi', $firstname, $lastname, $email, $username, $id);
		mysqli_stmt_execute($stmt);
		mysqli_stmt_close($stmt);
		echo "<h2>Customer details updated</h2>";
		//header('Location: http://localhost/bit608/listmembers.php', true, 301);
		die();
			}
			else{
			echo "<h2>$msg.PHP_EOL</h2>";
			}
		 }
		$query = 'SELECT customerID, firstname, lastname, email, username FROM `customer` WHERE customerID = '.$id;
		$result = mysqli_query($DBC,$query);
		$rowcount = mysqli_num_rows($result);
		if ($rowcount > 0) {
		$row = mysqli_fetch_assoc($result);
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
					<h2>Customer View</h2>
				</header>
				<div class="container">
						<section id="content">
		<h2><a href='listcustomers.php'>[Return to the Customer listing]</a><a href='index.php'>[Return to the main page]</a></h2>
		<h1> Customer Update </h1>
<form method ="POST" action = "editcustomer.php">
<input type = "hidden" name = "id" value = "<?php echo $id; ?>">
<p>
    <label for="firstname">Name: </label>
    <input type="text" id="firstname" name="firstname" minlength="5"
           maxlength="50" required value="<?php echo $row['firstname']; ?>">
  </p>
  <p>
    <label for="lastname">Name: </label>
    <input type="text" id="lastname" name="lastname" minlength="5"
           maxlength="50" required value="<?php echo $row['lastname']; ?>"> >
  </p>
  <p>
    <label for="email">Email: </label>
    <input type="email" id="email" name="email" maxlength="100"
           size="50" required value="<?php echo $row['email']; ?>"> >
   </p>
  <p>
    <label for="username">Username: </label>
    <input type="text" id="username" name="username" minlength="8"
           maxlength="32" required  value="<?php echo $row['username']; ?>"> >
  </p>

   <input type="submit" name="submit" value="Update">
 </form>
<?php
 } else {
  echo "<h2>Customer not found with that ID</h2>"; //simple error feedback
}
mysqli_close($DBC); //close the connection once done
?>
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
