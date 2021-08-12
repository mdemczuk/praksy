<?php

	session_start();

	# checking if admin is logged in
	if(isset($_SESSION['admin']) && ($_SESSION['admin']==true)) {
		$admin_logged = true;
	}
	else {
		$admin_logged = false;
	}

	require_once "connect.php";

	$connection = @new mysqli($host, $db_user, $db_pswd, $db_name);		# connect do db

	if($connection->connect_errno!=0) {					# if the connection hasn't been established
		echo "Error: ".$connection->connect_errno;
	}
	else {									# if the connection has been established
		$sql = "SELECT id, title, course_img FROM courses";		# sql query to find data of all of the courses

		if($result = @$connection->query($sql)) {
			$_SESSION['no_courses'] = $result->num_rows;

			$no_courses = $result->num_rows;
			$titles = array();
			$ids = array();
			$images = array();
			if($no_courses > 0) {
				for($x = 0; $x < $no_courses; $x++) {		# fetch data from all rows
					$course = $result->fetch_assoc();
					array_push($titles, $course['title']);
					array_push($ids, $course['id']);
					array_push($images, $course['course_img']);
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
	<title>Ideas4learning</title>
	<meta name="keywords" content="bioinformatics, bioinfo, courses, IT, programming, biology, genomics">

	<style>
		h1 {
			text-align: center;
		}
		a {
			text-decoration: none;			/* hyperlink is not underlined */
		}
		a:visited {					/* visited hyperlink */
			color: #404040;
		}
		#course {					/* div named 'course' */
			text-align: center;
			text-decoration: none;
		}
		#course:hover {
			color: white;
			background-color: #404040;
		}
	</style>

</head>

<body>
	<h1>All courses:</h1>

	<?php
		if($no_courses > 0) {
				for($x = 0; $x < $no_courses; $x++) {
					$bin=$titles[$x];
					# display a block containing course title and course image; whole block is a hyperlink to another page
					echo "<a href='course.php?courseid={$ids[$x]}'>
							<div id='course'>".
							'<img src="data:image;base64,'.base64_encode($images[$x]).'" alt="Image">'.
							"<h2>$bin</h2> </div>'
						</a>";
				}
			}
			else {
				echo "Sorry, no courses available :(";
			}
	?>

</body>

</html>
