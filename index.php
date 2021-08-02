<?php

	session_start();

	require_once "connect.php";

	$connection = @new mysqli($host, $db_user, $db_pswd, $db_name);

	if($connection->connect_errno!=0) {	
		echo "Error: ".$connection->connect_errno;
	}

	else {
		$sql = "SELECT * FROM courses";

		if($result = @$connection->query($sql)) {
			$_SESSION['no_courses'] = $result->num_rows;
		}

		$sql = "SELECT id, title FROM courses";

		if($result = @$connection->query($sql)) {
			$no_courses = $result->num_rows;
			$titles = array();
			$ids = array();
			if($no_courses > 0) {
				for($x = 0; $x < $no_courses; $x++) {
					$course = $result->fetch_assoc();
					array_push($titles, $course['title']);
					array_push($ids, $course['id']);
				}
			} 
		}

		$connection->close();
	}

?>

<!DOCTYPE HTML>
<html lang="pl">
<head>
	<meta charset="utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<title>E-learning platform</title>
</head>

<body>
	<h2>All courses:</h2>

	<?php
		if($no_courses > 0) {
				for($x = 0; $x < $no_courses; $x++) {
					echo "<p><b>".$titles[$x]."</b> <br />"."[<a href='course.php?courseid={$ids[$x]}'>More</a>]</p>";
				}
			}
			else {
				echo "Sorry, no courses available :(";
			}
	?>

</body>

</html>