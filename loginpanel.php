<?php
	session_start();

	$id = $_SESSION['courseid'];
	if(((isset($_SESSION["loggedin$id"])) && ($_SESSION["loggedin$id"]==true)) || (isset($_SESSION['admin']) && ($_SESSION['admin']==true))){		# checking if the person is already logged in for this course
		header("Location: coursecontent.php?courseid=$id");
		exit();
	}
?>

<!DOCTYPE HTML>
<html lang="pl">
<head>
	<meta charset="utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<title>Log In - Ideas4Learning</title>
</head>

<body>
	<h3>Login for the course</h3>

	<form action="login.php" method="post">
		E-mail:<br/><input type="text" name="email" /><br/>
		Course password:<br/><input type="password" name="pswd" /><br/>
		<br/><input type="submit" value="Log in" />
	</form>

	<?php
		# if variable $_SESSION['error'] exists in this session, the warning will be shown below the login form
		if(isset($_SESSION['login_error'])) {
			echo $_SESSION['login_error'];
			unset($_SESSION['login_error']);
		}
	?>

</body>
</html>
