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
	
	<?php $active = "index"; require "header.php";	?>
	<main>
		<div class="container-fluid">
			<div class="row">
				
				<div class="col-sm-8 my-3 mx-auto bg-secondary text-center text-light">
					<?php
						if (isset($_SESSION['logout'])) {
							echo "<h2>Wylogowano pomyślnie!</h2>";
							unset($_SESSION['logout']);
							exit();
						}
					?>
					<h3>Witamy na stronie
					<?php
						if (isset($_SESSION['zalogowany'])) {					
							$dane = explode(',', $_SESSION['zalogowany'], 3);
							echo ", ".$dane[2]."!</h3>";
							
							if ($dane[0] == "u") {
								require_once "dbconnect.php";
								$conn = new mysqli($host, $user, $pass, $db);
								$resultOdczytane = $conn->query("SELECT * FROM odczytane WHERE uczen_id = '$dane[1]' ORDER BY id DESC");
								if ($resultOdczytane->num_rows>0) {
									echo "<br><h3> Masz nowe powiadomienia!</h3>";
									while ($rowOdczytane = $resultOdczytane->fetch_assoc()) {
										$powiadomienieId = $rowOdczytane['powiadomienie_id'];
										$uczenId = $rowOdczytane['uczen_id'];			
										$resultUczen = $conn->query("SELECT * FROM uczniowie WHERE id = '$uczenId'");
										$rowUczen = $resultUczen->fetch_assoc();
										$klasaId = $rowUczen['klasa_id'];

										$resultPowiadomienia = $conn->query("SELECT * FROM powiadomienia WHERE ('$klasaId' = klasa_id OR '$uczenId' = uczenO_id) AND '$powiadomienieId' = id");
										$rowPowiadomienia = $resultPowiadomienia->fetch_assoc();
										$klasaIdPowiadomienia = $rowPowiadomienia['klasa_id'];
										$uczenIdPowiadomienia = $rowPowiadomienia['uczen_id'];
										$nauczycielIdPowiadomienia = $rowPowiadomienia['nauczyciel_id'];
										

										if ($nauczycielIdPowiadomienia != '0') {
											$resultNauczyciel = $conn->query("SELECT * FROM nauczyciele WHERE '$nauczycielIdPowiadomienia' = id");
											$rowNauczyciel = $resultNauczyciel->fetch_assoc();

											$przedmiotId = $rowPowiadomienia['przedmiot_id'];
											$resultPrzedmiot = $conn->query("SELECT * FROM przedmioty WHERE '$przedmiotId' = id");
											$rowPrzedmiot = $resultPrzedmiot->fetch_assoc();
											
											echo "<hr style='height: 5px; background: black; border: 0px;'>Prowadzący: ".$rowNauczyciel['imie']." ".$rowNauczyciel['nazwisko']."<br> Przedmiot: ".$rowPrzedmiot['nazwa']."<br><br>".str_replace("\n", "<br>",$rowPowiadomienia['wiadomosc'])."<br><br>";
											
											$conn->query("UPDATE odczytane SET odcz = 1 WHERE uczen_id = '$dane[1]' AND '$powiadomienieId' = powiadomienie_id");
										} else {
											$resultUczen = $conn->query("SELECT * FROM uczniowie WHERE '$uczenIdPowiadomienia' = id");
											$rowUczen = $resultUczen->fetch_assoc();
											$KlasaId = $rowUczen['klasa_id'];

											$resultKlasa = $conn->query("SELECT * FROM klasy WHERE '$KlasaId' = id");
											$rowKlasa = $resultKlasa->fetch_assoc();											

											echo "<hr style='height: 5px; background: black; border: 0px;'>Uczeń: ".$rowUczen['imie']." ".$rowUczen['nazwisko']."<br> Klasa: ".$rowKlasa['nazwa']."<br><br>".str_replace("\n", "<br>",$rowPowiadomienia['wiadomosc'])."<br><br>";

											$conn->query("UPDATE odczytane SET odcz = 1 WHERE uczen_id = '$dane[1]' AND '$powiadomienieId' = powiadomienie_id");
										}
											
												
									}
								}
							} else {
								require_once "dbconnect.php";
								$conn = new mysqli($host, $user, $pass, $db);
								$resultOdczytane = $conn->query("SELECT * FROM odczytane WHERE nauczyciel_id = '$dane[1]' ORDER BY id DESC");
								if ($resultOdczytane->num_rows>0) {
									echo "<br><h3> Masz nowe powiadomienia!</h3>";
									while ($rowOdczytane = $resultOdczytane->fetch_assoc()) {
										$powiadomienieId = $rowOdczytane['powiadomienie_id']; 
										$nauczycielId = $rowOdczytane['nauczyciel_id'];

										$resultPowiadomienia = $conn->query("SELECT * FROM powiadomienia WHERE '$nauczycielId' = nauczycielO_id AND '$powiadomienieId' = powiadomienia.id");
										$rowPowiadomienia = $resultPowiadomienia->fetch_assoc();										
										
										

										if ($rowPowiadomienia['nauczyciel_id'] != 0) {
											$nauczycielIdPowiadomienia = $rowPowiadomienia['nauczyciel_id'];
											$resultNauczyciel = $conn->query("SELECT * FROM nauczyciele WHERE '$nauczycielIdPowiadomienia' = id");
											$rowNauczyciel = $resultNauczyciel->fetch_assoc();
											
											echo "<hr style='height: 5px; background: black; border: 0px;'>".$rowNauczyciel['imie']." ".$rowNauczyciel['nazwisko']."<br><br>".str_replace("\n", "<br>",$rowPowiadomienia['wiadomosc'])."<br><br>";
										
											$conn->query("UPDATE odczytane SET odcz = 1 WHERE nauczyciel_id = '$dane[1]' AND '$powiadomienieId' = powiadomienie_id");

										} else {
											$przedmiotIdPowiadomienia = $rowPowiadomienia['przedmiot_id'];
											$uczenIdPowiadomienia = $rowPowiadomienia['uczen_id'];
											$resultUczen = $conn->query("SELECT * FROM uczniowie WHERE '$uczenIdPowiadomienia' = id");
											$rowUczen = $resultUczen->fetch_assoc();
											$klasaId = $rowUczen['klasa_id'];

											$resultKlasa = $conn->query("SELECT * FROM klasy WHERE '$klasaId' = id");
											$rowKlasa = $resultKlasa->fetch_assoc();

											$resultPrzedmiot = $conn->query("SELECT * FROM przedmioty WHERE '$przedmiotIdPowiadomienia' = id");
											$rowPrzedmiot = $resultPrzedmiot->fetch_assoc();

											echo "<hr style='height: 5px; background: black; border: 0px;'>".$rowUczen['imie']." ".$rowUczen['nazwisko']."<br> Klasa: ".$rowKlasa['nazwa']."<br> Przedmiot: ".$rowPrzedmiot['nazwa']."<br><br>".str_replace("\n", "<br>",$rowPowiadomienia['wiadomosc'])."<br><br>";
										
											$conn->query("UPDATE odczytane SET odcz = 1 WHERE nauczyciel_id = '$dane[1]' AND '$powiadomienieId' = powiadomienie_id");

										}
										
									}
								}
							}
							$conn->close();
						}
						else
							echo "!</h3><br><h4>Zapraszamy do logowania lub rejestracji konta!</h4><p>Link powyżej</p>";
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