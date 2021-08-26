<?php
	session_start();
	if((!isset($_POST['email'])) || (!isset($_POST['pswd']))) {
		header('Location:index.php');
		exit();
	}

	require_once "connect.php";
	$connection = @new mysqli($host, $db_user, $db_pswd, $db_name);

	if($connection->connect_errno!=0) {	
		echo "Error: ".$connection->connect_errno;
	}
	else {
		include_once('functions.php');
		$id = $_SESSION['courseid'];
		if($id > $_SESSION['no_courses'] or $id < 1) {
			header("Location: index.php");
			exit();
		}

		$login = $_POST['email'];
		$pswd = $_POST['pswd'];

		$sql = "SELECT user_id FROM access WHERE course_id = $id";

		if($result = @$connection->query($sql)){
			$numof_users_access = $result->num_rows;
			$users_ids = array();
			if($numof_users_access > 0){
				for($i = 0; $i < $numof_users_access; $i++){
					$row = $result->fetch_assoc();
					array_push($users_ids, $row['user_id']);
				}
			}
		}

		$user_found = false;
		$i = 0;

		while($user_found == false && $i < $numof_users_access){
			$sql = "SELECT email FROM users WHERE id = $users_ids[$i]";
			if($result = @$connection->query($sql)){
				$numof_users = $result->num_rows;
				if($numof_users > 0){
					$row = $result->fetch_assoc();
					$db_user_email = $row['email'];
					//echo "db_user_email = $db_user_email<br/>";
					$decrypted_email = decryptthis($db_user_email, $key);
					//echo "decrypted_email = $decrypted_email<br/>";
					if(strcmp($login, $decrypted_email) == 0){
						$user_found = true;
						$found_user_id = $users_ids[$i];
						//echo "We found you in our database! Your user id is $found_user_id";
					}
				}
			}
			$i++;
		}

		if($user_found == false){
			$_SESSION['login_error']='<span style="color:red">Incorrect e-mail or password.</span>';
			header("Location:loginpanel.php");
		}

		if($result = @$connection->query(sprintf("SELECT * FROM access WHERE course_id=$id AND user_id=$found_user_id"))) {
			$numof_users = $result->num_rows;
			if($numof_users>0) {
				$row = $result->fetch_assoc();
				$server_time = new DateTime();
				$end_access = DateTime::createFromFormat('Y-m-d H:i:s', $row['access_till']);
				$difference = $server_time->diff($end_access);
				if($server_time < $end_access) {
					# if user still has access
					$access = true;
				}
				else {
					# user doesn't have access anymore
					$access = false;
				}
				if(password_verify($pswd, $row['course_pswd']) && $access){
					# correct password and user still has access
					$_SESSION["loggedin$id"] = true;
					$_SESSION["accessid$id"] = $row['id'];
					$_SESSION['userid'] = $row['user_id'];
					unset($_SESSION['login_error']);
					$result->free();

					# redirecting to the course content
					header("Location: coursecontent.php?courseid=$id");
				}
				elseif(password_verify($pswd, $row['course_pswd']) && !$access) {
					# correct password but user doesn't have access to the course anymore
					$_SESSION['login_error']='<span style="color:red">You do not have access to this course anymore. If you want to continue this course, please register once more for 30 days of access.</span>';
					# deleting record from table access
					$sql_access = "DELETE FROM access WHERE course_id = $id AND user_id=$found_user_id";
					@$connection->query($sql_access);
					header("Location:loginpanel.php");
				}
				else {
					$_SESSION['login_error']='<span style="color:red">Incorrect e-mail or password.</span>';
					header("Location:loginpanel.php");
				}
				
			}
			else {
				$_SESSION['login_error']='<span style="color:red">Incorrect e-mail or password.</span>';
				header("Location:loginpanel.php");
			}
		}

		# closing the established connection
		$connection->close();
	}

?>