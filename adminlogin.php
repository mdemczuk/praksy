<?php
	session_start();
	if((!isset($_POST['email'])) || (!isset($_POST['pswd']))) {
		header('Location: admin.php');
		exit();
	}

	require_once "connect.php";
	$connection = @new mysqli($host, $db_user, $db_pswd, $db_name);

	if($connection->connect_errno!=0) {	
		echo "Error: ".$connection->connect_errno;
	}
	else {
		$login = $_POST['email'];
		$pswd = $_POST['pswd'];
		$login = htmlentities($login, ENT_QUOTES, "UTF-8");
		$pswd = htmlentities($pswd, ENT_QUOTES, "UTF-8");

		if($result = @$connection->query((sprintf("SELECT * FROM access WHERE user_id=(SELECT id FROM users WHERE email='%s' AND permissions='admin') AND course_pswd='%s'", mysqli_real_escape_string($connection, $login), mysqli_real_escape_string($connection, $pswd))))) {
			$numof_users = $result->num_rows;
			if($numof_users>0) {
				$_SESSION['loggedin'] = true;
				$row = $result->fetch_assoc(); # creates an associative array which stores variables from $result not under indexes but under column names of the table

				$_SESSION['userid'] = $row['user_id'];
				$_SESSION['admin'] = true;

				unset($_SESSION['error']);
				$result->free();

				# redirecting to the course content
				header('Location: adminpanel.php');
			}
			else {
				$_SESSION['error']='<span style="color:red">'."You don't have administrator's permissions or you typed in incorrect e-mail or password.".'</span>';
				header("Location: admin.php");
			}
		}

		# closing the established connection
		$connection->close();
	}
	
?>