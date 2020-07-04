<?php
	if (session_status() == PHP_SESSION_NONE)
	{
		session_start();
	}
	if(!isset($_SESSION['zalogowany']))
	{
		header('Location: index.php');
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
	
	<?php $active = "mojeKonto"; require "header.php";	?>
	<main>
		<div class="container-fluid">
			<div class="row">
				
				<div class="col-sm-8 my-3 mx-auto bg-secondary text-center text-light">
				
					<h2>Moje konto</h2>
					<br>
					<div class="d-inline-block text-right align-top w-25">
						<p>Imię: </p>
						<p>Nazwisko: </p>
						<p>Login: </p>
<?php
	if($_SESSION['zalogowany'][0] == 'u')
		echo "<p>Klasa: </p>";
	else
		echo "<p>Prowadzone przedmioty: ";
?>
					</div>
					<div class="d-inline-block text-left w-25">
<?php
	/*session_start() w headerze*/
	require_once "dbconnect.php";
	$conn = new mysqli($host, $user, $pass, $db);
	
	$dane = explode(",", $_SESSION['zalogowany']);
	if($dane[0] == "n")
		$result = $conn->query("SELECT * FROM nauczyciele WHERE id = '$dane[1]'");
	else
		$result = $conn->query("SELECT * FROM uczniowie WHERE id = '$dane[1]'");
	
	$row = $result->fetch_assoc();
	
	echo "<span class='font-weight-bold'><p>".$row['imie']."</p><p>".$row['nazwisko']."</p><p>".$row['login']."</p>";
	
	if($dane[0] == "n")
	{
		$id = $row['id'];
		$result = $conn->query("SELECT nazwa FROM przedmioty, zajecia WHERE przedmioty.id = zajecia.przedmiot_id AND nauczyciel_id = '$id'");
		while($row = $result->fetch_row() )
		{
			echo $row[0].",<br>";
		}
	}
	else
	{
		$id = $row['klasa_id'];
		$result = $conn->query("SELECT nazwa FROM klasy WHERE id = '$id'");
		$row = $result->fetch_row();
		echo $row[0];
	}
	echo "</p></span>";
	
	if(isset($_POST['stareHaslo']))
	{
		if(password_verify($_POST['stareHaslo'], $row['haslo']))
		{
			$haslo = $_POST['haslo'];
			$haslo2 = $_POST['haslo2'];
			$jest_ok = true;
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
			if($jest_ok == true)
			{
				$haslo_hash = password_hash($haslo, PASSWORD_DEFAULT);
				if($dane[0] == "n")
					$conn->query("UPDATE nauczyciele SET haslo = '$haslo_hash' WHERE imie = '$dane[1]' AND nazwisko = '$dane[2]'");
				else
					$conn->query("UPDATE uczniowie SET haslo = '$haslo_hash' WHERE imie = '$dane[1]' AND nazwisko = '$dane[2]'");
				$_SESSION['zmiana'] = "<h3 class='text-success font-weight-bold'>Hasło zostało zmienione pomyślnie!</h3>";
			}
			
		}
		else
		{
			$_SESSION['er_haslo'] = "Nieprawidłowe hasło!";
		}
	}
	
	$conn->close();
?>
					</div>
					<br><br>
<?php
	if(isset($_SESSION['zmiana']))
	{
		echo $_SESSION['zmiana'];
		unset($_SESSION['zmiana']);
	}
?>
					<h3>Zmiana hasła:</h3>
					
						<form action="mojeKonto.php" method="post">
							
							<div class="formularz text-left">
							<label>Stare hasło:<br><input type="password" name="stareHaslo"></label><br>
							<label>Nowe hasło:<br><input type="password" name="haslo"></label><br>
							<label>Powtórz nowe hasło:<br><input type="password" name="haslo2"></label>
<?php
	if(isset($_SESSION['er_haslo']))
	{
		echo "</div>";
		echo '<div class="error">'.$_SESSION['er_haslo'].'</div>';
		unset($_SESSION['er_haslo']);
		echo"<div class='formularz text-left'>";
	}
?>
							</div>
							<br>
							<input type="submit" value="Zmień hasło!"><br><br>
							
						</form>
					
					
					
				</div>
				
				
			</div>
		</div>
	</main>
	
	
	
	
	
	<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
	
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
	
	<script src="js/bootstrap.min.js"></script>
</body>
</html>