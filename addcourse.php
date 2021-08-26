<?php

	session_start();				# function that allows the document to use session features

	if(!((isset($_SESSION['admin'])) && ($_SESSION['admin']==true))) {
		header('Location: index.php');
		exit();
	}
	if(isset($_SESSION['add_error'])){
		$add_error = $_SESSION['add_error'];
		echo "$add_error <br />";
		unset($_SESSION['add_error']);
	}

	if(isset($_POST['add'])) {
		# every field hasn't been filled
		$correct_info = true;

		# if course title hasn't been typed in
		if(strlen($_POST['title']) < 1) {
			$correct_info = false;
			$_SESSION['err_title'] = '<span style="color:red">Type in course title!<br /><br /></span>';
		}

		# checking course image
		$img_name = $_FILES['course_img']['name'];
		$img_size = $_FILES['course_img']['size'];
		$tmp_name = $_FILES['course_img']['tmp_name'];
		$error = $_FILES['course_img']['error'];
		if($error == 0) {
			if($img_size > 125000) {
				# checking size of the file
				$correct_info = false;
				$_SESSION['err_img'] = '<span style="color:red">Sorry, your file is too large.<br /><br /></span>';
			}
			else {
				# checking extension of the file
				$img_ex = pathinfo($img_name, PATHINFO_EXTENSION);
				$img_ex_lc = strtolower($img_ex);
				$allowed_ex = array("jpg", "jpeg", "png");
				if(!in_array($img_ex_lc, $allowed_ex)) {
					$correct_info = false;
					$_SESSION['err_img'] = '<span style="color:red">Sorry, cannot upload files of this type.<br /><br /></span>';
				}
			}
		}
		else {
			$correct_info = false;
			$_SESSION['err_img'] = '<span style="color:red">Error occurred during uploading course photo. Please try again.<br /><br /></span>';
		}

		# if general description hasn't been typed in
		if(strlen($_POST['description']) < 1) {
			$correct_info = false;
			$_SESSION['err_gen_descr'] = '<span style="color:red">Type in general description of the course!<br /><br /></span>';
		}

		# if matters haven't been typed in
		if(strlen($_POST['matters']) < 1) {
			$correct_info = false;
			$_SESSION['err_matters'] = '<span style="color:red">Type in matters of the course!<br /><br /></span>';
		}

		# if 'for whom' field hasn't been filled
		if(strlen($_POST['for_whom']) < 1) {
			$correct_info = false;
			$_SESSION['err_for_whom'] = '<span style="color:red">Type in for whom the course is!<br /><br /></span>';
		}

		# if expected results haven't been typed in
		if(strlen($_POST['results']) < 1) {
			$correct_info = false;
			$_SESSION['err_ex_results'] = '<span style="color:red">Type in expected results!<br /><br /></span>';
		}

		# if additional info hasn't been typed in
		if(strlen($_POST['additional_info']) < 1) {
			$correct_info = false;
			$_SESSION['err_add_info'] = '<span style="color:red">Type in additional info about the course!<br /><br /></span>';
		}

		# if language hasn't been typed in
		if(strlen($_POST['language']) < 1) {
			$correct_info = false;
			$_SESSION['err_language'] = '<span style="color:red">Type in language of the course!<br /><br /></span>';
		}

		if($correct_info) {
			# every field has been filled, course can be added to database

			require_once "connect.php";
			$connection = @new mysqli($host, $db_user, $db_pswd, $db_name);		# connecting to database

			if($connection->connect_errno!=0) {	
				echo "Error: ".$connection->connect_errno;
			}
			else {
				# fetching data from the form
				$title = $_POST['title'];
				$image = addslashes(file_get_contents($tmp_name));
				$general_description = $_POST['description'];
				$matters = $_POST['matters'];
				$for_whom = $_POST['for_whom'];
				$results = $_POST['results'];
				$additional_info = $_POST['additional_info'];
				$language = $_POST['language'];
				$main = $general_description;

				include_once('functions.php');
				# query to insert course's information to db
				$sql_insert = "INSERT INTO courses (title, course_img, general_description, matters, for_whom, results, additional_info, language, main) VALUES ('".$title."', '".$image."', '".$general_description."', '".$matters."', '".$for_whom."', '".$results."', '".$additional_info."', '".$language."', AES_ENCRYPT('".$main."', '".$key."'))";
				$_SESSION['no_courses'] = $_SESSION['no_courses'] + 1;

				$result = mysqli_query($connection, $sql_insert);
				if($result) {
					# find id of the new course
					$sql = "SELECT * FROM courses ORDER BY id DESC LIMIT 1";
					$res = mysqli_query($connection, $sql);
					if($res) {
						$new_course = $res->fetch_assoc();
						$last_id = $new_course['id'];
						$_SESSION['courseid'] = $last_id;
						$_SESSION['last_course_id'] = $last_id;
						$_SESSION['course_title'] = $title;
						$_SESSION['message'] = '<span style="color:green"><b>Succesfully added new course! You can now add lessons to this course and edit main page of the course.</b></span>';
						header("Location: coursecontent.php");
					}
					else {
						$_SESSION['add_error'] = '<span style="color:red">'."Something went wrong, please try again.".'</span>';
					}
				}
				else {
					$_SESSION['add_error'] = '<span style="color:red">'."Something went wrong, please try again.".'</span>';
				}
			}
		}
	}

?>

<!DOCTYPE HTML>
<html lang="pl">
<head>
	<meta charset="utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<title>E-learning platform</title>
	<style>
		#container {
			padding: 30px;
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

	<div id="container">
		<form method="post" enctype="multipart/form-data">
			<h1>Adding new course</h1>
			<h3>Please type in course info:</h3>
			<div class="info_el">
				<p><b>Title:</b></p>
				<textarea type='text' name='title' cols=80 rows=2></textarea>
				<?php
					if(isset($_SESSION['err_title'])) {
						echo "<br />".$_SESSION['err_title'];
						unset($_SESSION['err_title']);
					}
				?>
			</div>
			<div class="info_el">
				<p><b>Course image:</b></p>
				<input type='file' name='course_img'></input>
				<?php
					if(isset($_SESSION['err_img'])) {
						echo "<br />".$_SESSION['err_img'];
						unset($_SESSION['err_img']);
					}
				?>
			</div>
			<div class="info_el">
				<p><b>General description:</b></p>
				<textarea type='text' name='description' cols=180 rows=8></textarea>
				<?php
					if(isset($_SESSION['err_gen_descr'])) {
						echo "<br />".$_SESSION['err_gen_descr'];
						unset($_SESSION['err_gen_descr']);
					}
				?>
			</div>
			<div class="info_el">
				<p><b>Matters:</b></p>
				<textarea type='text' name='matters' cols=180 rows=10></textarea>
				<?php
					if(isset($_SESSION['err_matters'])) {
						echo "<br />".$_SESSION['err_matters'];
						unset($_SESSION['err_matters']);
					}
				?>
			</div>
			<div class="info_el">
				<p><b>For whom:</b></p>
				<textarea type='text' name='for_whom' cols=180 rows=8></textarea>
				<?php
					if(isset($_SESSION['err_for_whom'])) {
						echo "<br />".$_SESSION['err_for_whom'];
						unset($_SESSION['err_for_whom']);
					}
				?>
			</div>
			<div class="info_el">
				<p><b>Expected results after finishing this course:</b></p>
				<textarea type='text' name='results' cols=180 rows=8></textarea>
				<?php
					if(isset($_SESSION['err_ex_results'])) {
						echo "<br />".$_SESSION['err_ex_results'];
						unset($_SESSION['err_ex_results']);
					}
				?>
			</div>
			<div class="info_el">
				<p><b>Language:</b></p>
				<textarea type='text' name='language' cols=20 rows=1></textarea>
				<?php
					if(isset($_SESSION['err_language'])) {
						echo "<br />".$_SESSION['err_language'];
						unset($_SESSION['err_language']);
					}
				?>
			</div>
			<div class="info_el">
				<p><b>Additional information:</b></p>
				<textarea type='text' name='additional_info' cols=180 rows=8></textarea>
				<?php
					if(isset($_SESSION['err_add_info'])) {
						echo "<br />".$_SESSION['err_add_info'];
						unset($_SESSION['err_add_info']);
					}
				?>
			</div>

			<div style="text-align: center;">
				<br/><input type="submit" value="Add course" name="add" />
			</div>
		</form>
	</div>

</body>

</html>
