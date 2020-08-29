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

	
	<?php $active = "z_matU"; require "header.php";	?>
	<main>
		<div class="container-fluid">
			<div class="row">
				
				<div class="col-sm-8 my-3 mx-auto bg-secondary text-center text-light">
					
					<h2>MATEMATYKA</h2>
					<?php
						require_once "dbconnect.php";
						$conn = new mysqli($host, $user, $pass, $db);
						$dane = explode(",", $_SESSION['zalogowany']);

						$resultNauczyciel = $conn->query("SELECT * FROM zajecia WHERE przedmiot_id = 1");
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
						$resultOgloszenie = $conn->query("SELECT * FROM ogloszeniazad WHERE przedmiot_id = '1' AND klasa_id = $klasaId ORDER BY id DESC");
						while ($rowOgloszenie = $resultOgloszenie->fetch_assoc()) {
							echo "<br>".str_replace("\n", "<br>",$rowOgloszenie['wiadomosc'])."<br><hr style='height: 5px; background: #373a3d; border: 0px;'>" ;
						}
						$conn->close();
					?>
					<br>
                    <h3>Testy do wykonania</h3>
                    <form role="form" action="test.php" method="post" id="post1">
                        <?php
                                require "dbconnect.php";
                                $conn = new mysqli($host, $user, $pass, $db);
                                $dane = explode(",", $_SESSION['zalogowany']);
                                $resultKlasa = $conn->query("SELECT * FROM uczniowie WHERE id = '$dane[1]'");
                                $rowKlasa = $resultKlasa->fetch_assoc();
                                $klasaId = $rowKlasa['klasa_id'];
                                $result = $conn->query("SELECT * FROM paczkazad WHERE klasa_id = $klasaId");
                                $i = 1;
                                while ($row = $result->fetch_assoc()) {
                                    $paczkaId = $row['id'];
                                    $termin = $row['termin'];
                                    $resultIlosc = $conn->query("SELECT COUNT(id) FROM paczkap WHERE paczkazad_id = $paczkaId");
                                    $rowIlosc = ($resultIlosc->fetch_row())[0];
                                    echo "<br>Test ".$i.". <br>Data wygaśnięcia testu: ".$termin."<br>Ilość zadań: ".$rowIlosc."<br>";
                                    echo "<input type='submit' value='Rozwiąż' class='submitButton'><br>";
                                    $i++;
                                    // $zad = $conn->query("SELECT zadania_id FROM paczkap WHERE paczkazad_id=$row[0] LIMIT 1");
                                    // $zad = $zad->fetch_row();
                                    // $przedmiotId = $conn->query("SELECT przedmiot_id FROM zadania WHERE id=$zad[0]");
                                    // $przedmiotId = ($przedmiotId->fetch_row())[0];
                                    // $przedmiot = $conn->query("SELECT * FROM przedmioty WHERE id=$przedmiotId");
                                    // $przedmiot = $przedmiot->fetch_row();
                                    
                                    // $resultZad = $conn->query("SELECT COUNT(id) FROM paczkap WHERE paczkazad_id=$row[0]");
                                    
                                    // $moje = 0;;
                                    // $dane = explode(',', $_SESSION['zalogowany'], 3);
                                    // if ($dane[0] == "n") {
                                    //     $moje = $conn->query("SELECT COUNT(id) FROM zajecia WHERE nauczyciel_id='$dane[1]' AND przedmiot_id='$przedmiot[0]'");
                                    //     $moje = ($moje->fetch_row())[0];
                                    //     if ($moje > 0) {
                                    //         echo "<b>";
                                    //         $moje = -1;
                                    //     }
                                    // } else {
                                    //     $moje = $conn->query("SELECT klasa_id FROM uczniowie WHERE id='$dane[1]'");
                                    //     $moje = ($moje->fetch_row())[0];
                                    //     if ($moje == $resultKl['id']) {
                                    //         echo "<b>";
                                    //         $moje = -1;
                                    //     }
                                    // }
                                    // echo "<h5 class='text-left'>".++$i.".</h5>Klasa: ".$resultKl['nazwa']."<br />";

                                    // echo "Przedmiot: ".$przedmiot[1]."<br />";

                                    // echo "Ilość zadań: ".($resultZad->fetch_row())[0]."<br />";

                                    // if ($moje == -1)
                                    //     echo "</b>";

                                    // if ($sideDate->format('d') >= $date->format('d') && $moje == -1) {
                                    //     if ($dane[0] == 'u') {
                                    //         // CZY JUZ NIE ROZWIAZAL?
                                    //         echo    '<form method="post" action="test.php" target="_blank">
                                    //                         <input type="hidden" value="'.$przedmiot[1].'" name="nazwaPrzedmiotu">
                                    //                         <input type="hidden" value="'.$row[0].'" name="testId">
                                    //                         <input type="submit" value="Rozwiąż" class="submitButton">
                                    //                     </form>';
                                    //     } else {// PODGLĄD DLA NAUCZYCIELA
                                    //         echo    '<form method="post">
                                    //                         <input type="hidden" value="'.$row[0].'" name="usun">
                                    //                         <input type="submit" value="Usuń" class="submitButton">
                                    //                     </form>';
                                    //     }
                                    // }
                                }
                                $conn->close();

                        ?>
                    </form>

                </div>
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