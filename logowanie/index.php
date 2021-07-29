<?php
	session_start();

	# when someone is already logged in
	if((isset($_SESSION['loggedin'])) && ($_SESSION['loggedin']==true)){
		header('Location:main.php');
		exit();
	}
?>
<!DOCTYPE HTML>
<html lang="pl">
<head>
	<meta charset="utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<title>E-learning platform - log in</title>
</head>

<body>
	<h2>Welcome!</h2>
	Please log in:<br /><br />

	<form action="login.php" method="post">
		E-mail:<br /><input type="text" name="email" /><br />
		Password:<br /><input type="password" name="pswd" /><br />
		<input type="submit" value="Log in" />

	</form>

<?php
	# if variable $_SESSION['error'] exists in this session, the warning will be shown below the login form
	if(isset($_SESSION['error'])) echo $_SESSION['error'];

?>

</body>

</html>
