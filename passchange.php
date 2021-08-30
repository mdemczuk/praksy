<?php
	session_start();

	if(isset($_SESSION['admin']) && ($_SESSION['admin']==true)) {
		$admin_logged = true;
	}
	else {
		$admin_logged = false;
		$id = $_SESSION['courseid'];
		if((!isset($_SESSION["loggedin$id"])) && ($_SESSION["loggedin$id"]!=true)){		# checking if the person is logged in for this course
			header("Location: course.php?courseid=$id");
			exit();
		}
	}	

	require_once "connect.php";
	$connection = @new mysqli($host, $db_user, $db_pswd, $db_name);		# connecting to database
	if($connection->connect_errno!=0) {	
		echo "Error: ".$connection->connect_errno;
	}
	elseif(!$admin_logged) {
		$id = $_SESSION['courseid'];
		$sql = "SELECT * FROM courses WHERE id=$id";
		if($result = @$connection->query($sql)){
			$course_info = $result->fetch_assoc();
			$title = $course_info['title'];
		}

		$connection->close();
	}
?>

<!DOCTYPE HTML>
<html lang="pl">
<head>
	<meta charset="utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<title>Change password - Ideas4Learning</title>
	<style>
	.error
	{
		color: red;
		margin-top: 10px;
		margin-bottom: 10px;
	}
	</style>
</head>

<body>
	<h3>Change password</h3>

	<?php
		if($admin_logged) {
			echo "Changing admin's password";
		}
		else {
			echo "You are changing your password for the course \"$title\".";
		}
	?>

	<form action="pswdchange.php" method="post">
		<br/>Current password:<br/><input type="password" name="cpswd" /><br/>
		<?php
		if (isset($_SESSION['pc_error1'])) {
			echo '<div class="error">'.$_SESSION['pc_error1'].'</div>';
			unset($_SESSION['pc_error1']);
		}
		?>
		New password:<br/><input type="password" name="newpswd" /><br/>
		<?php
		if (isset($_SESSION['pc_error2'])) {
			echo '<div class="error">'.$_SESSION['pc_error2'].'</div>';
			unset($_SESSION['pc_error2']);
		}
		?>
		Confirm new password:<br/><input type="password" name="cnewpswd" /><br/>
		<?php
		if (isset($_SESSION['pc_error3'])) {
			echo '<div class="error">'.$_SESSION['pc_error3'].'</div>';
			unset($_SESSION['pc_error3']);
		}
		?>
		<br/><input type="submit" value="Change password" />
	</form>

</body>
</html>