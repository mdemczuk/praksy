<?php

	session_start();

	require_once "connect.php";

	$connection = @new mysqli($host, $db_user, $db_pswd, $db_name);		# connecting to database

	if($connection->connect_errno!=0) {	
		echo "Error: ".$connection->connect_errno;
	}
	else {
		$id = $_GET['courseid'];
		if($id > $_SESSION['no_courses'] or $id < 1) {					# checking what was passed in $_GET['courseid']
			header("Location: index.php");
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
<h2 align="left">Rejestracja na kurs</h2> # making registration form 
<form action="buy.php" method="post">
	name:
	<input type="text" name="name">
	<br/><br/>
	surrname:
	<input type="text" name="surrname">
	<br/><br/>
	email:
	<input type="email" name="email">
	<br/><br/>
	Enter your phone number:
	<input type="text" name="phone">
	<br/><br/>
	financed from public found(choose one):<br>
	<input type="radio" id="yes" name="financed_from_public_funds" value="yes">
  <label for="yes">yes</label>
  <input type="radio" id="no" name="financed_from_public_funds" value="no">
  <label for="no">no</label>
  <br/><br/>
  institution:
  <input type="text" name="institution">
  <br/><br/>
  Bill institution:
  <input type="text" name="BILL_Institution">
  <br/><br/>
  Bill adress:
  <input type="text" name="BILL_Address">
   <br/><br/>
   Bill NIP:
   <input type="text" name="BILL_NIP">
   <br/><br/>
   User comment:
   <input type="text" name="User_Comment">
   <br/><br/>
   Nationality:
   <input type="text" name="Nationality">
   <br/><br/>
   sex:
   <input type="radio" id="female" name="sex" >
  <label for="female">female</label>
  <input type="radio" id="male" name="sex" >
  <label for="male">male</label>
  <br/><br/>
  <input type="submit" value="Prześlij formularz">
</form>					
</body>

</html>
