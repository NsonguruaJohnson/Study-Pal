<?php  





?>

<!DOCTYPE html>
<html>
<head>
	<title>Set Password</title>
</head>
<body>
	<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" >
		<div>
			<label>Enter New Password</label>
			<input type="password" name="Password">
			<span class="error"></span>
		</div>
		<br>
		<div>
			<label> Confirm Password</label>
			<input type="password" name="newPassword">
			<span class="error"></span>
		</div>
		<br>
		<input type="submit" name="submit">
		
	</form>

</body>
</html>