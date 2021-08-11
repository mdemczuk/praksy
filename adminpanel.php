<?php
	session_start();

	if(!isset($_SESSION['loggedin']) || !((isset($_SESSION['admin'])) && ($_SESSION['admin']==true))) {
		header('Location: index.php');
		exit();
	}

?>

<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<title>Ideas4learning</title>
</head>

<body>

	<h2>Administration panel</h2>

	Add course <br />
	Delete course <br />
	Change course info/content <br />
	
	<p><a href="index.php">Home page</a>&nbsp;&nbsp;&nbsp;&nbsp;<a href="logout.php">Log out</a></p>

</body>

</html>
