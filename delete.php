<?php
	session_start();

	# checking if admin is logged in
	if(!((isset($_SESSION['admin'])) && ($_SESSION['admin']==true))) {
		header('Location: index.php');
		exit();
	}

	require_once "connect.php";

	$connection = @new mysqli($host, $db_user, $db_pswd, $db_name);		# connect do db

	if($connection->connect_errno!=0) {									# if the connection hasn't been established
		echo "Error: ".$connection->connect_errno;
	}
	else {																# if the connection has been established
		$id = $_GET['courseid'];										# getting id of the course that is going to be deleted

		# deleting course, lessons and access to this course
		$sql_access = "DELETE FROM access WHERE course_id = ".$id;
		$sql_lessons = "DELETE FROM lessons WHERE course_id = ".$id;
		$sql_course = "DELETE FROM courses WHERE id = ".$id;
		@$connection->query($sql_access);
		@$connection->query($sql_lessons);
		@$connection->query($sql_course);

		# finding course which has highest id
		$sql_maxid = "SELECT id FROM courses ORDER BY id DESC LIMIT 1";
		$result = @$connection->query($sql_maxid);
		$r = $result->fetch_assoc();
		if($result->num_rows > 0) {
			$_SESSION['last_course_id'] = $r['id'];
		}
		else {
			$_SESSION['last_course_id'] = 0;
		}

		$connection->close();											# closing connection to db
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

	Course successfully deleted.

	<p>
		<a href="adminpanel.php">Administration panel</a>&nbsp;&nbsp;&nbsp;&nbsp;
		<a href="index.php">Home page</a>&nbsp;&nbsp;&nbsp;&nbsp;
		<a href="logout.php">Log out</a>
	</p>

</body>

</html>
