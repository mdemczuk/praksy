<?php

	session_start();

require_once"connect.php";
mysqli_report(MYSQLI_REPORT_STRICT);
try
{
$connection = new mysqli($host, $db_user, $db_pswd, $db_name);	
if($connection->connect_errno!=0) {	
		throw new Exception(mysqli_connect_errno());
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
}
catch(Exception $e)
{
echo '<div class="error"> Failed to connect with database. Please try again later.</div>';	
//echo '<br/> Information for developers: '.$e;
}

if (isset($_POST['Email']))
{
  	$validation = true;
$name=$_POST['Name'];
  	if(strlen($name)<2 || strlen($name)>50)
  	{
  		$validation=false;
  		$_SESSION['e_name']="Name has to be between 2 and 50 characters.";
  	}
  	if(ctype_alpha($name)==false)
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
  	if(ctype_alpha($surname)==false)
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
$address=$_POST['Address'];
$sex=$_POST['Sex'];
$nationality=$_POST['Nationality'];
$financed=$_POST['Financed'];
$institution=$_POST['Institution'];
$title=$_POST['Title'];

$title1=$course_info['title'];
if($validation==true)
  	{require_once"connect.php";
mysqli_report(MYSQLI_REPORT_STRICT);
try
{
$connection = new mysqli($host, $db_user, $db_pswd, $db_name);	
if($connection->connect_errno!=0) {	
		throw new Exception(mysqli_connect_errno());
	}
	else {
  		if($connection->query("INSERT INTO users (name, surname, email, phone, title, institution, BILL_Institution, address, NIP, nationality, sex, financed_from_public_funds) VALUES ('$name', '$surname', '$email', '$phone', '$title', '$institution', '$institution', '$address', '$NIP','$nationality','$sex', '$financed');")){

// tworzenie funkcji "losowy_ciag" - z parametrem $dlugosc
//gdzie $dlugosc może być z zakresu od 0 do 32 - w przypadku wpisania wiekszej liczby niż 32 i tak funkcja zwróci 32 znaki
function random_paswd($dlugosc){
 
// generujemy losowy ciąg znaków
// pobieramy aktualny czas a następnie przeprowadzamy kodowanie tego czasu za pomocą funkcji szyfrującej md5
  $string = md5(time());
 
//przycinamy ciąg znaków do podanej długości, począwszy od 1 znaku a skończywszy na $długosc
  $string = substr($string,0,$dlugosc);
 
//zwraca ciąg znaków
  return($string);
}
 
 // teraz zapisujemy do zmiennej i wyświetlamy na ekranie;
$password=random_paswd(8);

 $connection->query("INSERT INTO acces (course_pswd) VALUES ('$password');");
 

$html = "
								<html><p>Dziękujemy za rejestrację na kurs pt.</p>
								<p><strong><font color = darkgreen><h3>$title1</h3></font></strong></p>
								<p>Dostęp do kursu zostanie przyznany w momencie zaksięgowania opłaty za kurs.Hasło do Twojego kursu to: $password, będziesz mógł je zmienić po dokonaniu pierwszego logowania.</strong></p>
								<p><strong>Uiszczenie opłaty powinno nastąpić najpóźniej na tydzień przed rozpoczęciem kursu.</strong></p>
								<p>Szkolenie odbywa się zazwyczaj w godzinach od 9:00 do 17:00. Szczegółowy harmonogram szkolenia przesyłany jest do uczestników na kilka dni przed planowanym terminem kursu.</p>
								<p>Organizator zastrzega sobie prawo do odwołania kursu niepóźniej niż na dwa tygodnie przed datą jego rozpoczęcia w przypadku mniej niż 5 uczestników.</p>
								<p>Dane do przelewu:<br />
								Tytuł przelewu: imię, nazwisko oraz data kursu<br />
								Konto bankowe (Alior Bank):<br />
								Nazwa: ideas4biology Sp. z o.o. <br />
								PLN: 06 2490 0005 0000 4520 2193 3163 <br />
								Adres: <br />
								os. Wichrowe Wzgórze 2/12, <br />
								61-672 Poznań <br /></p>
								<p>Dane uczestnika kursu:<br />
								Tytuł: $title<br />
								Imię: $name<br />
								Nazwisko: $surname<br />
								E-mail: $email<br />
								Telefon: $phone<br />
								Afiliacja: $institution<br />
								<br />
								Dane do wystawienia faktury:<br />
								Instytucja: $institution<br />
								Adres: $address<br />
								NIP: $NIP<br />
								<br />
								<p>Dodatkowe informacje organizacyje znajdą Państwo w zakładce z najczęściej zadawanymi pytaniami na naszej stronie internetowej.</p>
								<p>Zachęcamy jednocześnie do zapisania się na nasz <a href = https://ideas4biology.com/newsletterNew.php >newsletter</a>, aby mogli Państwo na bieżąco dowiadywać się o kolejnych organizowanych przez nas kursach.</p> 
								<p>Pozdrawiamy serdecznie,<br /><strong><font color = darkgreen><img src= https://ideas4biology.com/i4b_right.png height='45' alt = 'Zespół Ideas For Biology s.c.'></font></strong><br /><br /></p></html>";
							$headers    = array
							(
								'MIME-Version: 1.0',
								'Content-Type: text/html; charset="UTF-8";',
								'Content-Transfer-Encoding: 7bit',
								'Date: ' . date('r', $_SERVER['REQUEST_TIME']),
								'Message-ID: <' . $_SERVER['REQUEST_TIME'] . md5($_SERVER['REQUEST_TIME']) . '@' . $_SERVER['SERVER_NAME'] . '>',
								'From:Ideas4biology.com',
								'Reply-To:office@ideas4biology.com',
								'X-Mailer: PHP v' . phpversion(),
					);

  						if(mail($emailB, $title1, $html, $headers)){//funkcja wysyłająca maila 1 wartość do kogo, tytuł, treść, nagłówek
  							 echo "Poprawnie wysłano e-mail";
								}
							else{
   									echo "Wystąpił nieoczekiwany błąd, spróbuj jeszcze raz...";
								}}
				else
  						
						throw new Exception($connection->error);
  						}
  		
  		{
$_SESION['s_registration']=true;
header('Location: witam.php');

  		}
  		
  	$connection->close();
  }
		

catch(Exception $e)
{
echo '<div class="error"> Failed to connect with database. Please try again later.</div>';	
//echo '<br/> Information for developers: '.$e;
}
}
}
?>

<!DOCTYPE HTML>
<html lang="pl">
<head>
	<meta charset="utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<title>Ideas4learning</title>

<style>
.error
{
	color: red;
	margin-top: 10px;
	margin-bottom: 10px;
}
</style>
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
   <br/><br/>
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
  <input type="submit" value="Prześlij formularz">
</form>					
</body>

</html>