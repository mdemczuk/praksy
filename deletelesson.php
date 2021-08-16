<?php
	session_start();

	# checking if admin is logged in
	if(!((isset($_SESSION['admin'])) && ($_SESSION['admin']==true))) {
		header('Location: index.php');
		exit();
	}

	require_once "connect.php";

	$connection = @new mysqli($host, $db_user, $db_pswd, $db_name);		# connect do db

	if($connection->connect_errno!=0) {									# if the connection hasn't been established
		echo "Error: ".$connection->connect_errno;
	}
	else {																# if the connection has been established
		$lesson_id = $_SESSION['lessonid'];

		# deleting lesson
		$sql = "DELETE FROM lessons WHERE id = ".$lesson_id;
		$result = mysqli_query($connection, $sql);

		if($result) {
			$_SESSION['lesson_message'] = '<span style="color:green"><b>Succesfully deleted lesson!</b></span>';
		}
		else {
			$_SESSION['del_lesson_error'] = '<span style="color:red">'."Something went wrong, please try again.".'</span>';
		}
		header("Location: coursecontent.php");

		$connection->close();											# closing connection to db
	}
?>