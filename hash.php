<!DOCTYPE HTML>
<html lang="pl">
<body>
	Enter the password you want to hash:
	<form action="hash.php" method="post">
		<br/>Password:<br/><input type="text" name="pass" /><br/>
		<br/><input type="submit" value="Hash password" />
	</form>
<?php 
	if(isset($_POST['pass'])){
		$pass = $_POST['pass'];
		$pass_hash = password_hash($pass, PASSWORD_DEFAULT);
		echo "<br/><b>Entered password:</b> $pass<br/><b>Hashed password:</b> $pass_hash";
	}
	
?>
</body>
</html>