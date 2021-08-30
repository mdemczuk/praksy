<?php
	session_start();
	if((!isset($_POST['email'])) || (!isset($_POST['pswd']))) {
		header('Location: admin.php');
		exit();
	}

	require_once "connect.php";
	$connection = @new mysqli($host, $db_user, $db_pswd, $db_name);

	if($connection->connect_errno!=0) {	
		echo "Error: ".$connection->connect_errno;
	}
	else {
		$login = $_POST['email'];
		$pswd = $_POST['pswd'];
		#$login = htmlentities($login, ENT_QUOTES, "UTF-8");

		include_once('functions.php');

		if($result = @$connection->query("SELECT * FROM users WHERE permissions='admin'")) {
			$numof_users = $result->num_rows;
			if($numof_users>0) {
				$row = $result->fetch_assoc();
				$admin_email = $row['email'];
				$dec_admin_email = decryptthis($admin_email, $key);
				if(strcmp($login, $dec_admin_email) == 0){
					$admin_id = $row['id'];
					#$sql = "SELECT * FROM access WHERE user_id = $admin_id";
					$admin_pass =  $row['admin_pass'];
					if(password_verify($pswd, $admin_pass)){
						$_SESSION['admin'] = true;
						unset($_SESSION['err_admin_login']);

						# redirecting to the course content
						header('Location: adminpanel.php');
					}
					else {
						$_SESSION['err_admin_login']='<span style="color:red">'."You don't have administrator's permissions or you typed in incorrect email or password.".'</span>';
						header("Location: admin.php");
					}
				}
				else {
					$_SESSION['err_admin_login']='<span style="color:red">'."You don't have administrator's permissions or you typed in incorrect e-mail or password.".'</span>';
					header("Location: admin.php");
				}
			}
			
		}

		# closing the established connection
		$connection->close();
	}
	
?>
