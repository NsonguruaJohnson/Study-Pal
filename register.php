<?php

	// Include config file
    require_once('config/connect.php');
   // Define variables and initializ with empty values.

    $email = $password = $confirmPasword = $emailErr = $passwordErr = $confirmPasswordErr = "";


    if ($_SERVER['REQUEST_METHOD'] == "POST"){

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
    						$emailErr = "This email is already taken";
    					// } else {
    					// 	$email = test_input($_POST['email']);
    					}
    				} else {
    					echo "Something went wrong. Please Try again.";
    					// Error page
    					// header("location:error.php");
    				}
    			}
    		}
    	}


    	if (empty($_POST['password'])){
    		$passwordErr = "Please enter a Password";
    	} else {
    		$password = $_POST['password'];
    		$pattern = "/^(?=.*[a-zA-Z])(?=.*[\W]).{6,}$/";
    		if(!preg_match($pattern, $password)){
    			$passwordErr = "Must contain at least 6 alphanumeric characters of uppercase, lowercase including symbols.";
    		}
    	}

    	// Validate Confirm Password
    	if (empty($_POST['confirmPassword'])) {
    		$confirmPasswordErr = "Please confirm password";
    	} else {
    		$confirmPassword = $_POST['confirmPassword'];
    		if (empty($passwordErr) && ($password != $confirmPassword)){
    			$confirmPasswordErr = "Password did not match";

    		}
    	}


    	if (empty($emailErr) && empty($passwordErr) && empty($confirmPasswordErr)){
    		// Prepare an insert statement
    		$sql = "INSERT INTO users (email, password) VALUES (:email, :password)";
    		if ($stmt = $pdo->prepare($sql)){
    			// Bind variables to the prepared statement as parameters
    			$stmt->bindParam(":email", $param_email, PDO::PARAM_STR);
    			$stmt->bindParam(":password", $param_password, PDO::PARAM_STR);

    			// Set Parameters
    			$param_email = $email;
    			$param_password = password_hash($password, PASSWORD_DEFAULT);

    			// Attempt to execute the prepared statement
    			if ($stmt->execute()){
    				// Redirect to login page
    				header("location: login.php");

    			} else {
    				echo "Something went wrong. Please try again later.";
    				// error page
    				// header("location: error.php");
    			}

    			// Close statement
    			unset($stmt);
    		}

    	}

    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <style type="text/css">
    	.error {color: #FF0000;}
    </style>
</head>
<body>
<form action="<?php echo htmlspecialchars ($_SERVER['PHP_SELF'])?>" method="POST">
	<div>
		<label>Email</label>
		<input type="text" name="email" value="<?php echo $email; ?>">
		<span class="error"><?php echo $emailErr; ?></span>
	</div>
	<br>
	<div>
		<label>Password</label>
		<input type="password" name="password">
		<span class="error"><?php echo $passwordErr; ?></span>
	</div>
	<br>
	<div>
		<label>Confirm Password</label>
		<input type="password" name="confirmPassword">
		<span class="error"><?php echo $confirmPasswordErr; ?></span>
	</div>
	<br>
	<div>
		<input type="submit" name="submit" value="Signup">
	</div>

</form>
    
</body>
</html>