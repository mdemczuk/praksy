<?php

	session_start();

	if((isset($_SESSION['admin'])) && ($_SESSION['admin']==true)) {
		$where = "admin.php";
	}
	else {
		$where = "index.php";
	}
	
	session_unset();
	header("Location: $where");
?>
