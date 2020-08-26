<?php
	session_start();
	if (!isset($_SESSION['zalogowany'])) {
		header('Location: logowanie.php');
		exit();
	}
	
	$anchor = "";
	if (isset($_POST['klucz'])) {
		$zdanie = $_POST['zdanie'];
		$klucz = $_POST['klucz'];
		
		if (strpos($zdanie, "#") !== false) {
			if ($klucz != "") {
				require_once "dbconnect.php";
				$conn = new mysqli($host, $user, $pass, $db);
				
				$result = $conn->query("SELECT tresc, klucz FROM zadania WHERE przedmiot_id = 12 AND typ = 1");
				$juzJest = false;
				while ($row = $result->fetch_assoc()) {
					if ($row['tresc'] == $zdanie && $row['klucz'] == $klucz)
						$juzJest = true;
				}
				
				if ($juzJest == false) {
					$conn->query("INSERT INTO zadania VALUES(NULL,12,1,'$zdanie','$klucz')");
				} else {
					$_SESSION['er_dodaj1'] = "Takie  zadanie już istnieje.";
					$anchor = "er1";
				}
				
				$conn->close();
			} else {
				$_SESSION['er_dodaj1'] = "Wypełnij oba pola";
				$anchor = "er1";
			}
		} else {
			$_SESSION['er_dodaj1'] = "W zdaniu musi pojawić się znak #";
			$anchor = "er1";
		}
	} else if (isset($_POST['wiadomosc'])) {
		$wiadomosc = $_POST['wiadomosc'];
		
		
			if (($wiadomosc != "") && isset($_POST['klasa'])) {
				$klasa = $_POST['klasa'];
				require_once "dbconnect.php";
				$conn = new mysqli($host, $user, $pass, $db);	
				$dane = explode(",", $_SESSION['zalogowany']);

				$conn->query("INSERT INTO ogloszeniazad VALUES(NULL,'$dane[1]','$klasa',12,'$wiadomosc')");
			
				$conn->close();	 
			} else {
				$_SESSION['er_wiadomosc'] = "Wypełnij wszystkie pola";
				$anchor = "er2";
			}
			
	} else if (isset($_POST['iloscPytan']) && isset($_POST['tekst'])) {
		$anchor = "er4";
		$iloscP =  $_POST['iloscPytan'];
		$tekst = $_POST['tekst'];
		if ($iloscP != "" || $tekst != "") {
			if (ctype_digit($iloscP) == true) {
				

			} else {
				$_SESSION['er_dodaj2'] = "Pole z ilością pytań musi składać się z samych znaków numerycznych!";
				unset($_POST['tekst']);
				unset($_POST['iloscPytan']);
			}
		} else {
			$_SESSION['er_dodaj2'] = "Wypełnij wszystkie pola";
			unset($_POST['tekst']);
			unset($_POST['iloscPytan']);
		}
	} else if (isset($_POST['wroc'])) {
		$anchor = "er4";
		unset($_POST['tekst']);
		unset($_POST['iloscPytan']);
		unset($_POST['tekst2']);
		unset($_POST['wroc']);
	} else if (isset($_POST['tekst2']) && isset($_POST['utworz']) && isset($_POST['iloscPyt'])) {
		$tekst2 = $_POST['tekst2'];
		if ($tekst2 != "") {
			$iloscP = $_POST['iloscPyt'];
			$i = 0;
			$jestT = false;
			for ($i = 0; $i < $iloscP; $i++){
				if (!empty($_POST["n".$i])) {
					$jestT = true;
				}
				$pytanie = $_POST[$i];
				if ($pytanie == "") {
					$anchor = "er4"; 
					$_SESSION['er_dodaj2'] = "Wypełnij wszystkie pola";
					break;
				}
			}

			if (!isset($_SESSION['er_dodaj2']) && $jestT) {
				require_once "dbconnect.php";
				$conn = new mysqli($host, $user, $pass, $db);	
				$tekst2 = $_POST['tekst2'];
				$conn->query("INSERT INTO zadania VALUES(NULL, 12, 2, '$tekst2','')");
		
				$result = $conn->query("SELECT * FROM zadania WHERE przedmiot_id = 12 AND typ = 2 ORDER BY id DESC");
				while ($row = $result->fetch_assoc()) {
					$zadId = $row['id'];
					for ($i = 0; $i < $iloscP; $i++){
						$pytanie = $_POST[$i];
						if (!empty($_POST["n".$i])) {
							$conn->query("INSERT INTO zadaniaabc VALUES(NULL,'$zadId', '$pytanie', 1)");
						} else {
							$conn->query("INSERT INTO zadaniaabc VALUES(NULL,'$zadId', '$pytanie', 0)");
						}
					}
					$conn->close();	
					unset($_POST['utworz']);
					unset($_POST['iloscPyt']);
					unset($_POST['tekst2']);
					$_SESSION['utworzonoZad'] = "";

					break;
				}

			} else {
				$anchor = "er4";
				$_SESSION['er_dodaj2'] = "Musisz zaznaczyć chociaż jedno pole jako T";
			}
		} else {
			$anchor = "er4";
			$_SESSION['er_dodaj2'] = "Wypełnij wszystkie pola";
		}

	} else if (isset($_POST['sprawdz'])) {
		if (isset($_POST['klasaPaczka'])) {
				$klasa = $_POST['klasaPaczka'];
				require_once "dbconnect.php";
				$conn = new mysqli($host, $user, $pass, $db);	
				$result = $conn->query("SELECT * FROM zadania WHERE przedmiot_id = 12 AND typ = 1");
				$ilosc1 = $result->num_rows;
				$result = $conn->query("SELECT * FROM zadania WHERE przedmiot_id = 12 AND typ = 2");
				$ilosc2 = $result->num_rows;
				$zaznaczone = 0;
				for ($i = 1; $i<$ilosc1; $i++) {
					if (!empty($_POST["a".$i])) {
						$zaznaczone = 1;

					} 
				}
				for ($i = 1; $i<$ilosc2; $i++) {
					if (!empty($_POST["b".$i])) {
						$zaznaczone = 1;
					} 
				}
				if ($zaznaczone == 0) {
					$anchor = "er5";
				$_SESSION['er_paczka'] = "Musisz zaznaczyć chociaż jedno zadanie!";
				}

				if (!isset($_SESSION['er_paczka'])) {
					$conn->query("INSERT INTO paczkazad VALUES(NULL,'$klasa','2020-08-30')");
					$result = $conn->query("SELECT * FROM paczkazad ORDER BY id DESC");
					while ($row = $result->fetch_assoc()) {
						$paczkaId = $row['id'];
						break;
					}
					$resultZad =  $conn->query("SELECT * FROM zadania WHERE przedmiot_id = 12 AND typ = 1");
					$j = 1;
					while ($rowZad = $resultZad->fetch_assoc()) {
						if (!empty($_POST["a".$j])) {
							$zadanieId = $rowZad['id'];
							$conn->query("INSERT INTO paczkap VALUES(NULL, '$paczkaId', '$zadanieId')");
						} 
						$j++;
					}
					$resultZad =  $conn->query("SELECT * FROM zadania WHERE przedmiot_id = 12 AND typ = 2");
					$j = 1;
					while ($rowZad = $resultZad->fetch_assoc()) {
						if (!empty($_POST["b".$j])) {
							$zadanieId = $rowZad['id'];
							$conn->query("INSERT INTO paczkap VALUES(NULL, '$paczkaId', '$zadanieId')");
						} 
						$j++;
					}
					$_SESSION['utworzonoPaczke'] = "";
				}
		} else {
			$anchor = "er5";
			$_SESSION['er_paczka'] = "Wybierz klasę";
		}
		
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
	<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,700&amp;subset=latin-ext" rel="stylesheet">	

	<script type="text/javascript">
		
		function rozwin(id)
		{
			var obiekt = document.getElementById(id);
			if(obiekt.style.display == 'block')
			{
				obiekt.style.display = 'none';
			}
			else
			{
				obiekt.style.display = 'block';
			}
		}
		
		function start()
		{
			if ("er1" == "<?= $anchor; ?>") {
				var obj = document.getElementById("d1");
				obj.style.display = 'block';
				window.location.hash = "d1";
			} else if ("er2" == "<?= $anchor; ?>") {
				var obj = document.getElementById("a1");
				obj.style.display = 'block';
				window.location.hash = "a1";
			} else if ("er3" == "<?= $anchor; ?>") {
				var obj = document.getElementById("c1");
				obj.style.display = 'block';
				window.location.hash = "c1";
			} else if ("er4" == "<?= $anchor; ?>") {
				var obj = document.getElementById("x1");
				obj.style.display = 'block';
				window.location.hash = "x1";
			} else if ("er5" == "<?= $anchor; ?>") {
				var obj = document.getElementById("z1");
				obj.style.display = 'block';
				window.location.hash = "z1";
			}
		}
		setTimeout(start,50);
		
	</script>
		
</head>

<body>

	
	<?php $active = "z_ang"; require "header.php";	?>
	<main>
		<div class="container-fluid">
			<div class="row">
				
				<div class="col-sm-8 my-3 mx-auto bg-secondary text-center text-light">
					
					<h2>JĘZYK ANGIELSKI</h2><br>
					
					<?php
						if (isset($_POST['klucz']) && !isset($_SESSION['er_dodaj1'])) {
							echo "<h3 class='text-success font-weight-bold'>Zadanie zostało dodane pomyślnie!</h3><br>";
						} else if (isset($_POST['wiadomosc']) && !isset($_SESSION['er_wiadomosc'])) {
							echo "<h3 class='text-success font-weight-bold'>Ogłoszenie zostało wysłane!</h3><br>";
						} else if (isset($_POST['rozwinOgloszenia'])) {
							echo "<h3 class='text-success font-weight-bold'>Pomyślnie usunięto ogłoszenie!</h3><br>";
						} else if (isset($_SESSION['utworzonoZad']) && !isset($_SESSION['er_dodaj2'])) {
							echo "<h3 class='text-success font-weight-bold'>Zadanie zostało dodane pomyślnie!</h3><br>";
							unset($_SESSION['utworzonoZad']);
						} else if (isset($_SESSION['utworzonoPaczke'])) {
							echo "<h3 class='text-success font-weight-bold'>Zadanie zostało dodane pomyślnie!</h3><br>";
							unset($_SESSION['utworzonoPaczke']);
						}
					?>
					
					<h3>1. Dodaj Ogłoszenie</h3>
					<button onclick="rozwin('a1')" class="dropdown-toggle w-75 btn-secondary">Dodaj Nowe Ogłoszenie</button><br>
					<div id="a1" class="rozwin">
						<br>
						<form role="form" action="zadanieAng.php" method="post" id="post1">
							<label for="klasa" id="klasa2">Wybierz klasę
							<select name="klasa" id="klasa" >
								<option selected="" disabled="">Wybierz klasę </option>
								<?php
										require_once "dbconnect.php";		
										$conn =  new mysqli($host, $user, $pass, $db);	
										$dane = explode(",", $_SESSION['zalogowany']);
										$result = $conn->query("SELECT * FROM klasy");
										
										while ($row = $result->fetch_row()) {
											echo "<option value='$row[0]'>$row[1]</option>";
											
										}
										$conn->close();
								?>																	
							</select>
							</label> 
							
							<br>
							Napisz wiadomość<br>
							<textarea name="wiadomosc" rows="6" class="w-75"></textarea><br>
							<input type="hidden" name="action" value="ogloszenie">
							<input type="submit" value="Wyślij" class="submitButton">
						</form>
						<br>
						<?php
							if (isset($_SESSION['er_wiadomosc'])) {
								echo "<div class='error'>".$_SESSION['er_wiadomosc']."</div><br>";
								unset($_SESSION['er_wiadomosc']);
							}
						?>
						
						
					</div>
					
					<button onclick="rozwin('c1')" class="dropdown-toggle w-75 btn-secondary">Twoje Ogłoszenia</button><br>
					<div id="c1" class="rozwin">
						<form role="form" action="zadanieAng.php" method="post" id="post1">
							<br>
							<?php
								require_once "dbconnect.php";
								$conn = new mysqli($host, $user, $pass, $db);
								$dane = explode(",", $_SESSION['zalogowany']);
								$result = $conn->query("SELECT * FROM ogloszeniazad WHERE nauczyciel_id = '$dane[1]' AND przedmiot_id = '12' ORDER BY id DESC");
								$i = 0;
								$j = 1;
								while ($row = $result->fetch_assoc()) {
									$i++;
									if (isset($_POST["$i"]) && $_POST["$i"] != 'delete') {
										$numer = $_POST["$i"];
										$ogloszenieId = $row['id'];
										$conn->query("DELETE FROM ogloszeniazad WHERE id = '$ogloszenieId' ");
										$_POST["$i"] = 'delete';
										$i--;
									} else {
										$klasaId = $row['klasa_id'];
										$resultKlasa = $conn->query("SELECT * FROM klasy WHERE '$klasaId' = klasy.id");
										$rowKlasa = $resultKlasa->fetch_assoc();

										$przedmiotId = $row['przedmiot_id'];
										$resultPrzedmiot = $conn->query("SELECT * FROM przedmioty WHERE '$przedmiotId' = id");
										$rowPrzedmiot = $resultPrzedmiot->fetch_assoc();
										
										if ($j>1) {
											echo "<hr style='height: 5px; background: #373a3d; border: 0px;'>";
										}
										echo "Klasa: ".$rowKlasa['nazwa']."<br><br>Wiadomość: <br>".str_replace("\n", "<br>",$row['wiadomosc'])."<br><br><input type='hidden' name='rozwinOgloszenia'><input name='$i' type='submit' value='Usuń ogłoszenie' class='submitButton'>";
										$j++;
									}
												
								}
								$conn->close();
							?>		
						</form>
					</div>

					<br>
					<h3>2. Uzupełnij lukę</h3>
					
					<button onclick="rozwin('d1')" class="dropdown-toggle w-75 btn-secondary">Dodaj zadanie</button><br>
					<div id="d1" class="rozwin">
						<br>
						<form action="zadanieAng.php" method="post">
							<label>Wpisz zdanie, słowo do uzupełnienia zastąp znakiem #<br><input type="text" name="zdanie"></label><br>
							<label>Jakie słowo powinno być wstawione?<br><input type="text" name="klucz"></label><br>
							<input type="hidden" name="action" value="zadanie">
							<input type="submit" value="Dodaj" class="submitButton">
						</form>
						<br>
						<?php
							if (isset($_SESSION['er_dodaj1'])) {
								echo "<div class='error'>".$_SESSION['er_dodaj1']."</div><br>";
								unset($_SESSION['er_dodaj1']);
							}
						?>
								
					</div>
					<button onclick="rozwin('e1')" class="dropdown-toggle w-75 btn-secondary">Otwórz</button><br>
					<div id="e1" class="rozwin">
						<br>
						<?php
							require_once "dbconnect.php";
							$conn = new mysqli($host, $user, $pass, $db);
							
							$result = $conn->query("SELECT * FROM zadania WHERE przedmiot_id = 12 AND typ = 1");
							$i = 0;
							while ($row = $result->fetch_assoc()) {
								$zdanie = explode("#", $row['tresc'], 2);
								$uzupelnij = $row['klucz'];
								echo "<b>".++$i.".</b> ".$zdanie[0]."<input type='text' size='10' style='text-align: center;' value='$uzupelnij'>".$zdanie[1]."<br>[Słowo do uzupełnienia: ".$row['klucz']."]<br><br>";
								
							}
							$conn->close();
						?>
						
						
					</div>
					<br>
					<h3>3. Zadanie typu ABCD</h3>

					<button onclick="rozwin('x1')" class="dropdown-toggle w-75 btn-secondary">Dodaj zadanie</button><br>
					<div id="x1" class="rozwin">
							<?php
								if (!isset($_POST['tekst']) && !isset($_POST['iloscPytan']) && !isset($_POST['tekst2'])) {
									echo "<br><form action='zadanieAng.php' method='post'>";
									echo "Wklej tekst<br><textarea name='tekst' rows='6' class='w-75'></textarea><br>";
									echo "Ile ma być pytań?<br><input type='text' name='iloscPytan'><br>";
									echo "<br><input type='submit' value='Zatwierdź' class='submitButton'>";
									echo "</form><br>";
								} else if (isset($_POST['tekst']) && isset($_POST['iloscPytan']) && !isset($_POST['tekst2'])) {
									$tekst = $_POST['tekst'];
									$iloscP = $_POST['iloscPytan'];
									echo "<br><form action='zadanieAng.php' method='post'>";
									echo "Wybrany tekst:<br><textarea name='tekst2' rows='6' class='w-75'>".$tekst."</textarea><br><br>";
									echo "Wprowadź pytania oraz zaznacz poprawne odpowiedzi<br>";
									for ($i = 0; $i < $iloscP; $i++) {
										echo "<input type='text' name='$i'> <label><input type='checkbox' name='n$i'></label><br>";
									}
									echo "<input type='hidden' name='iloscPyt' value = '$iloscP'>";
									echo "<br><input type='submit' name = 'wroc' value='Wróć' class='submitButton'> <input type='submit' name = 'utworz' value='Utwórz zadanie' class='submitButton'>";
									echo "</form><br>";
								} else if (!isset($_POST['tekst']) && !isset($_POST['iloscPytan']) && isset($_POST['tekst2'])) {
									$tekst2 = $_POST['tekst2'];
									$iloscP = $_POST['iloscPyt'];
									echo "<br><form action='zadanieAng.php' method='post'>";
									echo "Wybrany tekst:<br><textarea name='tekst2' rows='6' class='w-75'>".str_replace("\n", "<br>",$tekst2)."</textarea><br><br>";
									echo "Wprowadź pytania oraz zaznacz poprawne odpowiedzi<br>";
									for ($i = 0; $i < $iloscP; $i++) {
										echo "<input type='text' name='$i'> <label><input type='checkbox' name='n$i'></label><br>";
									}
									echo "<input type='hidden' name='iloscPyt' value = '$iloscP'>";
									echo "<br><input type='submit' name = 'wroc' value='Wróć' class='submitButton'> <input type='submit' name = 'utworz' value='Utwórz zadanie' class='submitButton'>";
									echo "</form><br>";
								} 

								if (isset($_SESSION['er_dodaj2'])) {
									echo "<div class='error'>".$_SESSION['er_dodaj2']."</div><br>";
									unset($_SESSION['er_dodaj2']);
									
								}

							?>	
					</div>
					<button onclick="rozwin('y1')" class="dropdown-toggle w-75 btn-secondary">Otwórz</button><br>
					<div id="y1" class="rozwin">
						<br>
						<?php
							require_once "dbconnect.php";
							$conn = new mysqli($host, $user, $pass, $db);
							$result = $conn->query("SELECT * FROM zadania WHERE przedmiot_id = 12 AND typ = 2");

							$i = 1;
							
							while ($row = $result->fetch_assoc()) {
								$tekst = $row['tresc'];
								$zadanieId = $row['id'];
								echo "<b>".$i.".</b> Tekst: <br>".$tekst."<br><br> Pytania: <br>";
								$resultZadabc = $conn->query("SELECT * FROM zadaniaabc WHERE zadania_id = '$zadanieId'");
								$j = 833;
								while ($rowZadabc = $resultZadabc->fetch_assoc()) {
									$pytanie = $rowZadabc['pytanie'];
									$prawda = $rowZadabc['TF'];
									if ($prawda == '1') {
										echo chr($j).") ".$pytanie.", T<br>";
									} else {
										echo chr($j).") ".$pytanie.", F<br>";	
									}
									$j++;
								}
								$i++;
								echo "<br>";
								
							}
							$conn->close();
						?>
						
						
					</div>
					<br>
					<h3>3. Zadaj zadania</h3>

					<button onclick="rozwin('z1')" class="dropdown-toggle w-75 btn-secondary">Stwórz paczkę z zadaniami</button><br>
					<div id="z1" class="rozwin">
						<br>
						<form action='zadanieAng.php' method='post'>
							<label for="klasa" id="klasa2">Wybierz klasę
								<select name="klasaPaczka" id="klasa" >
									<option selected="" disabled="">Wybierz klasę </option>
									<?php
											require_once "dbconnect.php";		
											$conn =  new mysqli($host, $user, $pass, $db);	
											$dane = explode(",", $_SESSION['zalogowany']);
											$result = $conn->query("SELECT * FROM klasy");
											
											while ($row = $result->fetch_row()) {
												echo "<option value='$row[0]'>$row[1]</option>";
												
											}
											$conn->close();
									?>																	
								</select>
							</label> 
							<input type='hidden' name='sprawdz'>
							<br>
							<h4>Zaznacz zadania które chcesz dołączyć do paczki</h4>
							<br>
							Zadania "uzupełnij lukę"
							<br>
							<?php
								require_once "dbconnect.php";		
								$conn =  new mysqli($host, $user, $pass, $db);	
								$result = $conn->query("SELECT * FROM zadania WHERE typ = '1' AND przedmiot_id = '12'");
								$i = 1;
								while ($row = $result->fetch_row()) {
									echo "<b>".$i.".</b> <input type='checkbox' name='a$i'> ";
									$i++;
								}
								$conn->close();
							?>
							<br>
							Zadania "ABCD"
							<br>
							<?php
								require_once "dbconnect.php";		
								$conn =  new mysqli($host, $user, $pass, $db);	
								$result = $conn->query("SELECT * FROM zadania WHERE typ = '2' AND przedmiot_id = '12'");
								$i = 1;
								while ($row = $result->fetch_row()) {
									echo "<b>".$i.".</b> <input type='checkbox' name='b$i'> ";
									$i++;
								}
								$conn->close();
							?>
							<br><br>
							<input type="submit" value="Utwórz" class="submitButton">
							<br><br>

							<?php
							if (isset($_SESSION['er_paczka'])) {
								echo "<div class='error'>".$_SESSION['er_paczka']."</div><br>";
								unset($_SESSION['er_paczka']);
							}
							?>

						</form>
						<br>
					</div>
					<br><br>	
				</div>	
			</div>
		</div>
	</main>
	
	
	
	
	
	<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
	
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
	
	<script src="js/bootstrap.min.js"></script>
</body>
</html>