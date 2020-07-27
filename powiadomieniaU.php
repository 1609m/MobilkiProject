<?php
	session_start();
	if(!isset($_SESSION['zalogowany']))
	{
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
	
	<?php $active = "powiadomieniaU"; require "header.php";	?>
	<main>
		<div class="container-fluid">
			<div class="row">
				
				<div class="col-sm-8 col-lg-7 my-3 mx-auto bg-secondary text-center text-light">
					<h2>Powiadomienia</h2>
					
					
					
					
					
<?php
	require_once "dbconnect.php";
	$conn = new mysqli($host, $user, $pass, $db);
	$dane = explode(",", $_SESSION['zalogowany']);
	$result = $conn->query("SELECT * FROM uczniowie WHERE uczniowie.id = '$dane[1]'");

	while ($row = $result->fetch_assoc())
	{
		$klasaId = $row['klasa_id'];
		$resultPowiadomienia = $conn->query("SELECT * FROM powiadomienia WHERE '$klasaId' = klasa_id ORDER BY id DESC ");
		while ($rowPowiadomienia = $resultPowiadomienia->fetch_assoc()) {

			$nauczycielId = $rowPowiadomienia['nauczyciel_id'];
			$resultNauczyciel = $conn->query("SELECT * FROM nauczyciele WHERE '$nauczycielId' = nauczyciele.id");
			$rowNauczyciel = $resultNauczyciel->fetch_assoc();

			$przedmiotId = $rowPowiadomienia['przedmiot_id'];
			$resultPrzedmiot = $conn->query("SELECT * FROM przedmioty WHERE '$przedmiotId' = przedmioty.id");
			$rowPrzedmiot = $resultPrzedmiot->fetch_assoc();
			echo "<hr style='height: 5px; background: black; border: 0px;'>Prowadzący: ".$rowNauczyciel['imie']." ".$rowNauczyciel['nazwisko']."<br> Przedmiot: ".$rowPrzedmiot['nazwa']."<br><br>".str_replace("\n", "<br>",$rowPowiadomienia['wiadomosc'])."<br><br>";
		}	
	}
	$conn->close();
?>
					
					
				</div>
				
				
			</div>
		</div>
	</main>
	
	
	
	
	
	<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
	
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
	
	<script src="js/bootstrap.min.js"></script>
</body>
</html>