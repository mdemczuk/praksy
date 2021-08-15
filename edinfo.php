<?php
	session_start();

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
		$sql = "SELECT id, title FROM courses";					# sql query to find data of all of the courses

		if($result = @$connection->query($sql)) {
			$_SESSION['no_courses'] = $result->num_rows;

			$no_courses = $result->num_rows;
			$titles = array();
			$ids = array();
			if($no_courses > 0) {
				for($x = 0; $x < $no_courses; $x++) {					# fetch data from all rows
					$course = $result->fetch_assoc();
					array_push($titles, $course['title']);
					array_push($ids, $course['id']);
				}
			} 
		}

		$connection->close();
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

	<h2>Choose which course's info you want to change:</h2>

	<?php
		if($no_courses > 0) {
			$_SESSION['editcourseinfo'] = true;
			for($x = 0; $x < $no_courses; $x++) {
				$bin = $titles[$x];
				echo "<a href='editcourseinfo.php?courseid={$ids[$x]}'><h3>$bin</h3></a>";
			}
		}
		else {
			echo "Sorry, no courses available :( <br />";
		}
	?>

	<br />
	<p>
		<a href="adminpanel.php">Administration panel</a>&nbsp;&nbsp;&nbsp;&nbsp;
		<a href="index.php">Home page</a>&nbsp;&nbsp;&nbsp;&nbsp;
		<a href="logout.php">Log out</a>
	</p>

</body>

</html>