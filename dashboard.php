<?php 

	// Initialize session
	session_start();

	// Check if the user is loggen in, if not then redirect the user to login page
	if(!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true){
		header('location: login.php');
		exit();
	}

?>

<!DOCTYPE html>
<html>
<head>
	<title>Dashboard</title>
</head>
<body>
	<h1>Hello <?php echo $_SESSION['email'] ?></h1>
	<p>To change password, click <a href="change-password.php">here</a></p>

	<h3>Let's here from you</h3>
	<p><a href="contact.php">Contact Us</a></p>
</body>
</html>