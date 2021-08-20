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
			$img_ok = true;
			$keep_old_img = false;
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
			elseif(empty($tmp_name)) {
				$keep_old_img = true;
			}
			else {
				$correct_info = false;
				$_SESSION['err_img'] = '<span style="color:red">Error occurred during uploading course photo. Please try again.<br /><br /></span>';
			}

			# retrieving data from form
			$title = $_POST['title'];
			$general_description = $_POST['description'];
			$matters = $_POST['matters'];
			$for_whom = $_POST['for_whom'];
			$results = $_POST['results'];
			$language = $_POST['language'];
			$additional_info = $_POST['additional_info'];

			# if image is correct
			if($img_ok && !$keep_old_img) {
				$course_img = addslashes(file_get_contents($tmp_name));
				# query to update course info
				$sql = "UPDATE courses SET title = '".$title."', course_img = '".$course_img."', general_description = '".$general_description."', matters = '".$matters."', for_whom = '".$for_whom."', results = '".$results."', additional_info = '".$additional_info."', language = '".$language."' WHERE id = ".$id;

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
			}
			elseif($keep_old_img) {
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
			}
			else {
				# if something is wrong with image
				header("Location: editcourseinfo.php?courseid=$id");
			}

			$connection->close();
		}
	}

?>