<?php
	session_start();

	# checking if admin is logged in
	if(isset($_SESSION['admin']) && ($_SESSION['admin']==true)) {
		$admin_logged = true;
	}
	else {
		$admin_logged = false;
	}

	require_once "connect.php";

	$connection = @new mysqli($host, $db_user, $db_pswd, $db_name);		# connecting to database

	if($connection->connect_errno!=0) {	
		echo "Error: ".$connection->connect_errno;
	
	}
	else {
		$id = $_GET['courseid'];
		$_SESSION['courseid'] = $id;
		if($id > $_SESSION['no_courses'] or $id < 1) {					# checking what was passed in $_GET['courseid']
			header("Location: index.php");
			exit();
		}

		if((isset($_SESSION["loggedin$id"])) && ($_SESSION["loggedin$id"]==true) && !$admin_logged){		# checking if the person is already logged in for this course
			header("Location: coursecontent.php?courseid=$id");
			exit();
		}

		$sql = "SELECT * FROM courses WHERE id = ".$id;					# fetching data of the course with proper id

		if($result = @$connection->query($sql)) {
			$course_info = $result->fetch_assoc();
		}

		$connection->close();
	}
$ID=$course_info['id'];
$_SESSION['ID']=$ID;
$title1=$course_info['title'];
$_SESSION['title1']=$title1;
?>

<!DOCTYPE HTML>
<html lang="pl">
<head>
	<meta charset="utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<title>Ideas4learning</title>

</head>

<body>
	
	<?php

		if($admin_logged) { ?>
			<div style="float: right; padding-right: 20px;">
				<form method="post">
					<br/><input type="submit" value="Edit course info" name="edit_course_info" />
				</form>
			</div>
			<div style="clear: both;"></div>
		<?php
		}

		if(isset($_POST['edit_course_info'])) {
			$_SESSION['editcourseinfo'] = true;
			header("Location: editcourseinfo.php?courseid=$id");
			exit();
		}
		unset($_SESSION['editcourseinfo']);

		$title=$course_info['title'];  #assignment value form specific $cours_info to variable so printing info on website becomes easier
    		$_SESSION['course_title'] = $title;
    		echo"<h1 align='center'>$title</h1><br />"; 
	 
		$description=$course_info['general_description'];
		echo "<p align='left'><b>General description:</b><br />$description</p>";
		$matters=$course_info['matters'];		
		echo "<p align='left'><b>Matters:</b><br />$matters</p>";
		$for_whom=	$course_info['for_whom'];	 
		echo "<p align='left'><b>For whom:</b><br />$for_whom</p>";
		$results=$course_info['results'];
		echo "<p align='left'><b>Expected results after finishing this course:</b><br />$results</p>";
		$language=$course_info['language'];
		echo "<p align ='left'><b>Language:</b><br />$language</p>";
		$more_info=	$course_info['additional_info'];
		echo "<p align='left'><b>Additional information:</b><br />$more_info</p>";
		
	?>

	<p><a href="index.php">Home page</a></p>
	
	<center><a href="loginpanel.php">Log in for this course</a> <a href="register.php">Register for this course</a></center>

</body>
</html>
