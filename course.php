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

		if((isset($_SESSION["loggedin$id"])) && ($_SESSION["loggedin$id"]==true)){		# checking if the person is already logged in for this course
			header("Location:coursecontent.php?courseid=$id");
			exit();
		}

		$sql = "SELECT * FROM courses WHERE id = ".$id;					# fetching data of the course with proper id

		if($result = @$connection->query($sql)) {
			$course_info = $result->fetch_assoc();
		}

		$connection->close();
	}

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
	# variable $course_info contains info of the chosen course


		$Title=$course_info['title'];  #assignment value form specyfik $cours_info to variable so  printing info on website becomes easier
    	echo"<h1 align='center'>Witaj na stronie poświęconej kursowi:</h1><br><p align='center'>$Title </p>"; 
	 
		$description=$course_info['general_description'];
		echo "<p align='left'>Krótki opis: $description</p>";
		$matters=$course_info['matters'];		
		echo "<p align='left'>Omawiane zagadnienia: $matters</p>";
		$for_whom=	$course_info['for_whom'];	 
		echo "<p align='left'>Dla kogo: $for_whom</p>";
		$results=$course_info['results'];
		echo "<p align='left'>Co będziesz umiał po ukończeniu: $results</p>";
		$language=$course_info['language'];
		echo "<p align ='left'>Język: $language</p>";
		$more_info=	$course_info['additional_info'];
		echo "<p align='left'>Informacje o wydarzeniu: $more_info</p>"

	?>

	<p><a href="index.php">Home page</a></p>
	
	<center><a href="loginpanel.php">Log in for this course</a> <a href="registerpanel.php">Register for this course</a></center>

</body>
</html>