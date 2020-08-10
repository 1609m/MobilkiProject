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
					$conn->query("INSERT INTO zadania VALUES(NULL,12,1,'$zdanie','$klucz','')");
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
							<textarea name="wiadomosc" rows="6" cols="80"></textarea><br>
							<input type="hidden" name="action" value="ogloszenie">
							<input type="submit" value="Wyślij">
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
											echo "<hr style='height: 5px; background: black; border: 0px;'>";
										}
										echo "Klasa: ".$rowKlasa['nazwa']."<br><br>Wiadomość: <br>".str_replace("\n", "<br>",$row['wiadomosc'])."<br><br><input type='hidden' name='rozwinOgloszenia'><input name='$i' type='submit' value='Usuń ogłoszenie'>";
										$j++;
									}
												
								}
								$conn->close();
							?>		
						</form>
					</div>

					<br>
					<h3>2. Uzupełnij lukę</h3>
					<button onclick="rozwin('e1')" class="dropdown-toggle w-75 btn-secondary">Otwórz</button><br>
					<div id="e1" class="rozwin">
						<br>
						<?php
							require_once "dbconnect.php";
							$conn = new mysqli($host, $user, $pass, $db);
							
							$result = $conn->query("SELECT * FROM zadania WHERE przedmiot_id = 12 AND typ = 1");
							$i = 0;
							while ($row = $result->fetch_assoc()) {
								$zdanie = explode("#", $row['tresc'],2);
								$uzupelnij = $row['klucz'];
								$i++;
								echo "<b>".$i.".</b> ".$zdanie[0]."<input type='text' size='10' style='text-align: center;' value='$uzupelnij'>".$zdanie[1]."<br>[Słowo do uzupełnienia: ".$row['klucz']."]<br><a href='#' class='text-warning'>Zadaj!</a><br><br>";
								
							}
							$conn->close();
						?>
						
						
					</div>
					<button onclick="rozwin('d1')" class="dropdown-toggle w-75 btn-secondary">Dodaj zadanie</button><br>
					<div id="d1" class="rozwin">
						<br>
						<form action="zadanieAng.php" method="post" id="post2">
							<label>Wpisz zdanie, słowo do uzupełnienia zastąp znakiem #<br><input type="text" name="zdanie"></label><br>
							<label>Jakie słowo powinno być wstawione?<br><input type="text" name="klucz"></label><br>
							<input type="hidden" name="action" value="zadanie">
							<input type="submit" value="Dodaj">
						</form>
						<br>
						<?php
							if (isset($_SESSION['er_dodaj1'])) {
								echo "<div class='error'>".$_SESSION['er_dodaj1']."</div><br>";
								unset($_SESSION['er_dodaj1']);
							}
						?>
								
					</div>
					
					<h3>3. Zrób cośtam innego</h3>
					
					<p class="text-left"><b>1. Uzupełnianie:</b><br>Przykład:	
					</p>
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