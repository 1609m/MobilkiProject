<?php
	if (session_status() == PHP_SESSION_NONE)
		session_start();
    if (!isset($_SESSION['zalogowany'])) {
		header('Location: logowanie.php');
		exit();
    }
    if (isset($_SESSION['wroc'])) {
        unset($_SESSION["wroc"]);
        if (isset($_POST['Mat'])) {
            header('Location: zadanieMatU.php');
        } else if (isset($_POST['Ang'])) {
            header('Location: zadanieAngU.php');
        } else {
            header('Location: terminarz.php');
        }	
        unset($_POST['Mat']);
		header('Location: terminarz.php');
		exit();
	}
    
    if (isset($_POST['zakoncz'])) {
        $i = 1;
        $wynik = 0;
        $podWynik = array("plus" => 0, "minus" => 0);
        $ile = array("plus" => 0, "minus" => 0, "wszystkie" => 0);
        // ANGIELSKI
        while (isset($_SESSION['odp'][$i])) {
            $odpowiedzi = explode(",", $_SESSION['odp'][$i]);
            
            if ($odpowiedzi[0] == 0) { // zerowanie typu 2
                $plus = $ile["plus"] > 0 ? $podWynik["plus"] / $ile["plus"] : 0;
                $minus = $ile["minus"] > 0 ? $podWynik["minus"] / $ile["minus"] : 0;
                $wynik += $plus - $minus > 0 ? $plus - $minus : 0;
                $podWynik["plus"] = 0;
                $podWynik["minus"] = 0;
                $ile["plus"] = 0;
                $ile["minus"] = 0;
                $ile["wszystkie"]++;
            } else if ($odpowiedzi[0] == 1) { // typ 1
                if ($odpowiedzi[1] == $_POST["$i"]) {
                    $wynik++;
                }
                $ile["wszystkie"]++;
            } else if ($odpowiedzi[0] == 2) { // typ 2
                if (isset($_POST["$odpowiedzi[1]"])) {
                    $gdzie = "minus";
                    if ($odpowiedzi[2] == "1") {
                        $gdzie = "plus";
                    }
                    $podWynik["$gdzie"]++;
                    $ile["$gdzie"]++;
                } else {
                    $gdzie = "minus";
                    if ($odpowiedzi[2] == "1") {
                        $gdzie = "plus";
                    }
                    $ile["$gdzie"]++;
                }
            }
            unset($_SESSION['odp'][$i]);
            $i++;
        }

        if ($ile["wszystkie"] > 0) {
            $wynik = $wynik / $ile["wszystkie"] * 100;
            
            require_once "dbconnect.php";
            $conn = new mysqli($host, $user, $pass, $db);
            $dane = explode(',', $_SESSION['zalogowany'], 3);
            $paczkazad_id = $_SESSION['odp'][0];
            $conn->query("INSERT INTO wyniki VALUES(NULL, '$dane[1]', '$paczkazad_id', '$wynik')");
            $conn->close();
            $_SESSION['pokazWynik'] = '';
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
	
	
</head>

<body>
	
    <header>
		
		<nav class="navbar navbar-dark">
			
			<a href="index.php" class="navbar-brand">
				<img src="img/logo4t.png" width="90" height="90" class="d-inline-block align-middle" alt="Szkoła z WWW">
			</a>
			
			<div class="mr-auto">
				
				<a href="index.php" class="navbar-brand" target="_blank"><h2 class="ml-sm-4">Szkoła z <span class="www">W</span><span class="www">W</span><span class="www">W</span></h2></a>
				
			</div>
			
		</nav>
		
	</header>
	<main>
		    <div class="container-fluid">
			    <div class="row">
				    <div class=" col-sm-8 my-3 mx-auto p-4 d-inline-block bg-secondary text-center text-light" id="main">

                        <h2>Test</h2>
                        
<?php

    if (isset($_SESSION['pokazWynik'])) {
        $dane = explode(',', $_SESSION['zalogowany'], 3);
        $dane = $dane[1];
        $conn = new mysqli($host, $user, $pass, $db);
        $result = $conn->query("SELECT wynik FROM wyniki WHERE uczen_id='$dane'");
        $srednia = 0;
        while ($row = $result->fetch_row()) {
            $srednia += $row[0];
        }
        $srednia /= $result->num_rows;
        if (isset($_POST['Mat'])) {
            echo "<br /><h3>Twój wynik: ".$wynik."%</h3><h4>Średnia twoich ocen: ".$srednia."%</h4><br /><a href='zadanieMatU.php' class='text-warning'>Powrót do Matematyki</a>";
        } else if (isset($_POST['Ang'])) {
            echo "<br /><h3>Twój wynik: ".$wynik."%</h3><h4>Średnia twoich ocen: ".$srednia."%</h4><br /><a href='zadanieAngU.php' class='text-warning'>Powrót do J. Angielskiego</a>";
        } else {
            echo "<br /><h3>Twój wynik: ".$wynik."%</h3><h4>Średnia twoich ocen: ".$srednia."%</h4><br /><a href='terminarz.php' class='text-warning'>Powrót do terminarza</a>";
        }
        unset($_SESSION['pokazWynik']);
        $conn->close();
        $_SESSION["wroc"] = '';
    } else if (isset($_POST['testId'])) {
        $testId = $_POST['testId'];
        echo "<h3>Przedmiot: ".$_POST['nazwaPrzedmiotu']."</h3><br /><form method='post'>";
        require_once "dbconnect.php";
        $conn = new mysqli($host, $user, $pass, $db);
        $result = $conn->query("SELECT zadania_id FROM paczkap WHERE paczkazad_id='$testId'");
        $i = 0;
        $_SESSION['odp'] = array($_POST['testId']);
        while ($row = $result->fetch_row()) {
            $zad = $conn->query("SELECT * FROM zadania WHERE id='$row[0]'")->fetch_assoc();
            
            if ($zad['przedmiot_id'] == 12 || 'innyPrzedmiot' == 'innyPrzedmiot') {
                if ($zad['typ'] == 1) {
                    $zdanie = explode("#", $zad['tresc'], 2);
                    echo "<b>".++$i.". Uzupełnij lukę:</b><br /> ".$zdanie[0];
                    
                    if ($zad['przedmiot_id'] == 12) {
                        echo "<input type='text' size='10' name='".$i."' style='text-align: center;'>".$zdanie[1]."<br /><br />";
                    } else if ($zad['przedmiot_id'] == 1) {
                        echo "<br /><input type='text' size='10' name='".$i."' style='text-align: center;'><br /><br />";
                    }
                    $tmp = "1,".$zad['klucz'];
                    array_push($_SESSION['odp'], $tmp);
                } else if ($zad['typ'] == 2) {
                    echo "<b>".++$i.". ".$zad['tresc']."</b><br />";
                    
                    $id = $zad['id'];
                    $odp = $conn->query("SELECT * FROM zadaniaabc WHERE zadania_id='$id'");
                    while ($odpRow = $odp->fetch_assoc()) {
                        echo "<label><input type='checkbox' name='".$odpRow['id']."'> ".$odpRow['pytanie']."</label><br />";
                        $tmp = "2,".$odpRow['id'].",".$odpRow['TF'];
                        array_push($_SESSION['odp'], $tmp);
                    }
                    array_push($_SESSION['odp'], "0");
                    echo "<br />";
                }
            }
        }
        if (isset($_POST['Ang'])) {
            $testAng = $_POST['Ang'];
            echo "<input type='hidden' value='$testAng' name='Ang'>";
        } else if (isset($_POST['Mat'])) {
            $testMat = $_POST['Mat'];
            echo "<input type='hidden' value='$testMat' name='Mat'>";
        }
        echo "<input type='submit' value='Zakończ test!' name='zakoncz' class='submitButton'></form>";
        $conn->close();
    } else {
        $_SESSION["wroc"] = '';
    }

?>
                    </div>
                </div>
            </div>
        

	</main>
	
	
	
	<script type='text/javascript'>

        window.onbeforeunload = function() {
               return "0 pkt";
        };

    </script>

	<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>

	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>

	<script src="js/bootstrap.min.js"></script>
</body>
</html>