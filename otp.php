<?php 

	$otp = $otpErr =  "";
	if($_SERVER["REQUEST_METHOD"] == "POST") {
		if (empty($_POST['otp'])){
			$otpErr = "Enter OTP";
		} else {
			$otp = $_POST['otp'];
			$sql = "SELECT id FROM users WHERE otp = :otp";
    			if ($stmt = $pdo->prepare($sql)){
    				$stmt->bindParam(":otp", $param_otp, PDO::PARAM_INT);
    				$param_otp = $otp;
    				if($stmt->execute()){
    					if ($stmt->rowCount() == 1) {
    						session_start();
    						// $_SESSION['email'] = $email;
    						
    						header("location: newpassword.php");
    					
    					} else {
    						$otpErr = "Incorrect otp.";
    					}

    				} else {
    					echo "Something went wrong. Please Try again.";
    					
    				}
    			}
		}
	}


?>

<!DOCTYPE html>
<html>
<head>
	<title>Enter OTP</title>
</head>
<body>
	<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" >
		<div>
			<label>Enter OTP</label>
			<input type="number" name="otp">
			<span class="error"></span>
		</div>
		<br>
		<div>
			<input type="submit" name="submit">
		</div>
	</form>

</body>
</html>