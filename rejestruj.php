<?php
	
	session_start();
	
	if(isset($_POST['imie']))
	{
		$jest_ok = true;
		
		$imie = $_POST['imie'];
		if(ctype_alnum($imie) == false || ctype_upper($imie[0]) == false)
		{
			$jest_ok = false;
			$_SESSION['er_imie'] = "Imię musi rozpoczynać się z wilekiej litery i składać ze znaków alfanumerycznych!";
		}
		
		$nazwisko = $_POST['nazwisko'];
		if(ctype_alnum($nazwisko) == false || ctype_upper($nazwisko[0]) == false)
		{
			$jest_ok = false;
			$_SESSION['er_nazwisko'] = "Nazwisko musi rozpoczynać się z wilekiej litery i składać ze znaków alfanumerycznych!";
		}
		
		$login = $_POST['login'];
		if(ctype_alnum($login) == false)
		{
			$jest_ok = false;
			$_SESSION['er_login'] = "Login musi składać się ze znaków alfanumerycznych!";
		}
		
		$haslo = $_POST['haslo'];
		$haslo2 = $_POST['haslo2'];
		if($haslo != $haslo2)
		{
			$jest_ok = false;
			$_SESSION['er_haslo'] = "Podane hasła muszą być identyczne!";
		}
		if(strlen($haslo) < 8 || strlen($haslo) > 20)
		{
			$jest_ok = false;
			$_SESSION['er_haslo'] = "Hasło musi posiadać od 8 do 20 znaków!";
		}
		$haslo_hash = password_hash($haslo, PASSWORD_DEFAULT);
		
		if( !isset($_POST['regulamin']))
		{
			$jest_ok = false;
			$_SESSION['er_reg'] = "Musisz zaakceptować regulamin!";
		}
		
		//captcha
		$secret = "6Ld2K_gUAAAAAEwPS8PgaRRlbEU7eKRDnDK7c0gX";
		$sprawdz = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$secret.'&response='.$_POST['g-recaptcha-response']);
		
		$odpowiedz = json_decode($sprawdz);
		
		if($odpowiedz->success == false)
		{
			$jest_ok = false;
			$_SESSION['er_captcha'] = "Potwierdź, że nie jesteś botem!";
		}
		
		require_once "dbconnect.php";
		
		$conn = new mysqli($host, $user, $pass, $db);
		
		//login - unikatowość
		$result = $conn->query("SELECT id FROM nauczyciele WHERE nauczyciele.login = '$login'");
		$ile_loginow = $result->num_rows;
		$result = $conn->query("SELECT id FROM uczniowie WHERE uczniowie.login = '$login'");
		$ile_loginow += $result->num_rows;
		if($ile_loginow > 0)
		{
			$jest_ok = false;
			$_SESSION['er_login'] = "Istnieje już konto o takim loginie!";
		}
		
		if($jest_ok == true)
		{
			if($_POST['status'] == "uczen")
			{//jestem uczniem
				$klasa = $_POST['klasa'];
				$conn->query("INSERT INTO uczniowie VALUES(NULL,'$imie','$nazwisko','$login','$haslo_hash','$klasa')");
			}
			else
			{//jestem nauczycielem
				$conn->query("INSERT INTO nauczyciele VALUES(NULL,'$imie','$nazwisko','$login','$haslo_hash')");
				
				//zaznaczone przedmioty
				$id_naucz = $conn->query("SELECT id FROM nauczyciele ORDER BY id DESC LIMIT 1");
				$id_naucz = $id_naucz->fetch_row();
				$result = $conn->query("SELECT id FROM przedmioty");
				$ile_przedmiotow = $result->num_rows;
				for($i = 1; $i <= $ile_przedmiotow; $i++)
				{
					$row = $result->fetch_row();
					$name = "p".$row[0];
					if(isset($_POST["$name"]))
					{
						$conn->query("INSERT INTO zajecia VALUES(NULL,'$id_naucz[0]','$row[0]')");
					}
				}
				
				$przedmioty = explode(',', $_POST['przedmioty']);
				for($i = 0; isset($przedmioty[$i]) && $przedmioty[$i] != ""; $i++)
				{
					$conn->query("INSERT INTO przedmioty VALUES(NULL, '$przedmioty[$i]')");
					$result = $conn->query("SELECT id FROM przedmioty ORDER BY id DESC LIMIT 1");
					$id_przed = $result->fetch_row();
					$conn->query("INSERT INTO zajecia VALUES(NULL,'$id_naucz[0]','$id_przed[0]')");
				}
			}
		}
		else
		{
			//zachowaj dane
			
			$_SESSION['f_imie'] = $imie;
			$_SESSION['f_nazwisko'] = $nazwisko;
			$_SESSION['f_login'] = $login;
			if(isset($_POST['regulamin']))
				$_SESSION['f_reg'] = true;
			
			
			header('Location: logowanie.php');
			exit();
		}
		
		$conn->close();
	}
	else
	{
		if(isset($_SESSION['zalogowany']))
			header('Location: index.php');
		else
			header('Location: logowanie.php');
		exit();
	}
	
?>
<!DOCTYPE html>
<html lang="pl">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	
	<title>Szkoła z WWW</title>
	<meta name="description" content="Zdalna nauka">
	<meta name="keywords" content="elearning, nauka, szkola, zdalne, nauczanie">
	<meta name="author" content="Wojciech Mila">
	<meta http-equiv="X-Ua-Compatible" content="IE=edge">
	
	<link rel="stylesheet" href="css/bootstrap.min.css">
	<link rel="stylesheet" href="main.css">
	<link href="https://fonts.googleapis.com/css?family=Open+Sans:400,700&amp;subset=latin-ext" rel="stylesheet">
</head>

<body>
	
	<?php $active = ""; require "header.php";	?>
	<main>
		<div class="container-fluid">
			<div class="row">
			
				<div class="col-sm-8 col-sm-12 my-3 bg-secondary text-center text-light">
					
					<h2>GRATULACJE</h2>
					<p>Rejestracja przebiegła pomyślnie!</p>
					<a href="logowanie.php" class="text-warning">Przejdź do strony logowania</a><br><br>
					
				</div>
			</div>
		</div>
	</main>
	
	
	
	
	
	<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
	
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
	
	<script src="js/bootstrap.min.js"></script>
</body>
</html>