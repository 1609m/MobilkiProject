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
						if ($resultOgloszenie->num_rows>0) {
							while ($rowOgloszenie = $resultOgloszenie->fetch_assoc()) {
								echo "<br>".str_replace("\n", "<br>",$rowOgloszenie['wiadomosc'])."<br><hr style='height: 5px; background: #373a3d; border: 0px;'>" ;
							}
						} else {
							echo "<br><hr style='height: 5px; background: #373a3d; border: 0px;'>" ;
						}

						$conn->close();
					?>
					<br>
					<h3>Testy do wykonania</h3>
					<?php
                                require "dbconnect.php";
                                $data = new DateTime();
                                //echo $data->format('Y-m-d');
                                $conn = new mysqli($host, $user, $pass, $db);
                                $dane = explode(",", $_SESSION['zalogowany']);
                                $resultKlasa = $conn->query("SELECT * FROM uczniowie WHERE id = '$dane[1]'");
                                $rowKlasa = $resultKlasa->fetch_assoc();
                                $klasaId = $rowKlasa['klasa_id'];
                                $result = $conn->query("SELECT * FROM paczkazad WHERE klasa_id = $klasaId AND przedmiot_id = 12 ORDER BY termin");
                                $i = 1;
                                while ($row = $result->fetch_assoc()) {
                                    $paczkaId = $row['id'];
                                    $termin = $row['termin'];
                                    $resultIlosc = $conn->query("SELECT COUNT(id) FROM paczkap WHERE paczkazad_id = $paczkaId");
                                    $rowIlosc = ($resultIlosc->fetch_row())[0];
                                    echo "<br>Test ".$i.". <br>Data wygaśnięcia testu: ".$termin."<br>Ilość zadań: ".$rowIlosc."<br>";
                                    echo "<form role='form' action='test.php' method='post'>";
                                    echo "<input type='hidden' value='JĘZYK ANGIELSKI' name='nazwaPrzedmiotu'>";
									echo "<input type='hidden' value='$paczkaId' name='testId'>";
									echo "<input type='hidden' value='Ang' name='Ang'>";
                                    echo "<input type='submit' value='Rozwiąż' class='submitButton'><br></form>";
                                    $i++;
                                }
                                $conn->close();

                        ?>
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