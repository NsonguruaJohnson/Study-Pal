<?php 
	
	// Initialize session
	session_start();

	// Check if the user is logged in, if not then redirect to login page
	// if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){	
	// 	header("location: login.php");
	// 	exit();
	// }
	if(!isset($_COOKIE['userid']) || !isset($_COOKIE['useremail'])){
		if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true){
		header("location: login.php");
			exit();
		}
		
		
	}


	

	// Include config file
	require_once("config/connect.php");

	// Define and initialize variables with empty values
	$Password = $passwordErr = $newPassword = $confirmPassword = $newPasswordErr = $confirmPasswordErr = "";

	// Processing form data when form is submitted
	if ($_SERVER["REQUEST_METHOD"] == "POST"){

		// Validate old password
		if (empty($_POST['password'])){
			$passwordErr = "Please enter old password";
		} else {
			$password = $_POST['password'];
			$sql = "SELECT id, password FROM users WHERE id = :id";
			if($stmt = $pdo->prepare($sql)){
				// Bind variables to the prepared statement as parameters
				$stmt->bindParam(":id", $param_id, PDO::PARAM_INT);
				// Set parameters
				$param_id = $_SESSION["id"];

				// Attempt to execute prepared statement
				if($stmt->execute()){
					// Check to see if id exists, then verify password
					if($stmt->rowCount() == 1){
						if ($row = $stmt->fetch()){
							$hashedPassword = $row["password"];
							if(!password_verify($password,$hashedPassword)){
								$passwordErr = "Password does not match this account. Check password";
							} else {
								session_start();
								$_SESSION = array();
								header("location: login.php");
								exit;
							}
						}
					} else {
						// no account found with that id. Login to retry.
						header("Location: login.php");
					}

				} else {
					echo "Something went wrong. Try again.";
					// Error page
					// header("location: error.php");
				}

			}

			// Close statement
			unset($stmt);

		}

		// Validate new password
		if (empty($_POST['newPassword'])){
			$newPasswordErr = "Please Enter a password";
		} else {
			$newPassword = $_POST['newPassword'];
			$pattern = "/^(?=.*[a-zA-Z])(?=.*[\W]).{6,}$/";
			if(!preg_match($pattern, $newPassword)){
    			$newPasswordErr = "Must contain at least 6 alphanumeric characters of uppercase, lowercase including symbols.";
    		}
		}
	}

	// if(isset($_POST['confirmPassword'])){
	// 	if(empty($_POST['confirmPassword'])){
	// 	$confirmPasswordErr = "Please confirm password";
	// } else {
	// 	$confirmPassword = $_POST['confirmPassword'];
	// 	if (empty($newPasswordErr) && ($newPassword != $confirmPassword)){
	// 		$confirmPasswordErr = "Password did not match";
	// 	}
	// }

	// }
	// Validate confirm password
	if(empty($_POST['confirmPassword'])){
		$confirmPasswordErr = "Please confirm password";
	} else {
		$confirmPassword = $_POST['confirmPassword'];
		if (empty($newPasswordErr) && ($newPassword != $confirmPassword)){
			$confirmPasswordErr = "Password did not match";
		}
	}

	// Check input errors before updating the db
	if (empty($newPasswordErr) && empty($confirmPasswordErr)){
		//prepare an update statement
		$sql = "UPDATE users SET password = :password WHERE id = :id";
		if($stmt = $pdo->prepare($sql)){
			// Bind variables to the prepared statement as parameters
			$stmt->bindParam(":password", $param_password, PDO::PARAM_STR);
			$stmt->bindParam(":id", $param_id, PDO::PARAM_INT);

			// Set Parameters
			$param_password = password_hash($newPassword, PASSWORD_DEFAULT);
			$param_id = $_SESSION["id"];

			// Attempt to execute the prepared statement
			if($stmt->execute()){
				// Password updated successfully. Destroy the session and redirect to login page.
				session_destroy();
				header("location: login.php");
				exit();
			} else {
				echo "Oops! Something went wrong. Please try again later.";
				// Error.php
				// header("location: error.php");
			}
		
		}

		// Close statement
		unset($stmt);
	}

	// Close connection
	unset($pdo);

?>

<!DOCTYPE html>
<html>
<head>
	<title>Change Password</title>
	<style type="text/css">
    	.error {color: #FF0000;}
    </style>
</head>
<body>
	<h2>Reset Password</h2>
	<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" >
		<div>
			<label>Old Password</label>
			<input type="password" name="password">
			<span class="error"><?php echo $passwordErr ?></span>
		</div>
		<br>
		<div>
			<label>New Password</label>
			<input type="password" name="newPassword">
			<span class="error"><?php echo $newPasswordErr ?></span>
		</div>
		<br>
		<div>
			<label>Confirm New Password</label>
			<input type="password" name="confirmPassword">
			<span class="error"><?php echo $confirmPasswordErr ?></span>
		</div>
		<br>
		<div>
			<input type="submit" name="submit" value="Confirm">
		</div>
	</form>

</body>
</html>