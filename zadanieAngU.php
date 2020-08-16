<?php
	session_start();
	if(!isset($_SESSION['zalogowany']))
	{
		header('Location: logowanie.php');
		exit();
	}
	

	require_once "dbconnect.php";
	$conn = new mysqli($host, $user, $pass, $db);
	$result = $conn->query("SELECT * FROM zadania");
	
	$iloscWierszy = $result->num_rows;

	$anchor = "";
	
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
			if("er1" == "<?= $anchor; ?>")
			{
				var obj = document.getElementById("d1");
				obj.style.display = 'block';
				window.location.hash = "d1";
			}
		}
		setTimeout(start,50);
		
	</script>
		
</head>

<body>

	
	<?php $active = "z_angU"; require "header.php";	?>
	<main>
		<div class="container-fluid">
			<div class="row">
				
				<div class="col-sm-8 my-3 mx-auto bg-secondary text-center text-light">
					
					<h2>JĘZYK ANGIELSKI</h2>
					<?php
						require_once "dbconnect.php";
						$conn = new mysqli($host, $user, $pass, $db);
						$dane = explode(",", $_SESSION['zalogowany']);

						$resultNauczyciel = $conn->query("SELECT * FROM zajecia WHERE przedmiot_id = 12");
						$i = 1;
						if ($resultNauczyciel->num_rows>1) {
							while ($rowNauczyciel = $resultNauczyciel->fetch_assoc()) {
								$nauczycielId = $rowNauczyciel['nauczyciel_id'];
								$result = $conn->query("SELECT * FROM nauczyciele WHERE $nauczycielId = id");
								$row = $result->fetch_assoc();
								$ilość = $resultNauczyciel->num_rows;
								if ($i < $ilość) {
									echo "<h4> Prowadzący: ".$row['imie']." ".$row['nazwisko'];
								} else {
									echo " & ".$row['imie']." ".$row['nazwisko']."</h4><br><br>";
								}
								$i++;
							}
						} else {
							while ($row = $result->fetch_assoc()) {
								$nauczycielId = $rowNauczyciel['nauczyciel_id'];
								$result = $conn->query("SELECT * FROM nauczyciele WHERE $nauczycielId = id");
								$row = $result->fetch_assoc();
								echo "<h4> Prowadzący: ".$row['imie']." ".$row['nazwisko']."</h4><br><br>";
							}
						}
						$conn->close();
					?>

					<h3>OGŁOSZENIA!</h3>
					<?php
						require_once "dbconnect.php";
						$conn = new mysqli($host, $user, $pass, $db);
						$dane = explode(",", $_SESSION['zalogowany']);
						$result = $conn->query("SELECT * FROM uczniowie WHERE $dane[1] = id");
						$row = $result->fetch_assoc();
						$klasaId = $row['klasa_id'];
						$resultOgloszenie = $conn->query("SELECT * FROM ogloszeniazad WHERE przedmiot_id = '12' AND klasa_id = $klasaId ORDER BY id DESC");
						while ($rowOgloszenie = $resultOgloszenie->fetch_assoc()) {
							echo "<br>".str_replace("\n", "<br>",$rowOgloszenie['wiadomosc'])."<br><hr style='height: 5px; background: #373a3d; border: 0px;'>" ;
						}
						$conn->close();
					?>
					<br>
					<h3>1. Uzupełnij lukę</h3>
						<form action="zadanieAngU.php" method="post">
						
							<br>
							<!-- <?php
								require_once "dbconnect.php";
								$conn = new mysqli($host, $user, $pass, $db);
								
								$result = $conn->query("SELECT * FROM zadania WHERE przedmiot_id = 12 AND typ = 1");
								$i = 0;
								while($row = $result->fetch_assoc())
								{
									$i++;
									$zdanie = explode("#", $row['tresc'],2);
									$uzupelnij = $row['klucz'];
									$tresc = $row['tresc'];
									$idZadania = $row['id'];
									$dane = explode(",", $_SESSION['zalogowany']);

									if (isset($_POST["$i"])) {
										$numer = $_POST["$i"];
										if ($numer != '') {
										$resultKlucz = $conn->query("SELECT klucz FROM zadania WHERE alt = '$i'");
										$rowKlucz = $resultKlucz->fetch_assoc();
										$klucz = $rowKlucz['klucz'];

											if (ctype_alnum($numer) == false || $numer != $klucz) {
												echo "<b>".$i.".</b> ".$zdanie[0]."<input type='text' size='10' style='text-align: center;' name='$i' >".$zdanie[1]."<br><span style='color: #990000; font-weight: bold'>Niestety twoja odpowiedź jest niepoprawna.</span><br><br><button class='sprawdz'>Sprawdź odpowiedź!</button></a><br><br>";
											} else {
												echo "<b>".$i.".</b> ".$zdanie[0].$uzupelnij.$zdanie[1]."<span style='color: #99FF33; font-weight: bold'><br>Brawo twoja odpowiedź jest prawidłowa!</span></a><br><br>";
												
												$conn->query("INSERT INTO wykonanezadania VALUES(NULL,'$dane[1]','$idZadania')");
											}
										} else {
											echo "<b>".$i.".</b> ".$zdanie[0]."<input type='text' size='10' style='text-align: center;' name='$i' >".$zdanie[1]."<br><button class='sprawdz'>Sprawdź odpowiedź!</button></a><br><br>";
										}

									} else {
										$resultWykonane = $conn->query("SELECT * FROM wykonanezadania WHERE zadanieAng_id = '$idZadania' AND uczen_id = '$dane[1]'");
										if ($resultWykonane->num_rows > 0) {
											$rowWykonane = $resultWykonane->fetch_assoc();
											$wykonane = $rowWykonane['zadanieAng_id'];
								
											if ($idZadania == $wykonane) {
												echo "<b>".$i.".</b> ".$zdanie[0].$uzupelnij.$zdanie[1]."<span style='color: #99FF33; font-weight: bold'>       Zadanie Wykonane.</span><br><br>";
												$conn->query("UPDATE zadania SET alt = '$i' WHERE  tresc = '$tresc'");
											} 
										} else {
											echo "<form action='zadanieAngU.php' method='post'><b>".$i.".</b> ".$zdanie[0]."<input type='text' size='10' style='text-align: center;' name='$i' >".$zdanie[1]."<br><button class='sprawdz'>Sprawdź odpowiedź!</button></a><br><br></form>";
											$conn->query("UPDATE zadania SET alt = '$i' WHERE  tresc = '$tresc'");
										}
									}
									
								}
								$conn->close();
							?>	 -->
						</form>
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