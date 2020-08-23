<?php
	session_start();
	if (!isset($_SESSION['zalogowany'])) {
		header('Location: logowanie.php');
		exit();
	}
	if (isset($_POST['powrot'])) {
		header('Location: index.php');
		exit();
	}
    
    function colorCards($termin) {
        require "dbconnect.php";
        $conn = new mysqli($host, $user, $pass, $db);
        $result = $conn->query("SELECT COUNT(id) FROM paczkazad WHERE termin='$termin'");
        if (($result->fetch_row())[0] > 0)
            echo " text-warning";
        $conn->close();
    }
    
    if (isset($_POST['usun'])) {
        $id = $_POST['usun'];
        require_once "dbconnect.php";
        $conn = new mysqli($host, $user, $pass, $db);
        $conn->query("DELETE FROM paczkazad WHERE id='$id'");
        $conn->query("DELETE FROM paczkap WHERE paczkazad_id='$id'");
        $conn->close();
        $_SESSION['usun'] = "letMeTellTheUserThatEverythingIsOK";
    }


?>
<!DOCTYPE html>
<html>
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

    <?php $active = "terminarz"; require "header.php";	?>

	    <main>
		    <div class="container-fluid">
			    <div class="row">
				    <div class=" col-sm-8 my-3 mx-auto p-4 d-inline-block bg-secondary text-center text-light" id="main">

                        <h2>Terminarz</h2><br>
                        <a href='terminarz.php?m=0' class='text-warning'>Wróć do dzisiaj</a>

<?php
    $date = new DateTime();

    // USTAW MIESIĄC
    $month = 0;
    if (isset($_GET['m'])) {
        $month = $_GET['m'];
    }
    if ($month > 0) {
        for ($i = 0; $i < $month; $i++) {
            $date->modify('+1 month');
        }
    } else {
        for ($i = 0; $i > $month; $i--) {
            $date->modify('-1 month');
        }
    }

    $monthName = ['Styczeń', 'Luty', 'Marzec', 'Kwiecień', 'Maj', 'Czerwiec', 'Lipiec', 'Sierpień', 'Wrzesień', 'Październik', 'Listopad', 'Grudzień'];

    // WYSWIETL MIESIAC
    echo "<form method='post'><h2>
		<a href='terminarz.php?m=".($month-1)."' class='text-warning'><</a>
        <b style='display: inline-block; width: 75%;'>".$monthName[$date->format('n') - 1]." ".$date->format('Y')."</b>
        <a href='terminarz.php?m=".($month+1)."' class='text-warning'>></a></h2></form><br>";

    $offset = ($date->format('N') - $date->format('j')%7 + 7)%7;
    echo "<div class='row mx-md-auto display-inline-block pl-lg-5'>";
    for($i = 0; $i < 6; $i++) {
        for($j = 1; $j <= 7; $j++) {
            echo "<div class='col-sm-1 ml-md-3 m-sm-1 m-lg-3 card";
            $id = $i*7 + $j - $offset;
            if($id <= $date->format('t') && $id > 0) {
                if($id == $date->format('j') && $date->format('j.m.Y') == (new DateTime())->format('j.m.Y')) {
                    echo " bg-white text-warning";
                } else {
                    echo " bg-dark";
                    if ($id%7 + $offset == 6 || $id%7 + $offset == 7)
                        echo " text-secondary";
                }
                $day = $id;
                $mon = $date->format('m');
                $year = $date->format('Y');
                $termin = new DateTime("$day.$mon.$year");
                colorCards($termin->format('Y.m.d'));
                echo "' id='".$id."'><h4><a href='terminarz.php?m=".$month."&d=".$id."' style='color: inherit;'>".$id."</a></h4></div>";
            } else
                echo " bg-secondary'></div>";
        }
        echo "<div class='w-100'></div>";
    }


?>
                    </div>
                </div>
                
                <div class="col-6 col-md-3 my-3 mx-auto bg-secondary text-center text-light d-none" id="aside">
                    <h3 class="text-right"><a href="terminarz.php<?php echo "?m=".$month; ?>" class='text-warning'>X</a></h3>
<?php

    if (isset($_GET['d'])) {
        if (isset($_SESSION['usun'])) {
            echo "<h4 class='text-success font-weight-bold'>Test usunięty!</h4>";
            unset($_SESSION['usun']);
        }
        
        $day = $_GET['d'];
        $mon = $date->format('m');
        $year = $date->format('Y');
        $sideDate = new datetime("$day.$mon.$year");
        echo "<h4>".$sideDate->format('d.m.Y')."</h4><hr style='height: 5px; background: #373a3d; border: 0px;'>";

        require "dbconnect.php";
        $conn = new mysqli($host, $user, $pass, $db);
        $sideDateFormat = $sideDate->format('Y.m.d');
        $result = $conn->query("SELECT * FROM paczkazad WHERE termin='$sideDateFormat'");
        $i = 0;
        $napisTesty = "";
        while($row = $result->fetch_row())
        {
            if ($napisTesty == "") {
                echo "<h4>Testy:</h4>";
                $napisTesty = "Testy";
            }
            $resultKl = $conn->query("SELECT * FROM klasy WHERE id=$row[1]");
            $resultKl = $resultKl->fetch_assoc();

            $zad = $conn->query("SELECT zadania_id FROM paczkap WHERE paczkazad_id=$row[0] LIMIT 1");
            $zad = $zad->fetch_row();
            $przedmiotId = $conn->query("SELECT przedmiot_id FROM zadania WHERE id=$zad[0]");
            $przedmiotId = ($przedmiotId->fetch_row())[0];
            $przedmiot = $conn->query("SELECT * FROM przedmioty WHERE id=$przedmiotId");
            $przedmiot = $przedmiot->fetch_row();
            
            $resultZad = $conn->query("SELECT COUNT(id) FROM paczkap WHERE paczkazad_id=$row[0]");
            
            $moje = 0;;
            $dane = explode(',', $_SESSION['zalogowany'], 3);
            if ($dane[0] == "n") {
                $moje = $conn->query("SELECT COUNT(id) FROM zajecia WHERE nauczyciel_id='$dane[1]' AND przedmiot_id='$przedmiot[0]'");
                $moje = ($moje->fetch_row())[0];
                if ($moje > 0) {
                    echo "<b>";
                    $moje = -1;
                }
            } else {
                $moje = $conn->query("SELECT klasa_id FROM uczniowie WHERE id='$dane[1]'");
                $moje = ($moje->fetch_row())[0];
                if ($moje == $resultKl['id']) {
                    echo "<b>";
                    $moje = -1;
                }
            }
            echo "<h5 class='text-left'>".++$i.".</h5>Klasa: ".$resultKl['nazwa']."<br />";

            echo "Przedmiot: ".$przedmiot[1]."<br />";

            echo "Ilość zadań: ".($resultZad->fetch_row())[0]."<br />";

            if ($moje == -1)
                echo "</b>";

            if ($sideDate->format('d') >= $date->format('d') && $moje == -1) {
                if ($dane[0] == 'u') {
                    // CZY JUZ NIE ROZWIAZAL?
                    echo    '<form method="post" action="test.php" target="_blank">
                                    <input type="hidden" value="'.$przedmiot[1].'" name="nazwaPrzedmiotu">
                                    <input type="hidden" value="'.$row[0].'" name="testId">
                                    <input type="submit" value="Rozwiąż" class="submitButton">
                                </form>';
                } else {// PODGLĄD DLA NAUCZYCIELA
                    echo    '<form method="post">
                                    <input type="hidden" value="'.$row[0].'" name="usun">
                                    <input type="submit" value="Usuń" class="submitButton">
                                </form>';
                }
            }
        }
        $conn->close();
    }

?>

                </div>

			</div>
		</div>
	</main>

    <script type="text/javascript">

        function tog(aside) {
            if (aside != "0") {
                $('#aside').removeClass('d-none');
                $('#aside').addClass('d-inline-block');
                $('#main').removeClass('col-sm-8');
                $('#main').addClass('col-sm-5').addClass('col-md-8');
                $('.card').addClass('col-md-1');
                $('.card').removeClass('col-sm-1');
            } else {
                console.log('else');
                $('#aside').addClass('d-none');
                $('#aside').removeClass('d-inline-block');
                $('#main').addClass('col-sm-8');
                $('#main').removeClass('col-sm-5').addClass('col-md-8');
                $('.card').removeClass('col-md-1');
                $('.card').addClass('col-sm-1');
            }
        }

        let aside = <?php if (isset($_GET['d'])) {echo $_GET['d'];} else {echo "0";} ?>;
        tog(aside.toString());
        
        

    </script>
	
	<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
	
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
	
	<script src="js/bootstrap.min.js"></script>
	
</body>
</html>