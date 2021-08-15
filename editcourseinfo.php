<?php
	session_start();
	$id = $_GET['courseid'];
	$_SESSION['courseid'] = $id;

	# checking if admin is logged in
	if(isset($_SESSION['admin']) && ($_SESSION['admin']==true)) {
		$admin_logged = true;
		if(isset($_SESSION['edit_error'])){
			$edit_error = $_SESSION['edit_error'];
			echo "$edit_error <br />";
			unset($_SESSION['edit_error']);
		}
	}
	else {
		$admin_logged = false;
		header("Location: course.php?courseid=$id");
	}

	require_once "connect.php";

	$connection = @new mysqli($host, $db_user, $db_pswd, $db_name);		# connecting to database

	if($connection->connect_errno!=0) {	
		echo "Error: ".$connection->connect_errno;
	}
	else {
		if(($id > $_SESSION['no_courses']) || ($id < 1)) {					# checking what was passed in $_GET['courseid']
			header("Location: index.php");
			exit();
		}

		$sql = "SELECT * FROM courses WHERE id = ".$id;					# fetching data of the course with proper id

		if($result = @$connection->query($sql)) {
			$course_info = $result->fetch_assoc();
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
	<script src="ckeditor/ckeditor.js"></script>
	<script src="ckeditor/samples/sample.js"></script>

	<style>
		#container {
			padding: 10px;
		}
		.course_title {
			text-align: center;
			font-size: 32;
			padding: 10px;
		}
		.info_el {
			padding: 10px;
		}
	</style>

</head>

<body>
	
	<?php

		if(isset($_SESSION['editcourseinfo'])) { ?>
			<div id="container">
				<form action="editinfo.php" method="post">
					<div class="course_title">
						<?php
							$title = $course_info['title'];
							echo "<textarea type='text' name='title' cols=100 rows=2 required>".$title."</textarea>";
						?>
					</div>
					<div class="info_el">
						<?php
							$description = $course_info['general_description'];
							echo "<p><b>General description:</b></p>";
							echo "<textarea type='text' name='description' cols=200 rows=8 required>".$description."</textarea>";
						?>
					</div>
					<div class="info_el">
						<?php
							$matters = $course_info['matters'];
							echo "<p><b>Matters:</b></p>";
							echo "<textarea type='text' name='matters' cols=200 rows=10 required>".$matters."</textarea>";
						?>
					</div>
					<div class="info_el">
						<?php
							$for_whom = $course_info['for_whom'];	 
							echo "<p><b>For whom:</b></p>";
							echo "<textarea type='text' name='for_whom' cols=200 rows=8 required>".$for_whom."</textarea>";
						?>
					</div>
					<div class="info_el">
						<?php
							$results = $course_info['results'];
							echo "<p><b>Expected results after finishing this course:</b></p>";
							echo "<textarea type='text' name='results' cols=200 rows=8 required>".$results."</textarea>";
						?>
					</div>
					<div class="info_el">
						<?php
							$language = $course_info['language'];
							echo "<p><b>Language:</b></p>";
							echo "<textarea type='text' name='language' cols=20 rows=1 required>".$language."</textarea>";
						?>
					</div>
					<div class="info_el">
						<?php
							$additional_info = $course_info['additional_info'];
							echo "<p align='left'><b>Additional information:</b></p>";
							echo "<textarea type='text' name='additional_info' cols=200 rows=8>".$additional_info."</textarea>";
						?>
					</div>

					<div style="text-align: center;">
							<br/><input type="submit" value="Save changes" name="save" />
					</div>
				</form>
			</div>
			
		<?php
		}
	?>

	<p><a href="index.php">Home page</a></p>

</body>
</html>