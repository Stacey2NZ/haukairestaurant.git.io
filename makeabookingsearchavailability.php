<?php
include "checksession.php";
checkUser(AC_ADMIN, AC_MANAGER, AC_AUTHENTICATED);
loginStatus();
?>
<!DOCTYPE HTML>

<html>
	<head>
		<title> Make A booking</title>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<meta name="description" content="" />
		<meta name="keywords" content="" />
		<!--[if lte IE 8]><script src="css/ie/html5shiv.js"></script><![endif]-->

	  <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
	  <link rel="stylesheet" href="https:/resources/demos/style.css">
  <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
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
		<script>
$( function() {
    var dateFormat = "yyyy-mm-dd",
      from = $( "#from" )
        .datepicker({
          defaultDate: "+1w",
          changeMonth: true,
          numberOfMonths: 1
        })
        .on( "change", function() {
          to.datepicker( "option", "minDate", getDate( this ) );
        }),
      to = $( "#to" ).datepicker({
        defaultDate: "+1w",
        changeMonth: true,
        numberOfMonths: 1
      })
      .on( "change", function() {
        from.datepicker( "option", "maxDate", getDate( this ) );
      });

    function getDate( element ) {
      var date;
      try {
        date = $.datepicker.parseDate( dateFormat, element.value );
      } catch( error ) {
        date = null;
      }

      return date;
    }
  } );
   function XMLHttpMethod(searchstr = "")	{
   var xmlhttp;
//step 1: check if the parameter string is empty
   if (searchstr == "") {
      document.getElementById("Result").innerHTML = "";
      return;
   }
//step 2: setup the XMLHttpRequest object for use
   if (window.XMLHttpRequest) {
     xmlhttp = new XMLHttpRequest();

//step 3: register an event handler for the AJAX response from the server
     xmlhttp.onreadystatechange=function()	{
       if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
         //console.log(xmlhttp.responseText);
	   var obj = JSON.parse(xmlhttp.responseText); //deserialise the JSON
         var result = "Results "+obj. total_count;
         document.getElementById("Result").innerHTML = $searchresult;
       }
    }
   }
//step 4: make the AJAX request
   xmlhttp.open("GET","roomsearch.php",true);
   xmlhttp.send();
}
  </script>
</head>
<body>
<?php
//for debugging
echo "<pre>"; var_dump($POST); echo "</pre>";
include "config.php";

//POST  check if we are saving data by checking for submit button
if ($_SERVER["REQUEST_METHOD"] == "POST"){
	include "config.php";
	$DBC = mysqli_connect("127.0.0.1", DBUSER, DBPASSWORD, DBDATABASE);

	if (mysqli_connect_errno()) {
		echo "Error: Unable to connect to MySQL. ".mysqli_connect_errno() ;
		exit;
	}
if (isset($_POST['submit']) and !empty($_POST['submit'])
    and ($_POST['submit'] == 'Add')) {
    $error = 0; //clear our error flag
    $msg = 'Error: ';
    if (isset($_POST['roomid']) and !empty($_POST['roomid'])
        and is_integer(intval($_POST['roomid']))) {
       $id = cleanInput($_POST['roomid']);
    } else {
       $error++; //bump the error flag
       $msg .= 'Invalid booking ID '; //append error message
       $id = 0;

}
	}
}
//validating incoming data?
$loadinroomdata = 'SELECT  roomID, roomname, roomtype, beds
					FROM `room`';

$loadinresult = mysqli_query($DBC,$loadinroomdata);

					if (mysqli_num_rows($loadinresult) > 0) {
						 while ($rows = mysqli_fetch_assoc($loadinresult)) {
							$roomid = $rows['roomID'];
							$roomname = $rows['roomname'];
							$roomtype = $rows['roomtype'];
							$beds = $rows['beds'];
						 }
					if (error == 0 && $id>0){

$query = "INSERT INTO `bookings` (roomID, customerID, checkinDate, checkoutDate, booking_extras) VALUES (?,?,?,?,?)";
$stmt = mysqli_prepare($DBC,$query); //prepare the query
mysqli_stmt_bind_param($stmt, 'i,i,s,s,s', $roomID, $customerID, $checkinDate, $checkoutDate, $extras);
mysqli_stmt_execute($stmt);
mysqli_stmt_close($stmt);
echo "<h2>Booking made</h2>";
}
else {
	echo "<h2>booking failed. Please try again</h2>".PHP_EOL;
}
mysqli_close($DBC);
}
?>
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
							<li class = "selected">Make A Booking / Search availability</li>
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
					<h2>Make a Booking / Search Availability</h2>
					<p>Book with us at OngaOnga B&B</p>
				</header>
				<div class="container">
					<div class="row 150%">
						<div class="4u 12u$(2)">

							<!-- Sidebar -->
								<section id="sidebar">
									<section>
		<h2> <a href = "index.php">[return to the home page]</a></h2>

		<form method = "POST" action = "MakeABookingSearchAvailability.php" id= "newBooking">
		<label id = "customerid" >CustomerID: </label> <!--validation needed here to check that the customerID matches the user logged in-->
		<input type = "text" name = "customerid" id = "customerID" required maxlength = "10" >
		<br>
		<select name = "rooms">
		<!-- What I was trying to do here was have a for loop that would retrieve each result from loadinresult query to echo each roomid, roomname and beds into the
				<select><option> drop down list. I couldn't get this to work. -->
		<?php
			for($i=1; $i<=mysqli_num_rows($rows); $i++)
		{
		    echo '<option value='.$roomid.'>'.$roomid.'</option>'.PHP_EOL;
		}

		?>
		</select>
		<br>
		<label id = "checkinDate" > Checkin date: </label>
		<input type = "date" name = "checkoutDate" id = "checkoutDate" required>
		</br>
		</br>
		<label id = "checkoutDate">Checkout date: </label>
		<input type = "date" name = "checkoutDate" id = "checkoutDate" required>
		</br>
		</br>
		<label id = "extras">Booking Extras </label>
		<textarea name="extras" rows="10" cols="30" maxlength = "100" placeholder = "maximum 100 words" id="extras"></textarea>
		</br>
		</br>
		<input type= "submit" name = "submit" value = "Add">
		<a href = "currentBookings.html">[Cancel]</a>
		<div id = "result"></div>
		</form>
		<h2> Search for room availability </h2>
		</br>
		<!-- I didn't understand the course content on AJAX and Json well enough to get this to work. The AJAX works, but getting it to show on the page is the problem. I will be consulting
		with facilitator for further clarification on this even though it will be past assignment deadline. I asked for clarification a couple of days ago, but no response-->
		<label id = "startDate" > start date: </label>
		<input type = "text" name = "from" id = "from">
		<label id = "checkinDateLabel" > end date: </label>
		<input type = "text" name = "to" id = "to">
		<button type="button" onclick="XMLHttpMethod()">Search availability</button> <div id = Result><?php echo $searchresult;?></div>
		</form>
									</section>
								</section>

						</div>
					</div>
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
