<?php  
	
	$email = $emailErr =  "";
	// Processing form data when form is submitted
	if ($_SERVER["REQUEST_METHOD"] == "POST"){

		function test_input($data){
		$data = trim($data);
		$data = stripslashes($data);
		$data = htmlspecialchars($data);
		return $data;
		}		

		if (empty($_POST['email'])){
    		$emailErr = "Please Enter an email address";
    	} else {
    		$email = test_input($_POST['email']);
    		if (!filter_var($email, FILTER_VALIDATE_EMAIL)){
    			$emailErr = "Invalid email format";
    		} else {
    			$sql = "SELECT id FROM users WHERE email = :email";
    			if ($stmt = $pdo->prepare($sql)){
    				$stmt->bindParam(":email", $param_email, PDO::PARAM_STR);
    				$param_email = $email;
    				if($stmt->execute()){
    					if ($stmt->rowCount() == 1) {
    						session_start();
    						$_SESSION['email'] = $email;
    						// Send msg
    						header("location: otp.php");
    					
    					} else {
    						$emailErr = "Incorrect email.";
    					}

    				} else {
    					echo "Something went wrong. Please Try again.";
    					
    				}
    			}
    		}
    	}
	}



?>

<!DOCTYPE html>
<html>
<head>
	<title>Forgot Password</title>
</head>
<body>
	<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" >
		<div>
			<label>Email</label>
			<input type="text" name="email" value="<?php echo $email ?>">
			<span class="error"></span>
		</div>
		<br>
		<input type="submit" name="submit" value="Generate OTP">
		
	</form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" >
	<div>
		<label>Enter OTP</label>
		<input type="number" name="otp">
		<span class="error"></span>
	</div>
	<br>
	<input type="submit" name="submit" value="Submit">

	<form>
		
	</form>
</body>
</html>