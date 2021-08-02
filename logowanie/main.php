<?php
	session_start();

	# when someone is not logged in
	if(!isset($_SESSION['loggedin'])) {
		header('Location:index.php');
		exit();
	}
?>
<!DOCTYPE HTML>
<html lang="pl">
<head>
	<meta charset="utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<title>E-learning platform</title>
</head>

<body>

<?php

echo "<p>Hello, <b>".$_SESSION['fname'].'</b>.</p>';
echo "<p>How are you feeling today?</p>";
echo '<p><a href="logout.php">Log out</a></p>';

?>

</body>

</html>
