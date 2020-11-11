<?php  

	require_once("config/connect.php");
	// Declare and inititialize variables with empty values
		$fname = $email = $message = $fnameErr = $emailErr = $messageErr = "";

	if ($_SERVER['REQUEST_METHOD'] == "POST"){

		function test_input($data){
		$data = trim($data);
		$data = stripslashes($data);
		$data = htmlspecialchars($data);
		return $data;
		}		
		
		// Validate name
		if (empty($_POST['fname'])){
			$fnameErr = "Input your name";
		} else {
			$fname = test_input($_POST['fname']);
			if (!preg_match("/^[a-zA-Z]*$/", $fname)){
				$fnameErr = "Only letters and white spaces allowed.";
			}
		}

		// if (!preg_match("/^[a-zA-Z]*$/", $fname)){
		// 	$fnameErr = "Only letters and white spaces allowed.";
		// }


		// Validate email
		if (empty($_POST['email'])){
			$emailErr = "Enter your email";
		} else {
			$email = test_input($_POST['email']);
    		if (!filter_var($email, FILTER_VALIDATE_EMAIL)){
    			$emailErr = "Invalid email format";
    		}
		}

		// Validate message
		if (empty($_POST['message'])){
			$messageErr = "Cannot be blank.";
		} else {
			$message = test_input($_POST['message']);
		}


		if (empty($fnameErr) && empty($emailErr) && empty($messageErr)){
			mail();
		}
	}


?>

<!DOCTYPE html>
<html>
<head>
	<title>Contact Us</title>
	<style type="text/css">
    	.error {color: #FF0000;}
    </style>
</head>
<body>
<p>To contact us, fill the form below</p>

<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) ?>" method="POST" >

	<div>
	<label>Name</label>
	<input type="text" name="fname" value="<?php echo $fname ?>">
	<span class="error"><?php echo $fnameErr ?></span>
	</div>

	<br>

	<div>
		<label>Email</label>
		<input type="text" name="email" value="<?php echo $email ?>">
		<span class="error"><?php echo $emailErr ?></span>
	</div>

	<br>

	<div>
		<label>Messages</label>
		<textarea>
			<?php echo $message ?>
		</textarea>
		<span class="error"><?php echo $messageErr ?></span>
	</div>

	<br>

	<div>
		<input type="submit" name="submit" value="Send Message">
	</div>


</form>

</body>
</html>