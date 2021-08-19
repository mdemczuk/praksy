  
<?php
	session_start();

	# if admin is not logged in
	if(!((isset($_SESSION['admin'])) && ($_SESSION['admin']==true))) {
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