<?php 

	// Initialize session
	session_start();

	// Check to see if the user is already looged in, if yes then redirect user to welcome page
	if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
		header("location: dashboard.php");
		exit();
	}

	// Include config file
    require_once('config/connect.php');
	
	// Define variables and initialize with empty values
	$email = $password = $emailErr = $passwordErr = "";

	// Processing form data when form is submitted
	if ($_SERVER["REQUEST_METHOD"] == "POST"){

		function test_input($data){
		$data = trim($data);
		$data = stripslashes($data);
		$data = htmlspecialchars($data);
		return $data;
		}

		if (empty($_POST['email'])){
			$emailErr = "Please enter your email";
		} else {
			$email = test_input($_POST['email']);
			if (!filter_var($email, FILTER_VALIDATE_EMAIL)){
				$emailErr = "Invalid email format";
			}
		}

		if (empty($_POST['password'])){
			$passwordErr = "Please enter your password";
		} else {
			$password = $_POST['password'];
		}

		if (empty($emailErr) && empty($passwordErr)){
			// Prepare a select statement
			$sql = "SELECT id, email, password FROM users WHERE email = :email";

			if ($stmt = $pdo->prepare($sql)){
				// Bind variables to the prepared statement as parameters
				$stmt->bindParam(":email", $param_email, PDO::PARAM_STR);

				// Set Parameters
				$param_email = test_input($_POST['email']);
				// $param_email = $email; This might work as well since the email has been cleaned.

				// Attempt to execute prepared statement
				if ($stmt->execute()){
					// Check if email already exists, if yes then verify password
					if($stmt->rowCount() == 1){
						if ($row = $stmt->fetch()){
							$id = $row["id"];
							$email = $row["email"];
							$hashedPassword = $row["password"];

							// Verify Password
							if (password_verify($password, $hashedPassword)){
								// Password is correct, so start a new session
								session_start();
								// Store data in session variables
								$_SESSION["loggedin"] = true;
								$_SESSION["id"] = $id;
								$_SESSION["email"] = $email;

								// Redirect user to dashboardpage
								header("location: dashboard.php");
							} else {
								$passwordErr = "The password you entered is incorrect.";
							}

						}
					} else {
						$emailErr = "No account found with that email.";
					}

				} else {
					// Display an error message if username doesnt exists.
					echo "Ooops! Something went wrong. please try again later.";
					// Error page
					// header("location: error.php");
				}

				// Close statement
				unset($stmt);
			}

		}

		// Close connection
		unset($pdo);

	}


 ?>

 <!DOCTYPE html>
 <html>
 <head>
 	<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
 	<title>Login</title>
 	<style type="text/css">
    	.error {color: #FF0000;}
    </style>
 </head>
 <body>
 	<h2>Login Here</h2>
 	<p>Please fill in your credentials to login</p>
 	<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) ?>" method="POST">
 		<div>
 			<label>Email-ID</label>
 			<input type="text" name="email" value="<?php echo $email ?>">
 			<span class="error"><?php echo $emailErr ?></span>
 		</div>
 		<br>
 		<div>
 			<label>Password</label>
 			<input type="password" name="password">
 			<span class="error"><?php echo $passwordErr ?></span>
 		</div>
 		<br>
 		<div>
 			<input type="submit" name="submit" value="LOGIN">
 		</div>
 		<div>
 			<label>Remember Me</label>
 			<input type="checkbox" name="rememberMe">
 		</div>
 		<p><a href="forgotpassword.php">Forgot Password</a></p>
 		
 	</form>
 
 </body>
 </html>