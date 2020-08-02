<?php
	session_start();
	if (!isset($_SESSION['zalogowany'])) {
		header('Location: logowanie.php');
		exit();
	}
	
	$anchor = "";
	if (isset($_POST['wiadomosc']) ) {
		
		$wiadomosc = $_POST['wiadomosc'];
		
		
			if (($wiadomosc != "") && isset($_POST['klasa'])) {
				$klasa = $_POST['klasa'];
				$uczen = $_POST['uczen'];
				
				$przedmiot = $_POST['przedmiot'];
				require_once "dbconnect.php";
				$conn = new mysqli($host, $user, $pass, $db);
				

				if ($uczen == '0') {
					

					$dane = explode(",", $_SESSION['zalogowany']);

					$conn->query("INSERT INTO powiadomienia VALUES(NULL,'$dane[1]','0','0','0','$klasa','$przedmiot','$wiadomosc')");
					
					$resultPowiadomienie = $conn->query("SELECT * FROM powiadomienia WHERE wiadomosc = '$wiadomosc' AND '$klasa' = klasa_id ORDER BY id DESC");

					while ($rowPowiadomienie = $resultPowiadomienie->fetch_assoc()) {	
					
						$klasaId = $rowPowiadomienie['klasa_id'];
						$powiadomienieId = $rowPowiadomienie['id'];

						$resultUczen = $conn->query("SELECT * FROM uczniowie WHERE '$klasaId' = klasa_id");

						while ($rowUczen = $resultUczen->fetch_assoc()) {

							$uczenId = $rowUczen['id'];

							$conn->query("INSERT INTO odczytane VALUES(NULL,'$uczenId','0','$powiadomienieId','true')");
						}
					break;
					}
					
				} else {
					

					$dane = explode(",", $_SESSION['zalogowany']);
					$imieNazwisko = explode(" ", $_POST['uczen']);
			
					$resultUczen = $conn->query("SELECT * FROM uczniowie WHERE  imie = '$imieNazwisko[0]'  AND  nazwisko = '$imieNazwisko[1]' ");
					
					$rowUczen = $resultUczen->fetch_assoc();
					$uczenId = $rowUczen['id'];

					$conn->query("INSERT INTO powiadomienia VALUES(NULL,'$dane[1]','0','0','$uczenId','0','$przedmiot','$wiadomosc')");
					
					$resultPowiadomienie = $conn->query("SELECT * FROM powiadomienia WHERE wiadomosc = '$wiadomosc' AND '$uczenId' = uczenO_id ORDER BY id DESC");
					while ($rowPowiadomienie = $resultPowiadomienie->fetch_assoc()) {
						$uczenId = $rowPowiadomienie['uczenO_id'];
						$powiadomienieId = $rowPowiadomienie['id'];
						
						$conn->query("INSERT INTO odczytane VALUES(NULL,'$uczenId','0','$powiadomienieId','true')");
						break;
					}		
				}

			
				$conn->close();	 
			} else if (($wiadomosc != "") && isset($_POST['nauczyciel'])) {
				$nauczyciel = $_POST['nauczyciel'];
				
				require_once "dbconnect.php";
				$conn = new mysqli($host, $user, $pass, $db);
				$dane = explode(",", $_SESSION['zalogowany']);

				$conn->query("INSERT INTO powiadomienia VALUES(NULL,'$dane[1]','$nauczyciel','0','0','0','0','$wiadomosc')");
				
				$resultPowiadomienie = $conn->query("SELECT * FROM powiadomienia WHERE wiadomosc = '$wiadomosc' AND '$nauczyciel' = nauczycielO_id ORDER BY id DESC");
				while ($rowPowiadomienie = $resultPowiadomienie->fetch_assoc()) {
					$uczenId = $rowPowiadomienie['uczen_id'];
					$powiadomienieId = $rowPowiadomienie['id'];
					$conn->query("INSERT INTO odczytane VALUES(NULL,'0','$nauczyciel','$powiadomienieId','true')");
					break;
				}	
				$conn->close();
			} else {
				$_SESSION['er_wiadomosc'] = "Wypełnij wszystkie pola";
				$anchor = "er1";
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
	<link href="https://fonts.googleapis.com/css?family=Open+Sans:400,700&amp;subset=latin-ext" rel="stylesheet">
	<script src="js/jquery-3.2.1.min.js"></script>
	
	
	<script type="text/javascript">
		$(document).ready(function(){
			$("#klasa").change(function(){
				var aid = $("#klasa").val();
				
				
				$.ajax({
					url: 'dataPowiadomienia.php',
					method: 'post',
					data: 'aid=' + aid
				}).done(function(uczen){
					console.log(uczen);
					uczen = JSON.parse(uczen);
					console.log(uczen);
					$('#uczen').empty();
					console.log(uczen.id);
					$('#uczen').append('<option value="0">' + "Cala klasa" + '</option>')
					uczen.forEach(function(uczen){
						
						$('#uczen').append('<option>' + uczen.imie + " "+ uczen.nazwisko + '</option>');
						
					})
					
				})
			})
		})
	</script>
	
	
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
			if("er1" == "<?= $anchor; ?>")
			{
				var obj = document.getElementById("d1");
				obj.style.display = 'block';
				window.location.hash = "d1";
			}
		}
		setTimeout(start,50);	
	</script>
	
	<script>
		function toggle()
		{
			var tryb = document.getElementById('wybierz');
			
			if(tryb.checked == true)
			{
				$('#klasa2').addClass('d-none');
				$('#uczen2').addClass('d-none');
				$('#przedmiot2').addClass('d-none');
				$('#nauczyciel2').removeClass('d-none');
				
			}
			else
			{
				$('#klasa2').removeClass('d-none');
				$('#uczen2').removeClass('d-none');
				$('#przedmiot2').removeClass('d-none');
				$('#nauczyciel2').addClass('d-none');
			}
		}
		
		
	</script>

	
	
	
</head>




<body>
	
	<?php $active = "powiadomienia"; require "header.php";	?>
	<main>
		<div class="container-fluid">
			<div class="row">
				
				<div class="col-sm-8 col-lg-7 my-3 mx-auto bg-secondary text-center text-light">
					<h3>Powiadomienia</h3>
					
					<?php
						if (isset($_POST['wiadomosc']) && !isset($_SESSION['er_wiadomosc'])) {
							echo "<h3 class='text-success font-weight-bold'>Wiadomość została wysłana!</h3><br>";
							
						}
						
					?>					
					<br>	
					<h5>Wyślij nową wiadomość</h5>
					<button onclick="rozwin('d1')" class="dropdown-toggle w-75 btn-secondary">Wyślij wiadomość</button><br>
					<div id="d1" class="rozwin">
						<br>
						<form role="form" action="powiadomienia.php" method="post">
							<label><input type="radio" name="status" value="nauczyciel" onclick="toggle()" id="wybierz"> Nauczyciel</label>
							<label><input type="radio" name="status" value="uczen" onclick="toggle()" checked> Uczeń</label>
							<br><br>
							<label for="klasa" id="klasa2">Wybierz klasę
							<select name="klasa" id="klasa" >
								<option selected="" disabled="">Wybierz klasę </option>
								<?php 
									
										require 'dataPowiadomienia.php';
										$klasy = loadKlasy();
										foreach ($klasy as $klasy) {
											echo "<option id='".$klasy['id']."' value='".$klasy['id']."'>".$klasy['nazwa']."</option>";
										}
								?>																		
							</select>
							</label> 
							
							<label class="d-none for="nauczyciel" id="nauczyciel2">Wybierz nauczyciela
							<select  name="nauczyciel" id="nauczyciel" >
								<option selected="" disabled="">Wybierz nauczyciela </option>
								<?php
									require_once "dbconnect.php";		
									$conn =  new mysqli($host, $user, $pass, $db);	
									$dane = explode(",", $_SESSION['zalogowany']);
									$result = $conn->query("SELECT * FROM nauczyciele");
									
									
									while ($row = $result->fetch_row()) {

										if ($row[0] != $dane[1]) {
										echo "<option value='$row[0]'>$row[1] $row[2]</option>";
										}
										
									}
									$conn->close();	
										
								?>																	
							</select>
							</label> 

							<label id ="uczen2">     Wybierz ucznia 
							<select name="uczen" id="uczen">
							
							</select>
							</label>
							<br>
							
							<label id = "przedmiot2">Wybierz przedmiot
							<select name="przedmiot">
							
								<?php
									require_once "dbconnect.php";		
									$conn =  new mysqli($host, $user, $pass, $db);	
									$dane = explode(",", $_SESSION['zalogowany']);
									$result = $conn->query("SELECT * FROM nauczyciele WHERE id = '$dane[1]'");
									$row = $result->fetch_assoc();
									$id = $row['id'];
									$result = $conn->query("SELECT przedmioty.id, nazwa FROM przedmioty, zajecia WHERE przedmioty.id = zajecia.przedmiot_id AND nauczyciel_id = '$id'");
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
					<br>
					<br>
					<h5>Wysłane wiadomości</h5>
					<button onclick="rozwin('o1')" class="dropdown-toggle w-75 btn-secondary">Otwórz</button><br>
					<div id="o1" class="rozwin">
						<br>
						<?php
							require_once "dbconnect.php";
							$conn = new mysqli($host, $user, $pass, $db);
							$dane = explode(",", $_SESSION['zalogowany']);
							$result = $conn->query("SELECT * FROM powiadomienia WHERE nauczyciel_id = '$dane[1]' ORDER BY id DESC");
							$i = 0;
							while ($row = $result->fetch_assoc()) {
								if ($row['klasa_id'] != '0') {
									$klasaId = $row['klasa_id'];
									$resultKlasa = $conn->query("SELECT * FROM klasy WHERE '$klasaId' = klasy.id");
									$rowKlasa = $resultKlasa->fetch_assoc();

									$przedmiotId = $row['przedmiot_id'];
									$resultPrzedmiot = $conn->query("SELECT * FROM przedmioty WHERE '$przedmiotId' = id");
									$rowPrzedmiot = $resultPrzedmiot->fetch_assoc();
									
									if ($i>0) {
										echo "<hr style='height: 5px; background: black; border: 0px;'>";
									}
									echo "Klasa: ".$rowKlasa['nazwa']."<br> Przedmiot: ".$rowPrzedmiot['nazwa']."<br><br>".str_replace("\n", "<br>",$row['wiadomosc'])."<br><br>";
									$i++;
								} else if ($row['uczenO_id'] != '0') {
									$uczenId = $row['uczenO_id'];
									$resultUczen = $conn->query("SELECT * FROM uczniowie WHERE '$uczenId' = id");
									$rowUczen = $resultUczen->fetch_assoc();

									$przedmiotId = $row['przedmiot_id'];
									$resultPrzedmiot = $conn->query("SELECT * FROM przedmioty WHERE '$przedmiotId' = id");
									$rowPrzedmiot = $resultPrzedmiot->fetch_assoc();

									if ($i>0) {
										echo "<hr style='height: 5px; background: black; border: 0px;'>";
									}
									echo "Uczeń: ".$rowUczen['imie']." ".$rowUczen['nazwisko']."<br> Przedmiot: ".$rowPrzedmiot['nazwa']."<br><br>".str_replace("\n", "<br>",$row['wiadomosc'])."<br><br>";
									$i++;

								} else {
									$nauczycielId = $row['nauczycielO_id'];
									$resultNauczyciel = $conn->query("SELECT * FROM nauczyciele WHERE '$nauczycielId' = id");
									$rowNauczyciel = $resultNauczyciel->fetch_assoc();

									if ($i>0) {
										echo "<hr style='height: 5px; background: black; border: 0px;'>";
									}
									echo "Nauczyciel: ".$rowNauczyciel['imie']." ".$rowNauczyciel['nazwisko']."<br><br>".str_replace("\n", "<br>",$row['wiadomosc'])."<br><br>";
									$i++;
								}
								
							}
							$conn->close();
						?>
						
					
					</div>
					<br>
					<br>

					<h5>Otrzymane powiadomienia</h5>
					<button onclick="rozwin('a1')" class="dropdown-toggle w-75 btn-secondary">Otwórz</button><br>
					<div id="a1" class="rozwin">
						<br>
						<?php
							require_once "dbconnect.php";
							$conn = new mysqli($host, $user, $pass, $db);
							$dane = explode(",", $_SESSION['zalogowany']);

							$result = $conn->query("SELECT * FROM powiadomienia WHERE nauczycielO_id = '$dane[1]' ORDER BY id DESC");
							$i = 0;
							while ($row = $result->fetch_assoc()) {
								if ($row['uczen_id'] != '0') {
									$uczenId = $row['uczen_id'];

									$resultUczen = $conn->query("SELECT * FROM uczniowie WHERE '$uczenId' = id");
									$rowUczen = $resultUczen->fetch_assoc();
									$klasaIdW = $rowUczen['klasa_id'];
	
									$resultKlasa = $conn->query("SELECT * FROM klasy WHERE '$klasaIdW' = id");
									$rowKlasa = $resultKlasa->fetch_assoc();

									if ($i>0) {
										echo "<hr style='height: 5px; background: black; border: 0px;'>";
									}
									echo "Uczeń: ".$rowUczen['imie']." ".$rowUczen['nazwisko']."<br> Klasa: ".$rowKlasa['nazwa']."<br><br>".str_replace("\n", "<br>",$row['wiadomosc'])."<br><br>";
									$i++;
								} else {
									$nauczycielId = $row['nauczyciel_id'];

									$resultNauczyciel = $conn->query("SELECT * FROM nauczyciele WHERE '$nauczycielId' = id");
									$rowNauczyciel = $resultNauczyciel->fetch_assoc();

									if ($i>0) {
										echo "<hr style='height: 5px; background: black; border: 0px;'>";
									}
									echo "Nauczyciel: ".$rowNauczyciel['imie']." ".$rowNauczyciel['nazwisko']."<br><br>".str_replace("\n", "<br>",$row['wiadomosc'])."<br><br>";
									$i++;
								}

							
								
							}
							$conn->close();
						?>
						
					
					</div>
					<br>
					
				</div>
				
				
			</div>
		</div>
	</main>
	
	
	
	
	
	
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
	
	<script src="js/bootstrap.min.js"></script>
</body>
</html>