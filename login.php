<?php
	session_start();
	if((!isset($_POST['email'])) || (!isset($_POST['pswd']))) {
		header('Location:index.php');
		exit();
	}

	require_once "connect.php";
	$connection = @new mysqli($host, $db_user, $db_pswd, $db_name);

	if($connection->connect_errno!=0) {	
		echo "Error: ".$connection->connect_errno;
	}
	else {
		$id = $_SESSION['courseid'];
		if($id > $_SESSION['no_courses'] or $id < 1) {
			header("Location: index.php");
			exit();
		}

		$login = $_POST['email'];
		$pswd = $_POST['pswd'];
		$login = htmlentities($login, ENT_QUOTES, "UTF-8");
		$pswd = htmlentities($pswd, ENT_QUOTES, "UTF-8");

		if($result = @$connection->query((sprintf("SELECT * FROM access WHERE course_id=$id AND user_id=(SELECT id FROM users WHERE email='%s') AND course_pswd='%s'", mysqli_real_escape_string($connection, $login), mysqli_real_escape_string($connection, $pswd))))) {
			$numof_users = $result->num_rows;
			if($numof_users>0) {
				$_SESSION["loggedin$id"] = true;
				$row = $result->fetch_assoc(); # creates an associative array which stores variables from $result not under indexes but under column names of the table
				$_SESSION["accessid$id"] = $row['id'];
				unset($_SESSION['login_error']);
				$result->free();

				# redirecting to the course content
				header("Location: coursecontent.php?courseid=$id");
			}
			else {
				$_SESSION['login_error']='<span style="color:red">Incorrect e-mail or password.</span>';
				header("Location:loginpanel.php");
			}
		}

		# closing the established connection
		$connection->close();
	}

?>
