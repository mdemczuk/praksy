<?php
	#	1. Check if the current password matches the one in the database.
	#	2. If it doesn't, set a proper error message to display on the page with the form under the "current password" field.
	#	3. If it does, check if the new password meets the requirements of the password.
	#	4. If it doesn't, set a proper error message to display on the page with the form under the "new password" field.
	#	5. If it does, check if the new password and the confirmed password are the same.
	#	6. If they aren't, set a proper error message to display on the page with the form under the "confirm password" field.
	#	7. If they are, update the course password in the database and display a message that password change was successful.

	session_start();

	$id = $_SESSION['courseid'];
	if((!isset($_SESSION["loggedin$id"])) && ($_SESSION["loggedin$id"]!=true)){		# checking if the person is logged in for this course
		header("Location: course.php?courseid=$id");
		exit();
	}

	require_once "connect.php";
	$connection = @new mysqli($host, $db_user, $db_pswd, $db_name);		# connecting to database
	if($connection->connect_errno!=0) {	
		echo "Error: ".$connection->connect_errno;
	}

	else {
		$id = $_SESSION['courseid'];
		$access_id = $_SESSION["accessid$id"];
		$validation = true;
		
		$cpswd = $_POST['cpswd'];					# current password
		$newpswd = $_POST['newpswd'];				# new password
		$cnewpswd = $_POST['cnewpswd'];				# confirmed new password
		
		$cpswd = htmlentities($cpswd, ENT_QUOTES, "UTF-8");
		$newpswd = htmlentities($newpswd, ENT_QUOTES, "UTF-8");
		$cnewpswd = htmlentities($cnewpswd, ENT_QUOTES, "UTF-8");

		if($result = @$connection->query((sprintf("SELECT * FROM access WHERE id=$access_id AND course_pswd='%s'", mysqli_real_escape_string($connection, $cpswd))))) {
			$num = $result->num_rows;
			if($num>0) {
				$row = $result->fetch_assoc();
				#unset($_SESSION['pc_error']);
				$result->free();
				
				if((strlen($newpswd)<2 || strlen($newpswd)>20) || (strlen($cnewpswd)<2 || strlen($cnewpswd)>20)){
					$validation = false;
					$_SESSION['pc_error2'] = "The new password must consist of 8 to 20 characters.";
				}

				if($validation==true) {
					#unset($_SESSION['pc_error']);
					if(strcmp($newpswd, $cnewpswd) != 0){
						$_SESSION['pc_error3'] = "The new passwords do not match.";
						header("Location:passchange.php");
					}

					else {
						#unset($_SESSION['pc_error']);
						$sql = "UPDATE access set course_pswd='" . $newpswd . "' WHERE id='" . $access_id . "'";
						if (@$connection->query((sprintf("UPDATE access set course_pswd='" . $newpswd . "' WHERE id='" . $access_id . "'", mysqli_real_escape_string($connection, $newpswd))))) {
							$_SESSION['message'] = '<span style="color:green"><b>The password has been changed successfully.</b></span>';
							header("Location:coursecontent.php?courseid=$id");
						} 
						else {
							$_SESSION['message'] = '<span style="color:red">We couldn\'t update the password. Please try again later.</span>';
						 	header("Location:coursecontent.php?courseid=$id");
						}
					}
				}

				else {
					header("Location:passchange.php");
				}
			}

			else {
				$_SESSION['pc_error1'] = "The password you entered is incorrect.";
				header("Location:passchange.php");
			}
		}

		$connection->close();
	}
?>