<?php 
session_start();
$ID = $_SESSION['ID'];
// $dlugosc może być z zakresu od 0 do 32 - w przypadku wpisania wiekszej liczby niż 32 i tak funkcja zwróci 32 znaki
function random_paswd($dlugosc){
 
// generujemy losowy ciąg znaków
// pobieramy aktualny czas a następnie przeprowadzamy kodowanie tego czasu za pomocą funkcji szyfrującej md5
  $string = md5(time());
 
//przycinamy ciąg znaków do podanej długości, począwszy od 1 znaku a skończywszy na $długosc
  $string = substr($string,0,$dlugosc);
 
//zwraca ciąg znaków
  return($string);
}
if (isset($_POST['Email']))
{
  	$validation = true;
  	$pattern_name= '/^[\p{L} -]+$/u';
$name=$_POST['Name'];
  	if(strlen($name)<2 || strlen($name)>50)
  	{
  		$validation=false;
  		$_SESSION['e_name']="Name has to be between 2 and 50 characters.";
  	}
  	if(!preg_match($pattern_name,$name))
  	{
  		$validation=false;
  		$_SESSION['e_name']="Name can contain only letters.";
  	}
$surname=$_POST['Surname'];
	if(strlen($surname)<2 || strlen($surname)>50)
  	{
  		$validation=false;
  		$_SESSION['e_surname']="Surname has to be between 2 and 50 characters.";
  	}
  	if(!preg_match($pattern_name,$name))
  	{
  		$validation=false;
  		$_SESSION['e_surname']="Surname can contain only letters.";
  	}

$email=$_POST['Email'];
  	$example = '/^[a-zA-Z0-9\.\-_]+\@[a-zA-Z0-9\.\-_]+\.[a-z]{2,6}$/D'; //wyrażenie regularne, na podstawie którego testowany jest e-mail
  	if(!preg_match($example,$email))//funkcja, która odpowiada za testowanie danych na podstawie szablonu
  	{
  		$validation=false;
  		$_SESSION['e_email']="Please insert correct email.";
  	}
$emailB=filter_var($email, FILTER_SANITIZE_EMAIL);
$phone=$_POST['Phone'];
  	if(strlen($phone)!=9)
  	{
  		$validation=false;
  		$_SESSION['e_phone']="Phone number must have 9 numbers.";
  	}
  	if(intval($phone)==false)
  	{
  		$validation=false;
  		$_SESSION['e_phone']="Phone number must contain only numbers.";
  	}
$NIP=$_POST['BILL_NIP'];
if(intval($NIP)==false)
{
	$validation=false;
  		$_SESSION['e_NIP']="NIP can contain only numbers.";
  	
}
$pattern_adress = '/^[\p{L}\p{Nd} -]+$/u';
$address=$_POST['Address'];
if(!preg_match($pattern_adress,$address))
{
	$validation=false;
	$_SESSION['e_adress']="There is something wrong with the adress.";
}
$sex=$_POST['Sex'];
$nationality=$_POST['Nationality'];
$financed=$_POST['Financed'];
$institution=$_POST['Institution'];
$title=$_POST['Title'];
$Bill_institution=$_POST['BILL_Institution'];

$title1=$_SESSION['title1'];
if($validation==true)
{
require_once"connect.php";
mysqli_report(MYSQLI_REPORT_STRICT);
try
{
$connection = new mysqli($host, $db_user, $db_pswd, $db_name);	
if($connection->connect_errno!=0) {	
		throw new Exception(mysqli_connect_errno());
	}
	else {
$password=random_paswd(8);

$wynik=$connection->query("SELECT id FROM users WHERE email='$email';");
$number=$wynik->num_rows;
if($number>0)
{
$result=$connection->query("SELECT id FROM users WHERE email='$email';");
if(!$result)throw new Exception($connection->error);
else
{
$info = $result->fetch_assoc();
$znacznik=$info['id'];
$connection->query("INSERT INTO access (course_pswd, user_id)VALUES('$password','$znacznik');");	
}
  
}
else
{
if($connection->query("INSERT INTO users (name, surname, email, phone, title, institution, BILL_Institution, address, NIP, nationality, sex, financed_from_public_funds) VALUES ('$name', '$surname', '$email', '$phone', '$title', '$institution', '$Bill_institution', '$address', '$NIP','$nationality','$sex', '$financed');"))
{
$result=$connection->query("SELECT id FROM users WHERE email='$email';");
if(!$result)throw new Exception($connection->error);
else
{
	$info = $result->fetch_assoc();
$znacznik=$info['id'];
$connection->query("INSERT INTO access (course_pswd, user_id)VALUES('$password','$znacznik');");

}

}
else
{
	throw new Exception($connection->error);
}
}
}
$connection->close();
}
  						
  		
catch(Exception $e)
{
echo '<div class="error"> Failed to connect with database. Please try again later.</div>';	
//echo '<br/> Information for developers: '.$e
  
}

;
}
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
<h2 align="left">Rejestracja na kurs</h2> 
<form  method="post">
<input type="radio" id="Mr." name="Title" value="Mr.">
  <label for="Mr.">Mr.</label>
  <input type="radio" id="Mrs" name="Title" value="Mrs.">
  <label for="Mrs.">Mrs.</label>
  <br/><br/>
	name:
	<input type="text" name="Name">
	<br/>
	<?php
	if(isset($_SESSION['e_name']))
	{
		echo '<div class="error">'.$_SESSION['e_name'].'</div>';
		unset($_SESSION['e_name']);
	}
	?><br/>
	surname:
	<input type="text" name="Surname">
	<br/>
	<?php
	if(isset($_SESSION['e_surname']))
	{
		echo '<div class="error">'.$_SESSION['e_surname'].'</div>';
		unset($_SESSION['e_surname']);
	}
	?><br/>
	email:
	<input type="email" name="Email">
	<br/>
	<?php
	if(isset($_SESSION['e_email']))
	{
		echo '<div class="error">'.$_SESSION['e_email'].'</div>';
		unset($_SESSION['e_email']);
	}
	?><br/>
	phone number:
	<input type="text" name="Phone">
	<br/>
	<?php
	if(isset($_SESSION['e_phone']))
	{
		echo '<div class="error">'.$_SESSION['e_phone'].'</div>';
		unset($_SESSION['e_phone']);
	}
	?>
	<br/>
	financed from public found(choose one):<br>
	<input type="radio" id="yes" name="Financed" value="yes">
  <label for="yes">yes</label>
  <input type="radio" id="no" name="Financed" value="no">
  <label for="no">no</label>
  <br/><br/>
  institution:
  <input type="text" name="Institution">
  <br/><br/>
  Bill institution:
  <input type="text" name="BILL_Institution">
  <br/><br/>
  Bill adress:
  <input type="text" name="Address">
   <br/>
<?php
	if(isset($_SESSION['e_adress']))
	{
		echo '<div class="error">'.$_SESSION['e_adress'].'</div>';
		unset($_SESSION['e_adress']);
	}
	?>
   <br/>
   Bill NIP:
   <input type="text" name="BILL_NIP">
   <br/>
   <?php
   if(isset($_SESSION['e_NIP']))
	{
		echo '<div class="error">'.$_SESSION['e_NIP'].'</div>';
		unset($_SESSION['e_NIP']);
	}
	?>
   <br/>
   Nationality:
   <input type="text" name="Nationality">
   <br/><br/>
   sex:
   <input type="radio" id="female" name="Sex" >
  <label for="female">female</label>
  <input type="radio" id="male" name="Sex" >
  <label for="male">male</label>
  <br/>
	<?php
	if(isset($_SESSION['e_sex']))
	{
		echo '<div class="error">'.$_SESSION['e_sex'].'</div>';
		unset($_SESSION['e_sex']);
	}
	?>
  <br/>
  <input type="submit" value="Submit">


</body>

</html>