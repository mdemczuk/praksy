<?php

	session_start();
	
	if(!isset($_SESSION['loggedin'])) {
		header('Location:course.php');
		exit();
	}

	require_once "connect.php";

	$connection = @new mysqli($host, $db_user, $db_pswd, $db_name);
	$content = 'abcd';
	if($connection->connect_errno!=0) {	
		echo "Error: ".$connection->connect_errno;
	}

	else {
		$id = $_SESSION['courseid'];
		$sql = "SELECT * FROM courses WHERE id=$id";
		if($result = @$connection->query($sql)){
			$course_info = $result->fetch_assoc();
			$content = $course_info['content'];
		}

		$connection->close();
	}

?>

<!DOCTYPE HTML>
<html lang="pl">
<head>
	<meta charset="utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<title>Ideas4Learning</title>

	
</head>

<body>
	<h2>Course content:</h2>

	<?php
	#	echo "<p align = left>".$content."</p>";
	#	echo '<p><a href="index.php">Home page</a> <a href="logout.php">Log out</a></p>';
	?>

	<div>
		<form method="post">
			<textarea name="my_editor" id="my_editor" cols="30" rows="10">
				
			</textarea>
			<input type="submit" name="submit" value="Submit">
		</form>
	</div>
	

	<script src="CKEditor/build/ckeditor.js"></script>

	<script>
		ClassicEditor
    		.create(document.querySelector('#editor'))
    		.then(editor => {
        		console.log( editor );
    		})
    		.catch(error => {
        		console.error( error );
    		});

    	ClassicEditor.create(document.getElementById('my_editor'));
	</script>
	

</body>

</html>
