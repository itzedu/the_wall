<?php 
	session_start();
?>

<!doctype html>
<html>
<head>
	<meta charset="utf-8">
	<title>The Wall</title>
	<link rel="stylesheet" type="text/css" href="style/main.css">
</head>
<body>
	<div id='container'>
<?php if (isset($_SESSION['error_messages'])) 
			{ foreach ($_SESSION['error_messages'] as $error) 
				{ 
					echo "<p id='red' class='msg'> * {$error}</p>";
				}
				unset($_SESSION['error_messages']);
			} 
			if (isset($_SESSION['success_message']))
			{
				foreach ($_SESSION['success_message'] as $success)
				{
					echo "<p id='green' class='msg'> * {$success}</p>";
				}	
				unset($_SESSION['success_message']);
			} ?>
		<h1>Welcome to the Coding Dojo Wall</h1>
		<div id='register' class="box">
	 		<h2>Membership</h2>
		 	<form action="process.php" method="post">
		 		<input type="hidden" name="action" value="register">
		 		<p><label>First Name: </label><input type="text" name="first_name" placeholder="First name"></p>
		 		<p><label>Last Name:	</label><input type="text" name="last_name" placeholder="Last name"></p>
		 		<p><label>Email Address: </label><input type="text" name="email" placeholder="Email Address"></p>
		 		<p><label>Password: </label><input type="password" name="password" placeholder="Least 6 characters"></p>
		 		<p><label>Confirm Password: </label><input type="password" name="confirm_password" placeholder="Least 6 characters"></p>
		 		<input type="submit" value="Register now">
		 	</form>
		</div> <!-- end of register -->

		<div id='login' class='box'>
			<h2>Login</h2>
			<form action="process.php" method="post">
				<input type="hidden" name="action" value="login">
				<p><label>Email Address: </label><input type="text" name="email" placeholder="Email Address"></p>
				<p><label>Password: </label><input type="password" name="password" placeholder="********"></p>
				 <input type="submit" value="Login">
			</form>
		</div> <!-- end of login -->

		<img src="images/logo.png">
	</div> <!-- end of container -->
</body>
</html>