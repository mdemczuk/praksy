<?php
	session_start();
	$ID = $_SESSION['ID'];

	function random_password($length) {
		$string = md5(time());
		$string = substr($string,0,$length);
		return($string);
	}

	
	if (isset($_POST['Email'])){

		$validation = true;

		$pattern_name = '/^[\p{L} -]+$/u';
		$name = $_POST['Name'];

		if(strlen($name)<2 || strlen($name)>50) {
			$validation = false;
			$_SESSION['e_name'] = "Name has to be between 2 and 50 characters.";
		}

		if(!preg_match($pattern_name,$name)){
			$validation = false;
			$_SESSION['e_name'] = "Name can contain only letters.";
		}

		$surname = $_POST['Surname'];

		if(strlen($surname)<2 || strlen($surname)>50){
			$validation=false;
			$_SESSION['e_surname']="Surname has to be between 2 and 50 characters.";
		}

		if(!preg_match($pattern_name,$name)){
			$validation=false;
			$_SESSION['e_surname']="Surname can contain only letters.";
		}

		$email=$_POST['Email'];
		$example = '/^[a-zA-Z0-9\.\-_]+\@[a-zA-Z0-9\.\-_]+\.[a-z]{2,6}$/D';
		
		if(!preg_match($example,$email)){
			$validation=false;
			$_SESSION['e_email']="Please insert correct email.";
		}
		
		$emailB=filter_var($email, FILTER_SANITIZE_EMAIL);
		
		$phone=$_POST['Phone'];
		if(strlen($phone)!=9){
			$validation=false;
			$_SESSION['e_phone']="Phone number must have 9 numbers.";
		}

		if(intval($phone)==false){
			$validation=false;
			$_SESSION['e_phone']="Phone number must contain only numbers.";
		}

		$NIP=$_POST['BILL_NIP'];
		if(intval($NIP)==false){
			$validation=false;
			$_SESSION['e_NIP']="NIP can contain only numbers.";  	
		}

		$pattern_adress = '/^[\p{L}\p{Nd} -]+$/u';
		$address=$_POST['Address'];
		if(!preg_match($pattern_adress,$address)){
			$validation=false;
			$_SESSION['e_address']="The address is incorrect.";
		}

		if(!isset($_POST['Sex'])){
			$validation=false;
			$_SESSION['e_sex']= "Please choose one option.";
		}
		else
			$sex=$_POST['Sex'];

		if(!isset($_POST['Nationality'])){
			$validation=false;
			$_SESSION['e_nationality']= "Please choose one option.";
		}
		else
			$nationality=$_POST['Nationality'];

		if(!isset($_POST['Financed'])){
			$validation=false;
			$_SESSION['e_financed']= "Please choose one option.";
		}
		else
			$financed=$_POST['Financed'];


		if(!isset($_POST['Title'])){
			$validation=false;
			$_SESSION['e_title']= "Please choose one option.";
		}
		else
			$title=$_POST['Title'];

		$institution=$_POST['Institution'];
		$Bill_institution=$_POST['BILL_Institution'];


    $title1=$_SESSION['title1'];
		if($validation==true){
			require_once "connect.php";
			mysqli_report(MYSQLI_REPORT_STRICT);
			try {
				$connection = new mysqli($host, $db_user, $db_pswd, $db_name);	
				if($connection->connect_errno!=0) throw new Exception(mysqli_connect_errno());

				else {
					include_once('functions.php');
					$course_id = $ID;
					$password = random_password(8);
					$password_hash = password_hash($password, PASSWORD_DEFAULT);

					if($result = $connection->query("SELECT id, email FROM users")) {
						$numof_users = $result->num_rows;

						if($numof_users > 0){
							$users_ids = array();
							$users_emails = array();
							for($i = 0; $i < $numof_users; $i++){
								$row = $result->fetch_assoc();
								array_push($users_ids, $row['id']);
								array_push($users_emails, $row['email']);
							}

						$result->free();

							$user_found = false;
							$i = 0;

							while($user_found == false && $i < $numof_users){
								$db_user_email = $users_emails[$i];
								//echo "db_user_email = $db_user_email<br/>";
								$decrypted_email = decryptthis($db_user_email, $key);
								//echo "decrypted_email = $decrypted_email<br/>";
								if(strcmp($email, $decrypted_email) == 0){
									$user_found = true;
									$found_user_id = $users_ids[$i];
								}
								$i++;
							}

							if($user_found == true){
								# insert only to access table
								if($result = $connection->query("INSERT INTO access (user_id, course_id, course_pswd) VALUES ('$found_user_id', '$course_id', '$password_hash')")){
									$_SESSION['register_message'] = '<span style="color:green">Congratulations! You can now access the course. Your password is<b>'.$password.'</b>. Please remember to change it after you log in for the course.</span><br/>';
									header("Location:course.php?courseid=$ID");

								}
							}

							else {
								# insert a person into  both users and access tables
								$enc_name = encryptthis($name, $key);
								$enc_surname = encryptthis($surname, $key);
								$enc_email = encryptthis($email, $key);
								$enc_phone = encryptthis($phone, $key);
								$enc_title = encryptthis($title, $key);
								$enc_institution = encryptthis($institution, $key);
								$enc_bill_institution = encryptthis($Bill_institution, $key);
								$enc_address = encryptthis($address, $key);
								$enc_NIP = encryptthis($NIP, $key);
								$enc_nationality = encryptthis($nationality, $key);
								$enc_sex = encryptthis($sex, $key);
								$enc_financed = encryptthis($financed, $key);

								$sql = "INSERT INTO users (name, surname, phone, email, title, institution, bill_institution, address, NIP, nationality, sex, public_funds) VALUES ('$enc_name', '$enc_surname', '$enc_phone', '$enc_email', '$enc_title', '$enc_institution', '$enc_bill_institution',	'$enc_address', '$enc_NIP', '$enc_nationality', '$enc_sex', '$enc_financed')";

								if($result = $connection->query($sql)){
									if($result = $connection->query("SELECT id FROM users WHERE email = '$enc_email'")) {
										$row = $result->fetch_assoc();
											$result->free();
											$user_id = $row['id'];
											if($connection->query("INSERT INTO access (user_id, course_id, course_pswd) VALUES ('$user_id', '$course_id', '$password_hash')")){
												$_SESSION['register_message'] = '<span style="color:green">Congratulations! You can now access the course. Your password is<b>'.$password.'</b>. Please remember to change it after you log in for the course.</span><br/>';
												header("Location:course.php?courseid=$ID");

											}
									}

									else throw new Exception($connection->error);
								}
							}
						} // if num of users > 0
						else {
								# insert a person into  both users and access tables
								$enc_name = encryptthis($name, $key);
								$enc_surname = encryptthis($surname, $key);
								$enc_email = encryptthis($email, $key);
								$enc_phone = encryptthis($phone, $key);
								$enc_title = encryptthis($title, $key);
								$enc_institution = encryptthis($institution, $key);
								$enc_bill_institution = encryptthis($Bill_institution, $key);
								$enc_address = encryptthis($address, $key);
								$enc_NIP = encryptthis($NIP, $key);
								$enc_nationality = encryptthis($nationality, $key);
								$enc_sex = encryptthis($sex, $key);
								$enc_financed = encryptthis($financed, $key);

								$sql = "INSERT INTO users (name, surname, phone, email, title, institution, bill_institution, address, NIP, nationality, sex, public_funds) VALUES ('$enc_name', '$enc_surname', '$enc_phone', '$enc_email', '$enc_title', '$enc_institution', '$enc_bill_institution',	'$enc_address', '$enc_NIP', '$enc_nationality', '$enc_sex', '$enc_financed')";

								if($result = $connection->query($sql)){
									if($result = $connection->query("SELECT id FROM users WHERE email = '$enc_email'")) {
										$row = $result->fetch_assoc();
											$result->free();
											$user_id = $row['id'];
											if($connection->query("INSERT INTO access (user_id, course_id, course_pswd) VALUES ('$user_id', '$course_id', '$password_hash')")){
												$_SESSION['register_message'] = '<span style="color:green">Congratulations! You can now access the course. Your password is<b>'.$password.'</b>. Please remember to change it after you log in for the course.</span><br/>';
												header("Location:course.php?courseid=$ID");

											}
									}

									else throw new Exception($connection->error);
								}
							}
					}
					else throw new Exception($connection->error);
				} // pierwszy else w try

				$connection->close();
			} // try

			catch(Exception $e){
				echo '<div class="error"> Failed to connect with database. Please try again later.</div>';	
				//echo '<br/> Information for developers: '.$e 
			}
		}// if validation true
		else {
			$_SESSION['validation_message'] = '<div class="error"> Validation failed. <br/></div>';
		}
	} // if isset post email

?>

<!DOCTYPE HTML>
<html lang="pl">
<head>
	<meta charset="utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<title>Ideas4learning</title>
	<style>
		.error {
			font-weight: bold;
			color: red;
		}
	</style>
</head>

<body>
<h2 align="left">Register for the course</h2> 
<form method="post">
  <input type="radio" id="Mr." name="Title" value="Mr.">
  <label for="Mr.">Mr.</label>
  <input type="radio" id="Mrs" name="Title" value="Mrs.">
  <label for="Mrs.">Mrs.</label>
  <br/><?php
    	if(isset($_SESSION['e_title']))
    	{
    		echo '<div class="error">'.$_SESSION['e_title'].'</div>';
    		unset($_SESSION['e_title']);
    	}
	?><br/>

  Name:
  <input type="text" name="Name">
	<br/>
	<?php
	  	if(isset($_SESSION['e_name']))
	  	{
    		echo '<div class="error">'.$_SESSION['e_name'].'</div>';
      	unset($_SESSION['e_name']);
	  	}
	?><br/>

	Surname:
	<input type="text" name="Surname">
	<br/>
	<?php
	  	if(isset($_SESSION['e_surname']))
	  	{
	    	echo '<div class="error">'.$_SESSION['e_surname'].'</div>';
	    	unset($_SESSION['e_surname']);
	  	}
	?><br/>

	Email:
	<input type="email" name="Email">
	<br/>
	<?php
    	if(isset($_SESSION['e_email']))
    	{
    		echo '<div class="error">'.$_SESSION['e_email'].'</div>';
    		unset($_SESSION['e_email']);
    	}
	?><br/>

	Phone number:
	<input type="text" name="Phone">
	<br/>
	<?php
    	if(isset($_SESSION['e_phone']))
    	{
    		echo '<div class="error">'.$_SESSION['e_phone'].'</div>';
    		unset($_SESSION['e_phone']);
    	}
	?><br/>

	Financed from public funds:<br>
	<input type="radio" id="yes" name="Financed" value="yes">
    <label for="yes">Yes</label>
    <input type="radio" id="no" name="Financed" value="no">
    <label for="no">No</label>
    <br/>
    <?php
    	if(isset($_SESSION['e_financed']))
    	{
    		echo '<div class="error">'.$_SESSION['e_financed'].'</div>';
    		unset($_SESSION['e_financed']);
    	}
	?><br/>

  Institution:
  <input type="text" name="Institution">
  <br/><br/>

  Bill institution:
  <input type="text" name="BILL_Institution">
  <br/><br/>

  Bill address:
  <input type="text" name="Address">
  <br/>
  <?php
    	if(isset($_SESSION['e_address']))
    	{
    		echo '<div class="error">'.$_SESSION['e_address'].'</div>';
    		unset($_SESSION['e_address']);
    	}
	?><br/>

  Bill NIP:
  <input type="text" name="BILL_NIP">
  <br/>
  <?php
      if(isset($_SESSION['e_NIP']))
    	{
    		echo '<div class="error">'.$_SESSION['e_NIP'].'</div>';
    		unset($_SESSION['e_NIP']);
    	}
	?><br/>

  Nationality:
  <input type="text" name="Nationality">
	<br/>
	<?php
	  	if(isset($_SESSION['e_nationality']))
	  	{
    		echo '<div class="error">'.$_SESSION['e_nationality'].'</div>';
      	unset($_SESSION['e_nationality']);
	  	}
	?><br/>

  Sex:
  <input type="radio" id="female" name="Sex" >
  <label for="female">Female</label>
  <input type="radio" id="male" name="Sex" >
  <label for="male">Male</label>
  <br/>
	<?php
    	if(isset($_SESSION['e_sex']))
    	{
    		echo '<div class="error">'.$_SESSION['e_sex'].'</div>';
    		unset($_SESSION['e_sex']);
    	}
	?><br/>
  <input type="submit" value="Submit">
</form>

</body>

</html>