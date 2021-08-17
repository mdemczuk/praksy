<?php
	session_start();

	# checking if admin is logged in
	if(isset($_SESSION['admin']) && ($_SESSION['admin']==true)) {
		$admin_logged = true;
		if(isset($_SESSION['edit_lesson_error'])){
			$edit_lesson_error = $_SESSION['edit_lesson_error'];
			echo "$edit_lesson_error <br />";
			unset($_SESSION['edit_lesson_error']);
		}
	}
	else {
		$admin_logged = false;
		header('Location: index.php');
		exit();
	}

	require_once "connect.php";
	$connection = mysqli_connect($host, $db_user, $db_pswd, $db_name);		# connecting to database

	if(isset($_POST['save'])) {
		if($connection->connect_errno!=0) {	
			echo "Error: ".$connection->connect_errno;
		}
		else {
			# retrieving data from form
			$lesson_id = $_SESSION['lessonid'];
			$lesson_name = $_POST['lesson_name'];
			$editor_data = $_POST['content'];
			$lesson_num = $_SESSION['lesson_num'];

			# query to update lesson's content
			$sql = "UPDATE lessons SET name = '".$lesson_name."', content = '".$editor_data."' WHERE id = ".$lesson_id;

			$result = mysqli_query($connection, $sql);
			if($result) {
				$_SESSION['lesson_message'] = '<span style="color:green"><b>Succesfully changed lesson content!</b></span>';
			}
			else {
				$_SESSION['edit_lesson_error'] = '<span style="color:red">'."Something went wrong, please try again.".'</span>';
			}
			header("Location: coursecontent.php?num=$lesson_num");
		}
	}

	if(isset($_POST['save_main'])) {
		if($connection->connect_errno!=0) {	
			echo "Error: ".$connection->connect_errno;
		}
		else {
			# retrieving data from form
			$course_id = $_SESSION['courseid'];
			$editor_data = $_POST['main'];
			$lesson_num = $_SESSION['lesson_num'];

			# query to update course's main page
			$sql = "UPDATE courses SET main = '".$editor_data."' WHERE id = ".$course_id;

			$result = mysqli_query($connection, $sql);
			if($result) {
				$_SESSION['lesson_message'] = '<span style="color:green"><b>Succesfully changed content of the main page!</b></span>';
			}
			else {
				$_SESSION['edit_lesson_error'] = '<span style="color:red">'."Something went wrong, please try again :(.".'</span>';
			}
			header("Location: coursecontent.php");
		}
	}

	$connection->close();
?>