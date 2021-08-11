<?php

	session_start();

	if((isset($_SESSION['admin'])) && ($_SESSION['admin']==true)) {
		$where = "admin.php";
		session_unset();
	}
	else {
		$where = "index.php";
		$id = $_SESSION['courseid'];
		unset($_SESSION["loggedin$id"]);			# logging out of the specific course
		unset($_SESSION['courseid']);
	}
	
	header("Location: $where");
?>
