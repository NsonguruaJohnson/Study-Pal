<?php 

	// Initialize session
	session_start();

	// Check to see if the cookie is set, if not, redirect to login page
	// if(isset($_COOKIE['userid']) || isset($_COOKIE['useremail'])){
	// 	$user = true;

	// 	// header('location: login.php');
	// }

	
	// Check if the user is logged in, if not then redirect the user to login page
	// if(!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true){
	// 	header('location: login.php');
	// 	exit();
	
	// }

	// if(!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true){
	// 	if (!isset($_COOKIE['userid']) || !isset($_COOKIE['useremail'])){
	// 		header('location: login.php');
	// 		exit();
	// 	}

	// 	if (isset($_COOKIE['userid']) || isset($_COOKIE['useremail'])) {
	// 		header('location: dashboard.php');
	// 	}
		
	// 	exit();
	
	// // }
	if(!isset($_COOKIE['userid']) || !isset($_COOKIE['useremail'])){
		if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true){
		header("location: login.php");
			exit();
		}
		
		
	}


?>

<!DOCTYPE html>
<html>
<head>
	<title>Dashboard</title>
</head>
<body>
	<h1>Hello <?php echo $_COOKIE['useremail'] ?></h1>
	<p>To change password, click <a href="change-password.php">here</a></p>

	<h3>Let's here from you</h3>
	<p><a href="contact.php">Contact Us</a></p>
	<h3>Logout Here</h3>
	<p><a href="logout.php">Logout</a></p>
</body>
</html>