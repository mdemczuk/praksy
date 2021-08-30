<?php
	session_start();

	# if admin is not logged in
	if(!((isset($_SESSION['admin'])) && ($_SESSION['admin']==true))) {
		header('Location: index.php');
		exit();
	}

	if(isset($_SESSION['password_change_message'])){
		$message = $_SESSION['password_change_message'];
		echo "$message <br/>";
		unset($_SESSION['password_change_message']);
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

	<div style="float: left;">
		<h2>Administration panel</h2>
	</div>
	<div style="float: right; padding: 20px;">
		<a href='passchange.php'>Change password</a>
	</div>
	<div style="clear: both;"></div>

	<a href="addcourse.php">Add course</a> <br />
	<a href="delcourse.php">Delete course</a> <br />
	<a href="edinfo.php">Change course info</a> <br />
	<a href="editcontent.php">Change course content</a> <br />

	<?php
		unset($_SESSION['editcourseinfo']);
	?>

	<p><a href="index.php">Home page</a>&nbsp;&nbsp;&nbsp;&nbsp;<a href="logout.php">Log out</a></p>

</body>

</html>
