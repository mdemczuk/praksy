<?php

	session_start();
	$id = $_SESSION['courseid'];

	# checking if admin is logged in
	if(isset($_SESSION['admin']) && ($_SESSION['admin']==true)) {
		$admin_logged = true;
	}
	else {
		$admin_logged = false;
		header("Location: course.php?courseid=$id");
	}

	if(isset($_POST['save'])) {
		require_once "connect.php";
		$connection = mysqli_connect($host, $db_user, $db_pswd, $db_name);		# connecting to database
		#$connection = @new mysqli($host, $db_user, $db_pswd, $db_name);		

		if($connection->connect_errno!=0) {	
			echo "Error: ".$connection->connect_errno;
		}
		else {
			# retrieving data from form
			$title = $_POST['title'];
			$general_description = $_POST['description'];
			$matters = $_POST['matters'];
			$for_whom = $_POST['for_whom'];
			$results = $_POST['results'];
			$language = $_POST['language'];
			$additional_info = $_POST['additional_info'];

			echo "changing course info <br />";

			# query to update course info
			$sql = "UPDATE courses SET title = '".$title."', general_description = '".$general_description."', matters = '".$matters."', for_whom = '".$for_whom."', results = '".$results."', additional_info = '".$additional_info."', language = '".$language."' WHERE id = ".$id;

			$result = mysqli_query($connection, $sql);
			if($result) {
				$_SESSION['edit_message'] = '<span style="color:green"><b>Succesfully changed course information!</b></span>';
				unset($_SESSION['editcourseinfo']);
				header("Location: course.php?courseid=$id");
			}
			else {
				$_SESSION['edit_error'] = '<span style="color:red">'."Something went wrong, please try again.".'</span>';
				header("Location: editcourseinfo.php?courseid=$id");
			}

			$connection->close();
		}
	}

?>