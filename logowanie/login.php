<?php
	
	session_start();				# function that allows the document to use session features
	
	# when someone tries to access the localhost/logowanie/login.php site without entering either their e-mail address or password
	if((!isset($_POST['email'])) || (!isset($_POST['pswd']))) {
		header('Location:index.php');
		exit();
	}

	require_once "connect.php";		# 'include' informs about any possible errors but the script will be further executed regardless; 'require' stops the script from being executed any further if there was any error, so 'require' prevents from any unwanted information leak from the data base

	# connecting to data base

	$connection = @new mysqli($host, $db_user, $db_pswd, $db_name); # constructor for object-oriented mysql (mysqli); '@' is an operator for error control which mutes information about any possible errors while connecting to a data base (used when establishing your own ways of error control)

	# if the connection hasn't been established
	if($connection->connect_errno!=0) {	
		echo "Error: ".$connection->connect_errno;
	}

	# if the connection has been established successfully
	else {
		# the rest of the script
		$login = $_POST['email'];
		$pswd = $_POST['pswd'];
		$sql = "SELECT * FROM users WHERE email='$login' AND pswd='$pswd'";

		# if there wasn't any error in the query (i.e. something was incorrectly written)
		if($result = @$connection->query($sql)) {
			$no_users = $result->num_rows;
			if($no_users>0) {
				$_SESSION['loggedin'] = true;
				$row = $result->fetch_assoc(); # creates an associative array which stores variables from $result not under indexes but under column names of the table

				$_SESSION['name'] = $row['name'];
				$_SESSION['id'] = $row['id'];

				unset($_SESSION['error']);
				$result->free(); # or $result->close(), or $result->free_result();

				# redirecting to index.php
				header('Location: main.php');
			}
			else {

				$_SESSION['error']='<span style="color:red">Incorrect e-mail or password.</span>';
				header('Location:index.php');
			}
		}

		# closing the established connection
		$connection->close();
	}

?>