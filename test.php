<?php
	if (session_status() == PHP_SESSION_NONE)
		session_start();
    if (!isset($_SESSION['zalogowany'])) {
		header('Location: logowanie.php');
		exit();
	}
    /*if (!isset($_POST['testId'])) {
		header('Location: index.php');
		exit();
	}*/
    
    if (isset($_POST['zakoncz'])) {
        $i = 1;
        $wynik = 0;
        // ANGIELSKI
        while (isset($_SESSION['odp'][$i])) {
            echo $i.". ".$_SESSION['odp'][$i]."<br />";
            $odpowiedzi = explode(",", $_SESSION['odp'][$i]);
            if ($odpowiedzi[0] == 1) {
                if ($odpowiedzi[1] == $_POST["$i"])
                    echo "good";
            }
            $i++;
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

    if (isset($_POST['testId'])) {
        $testId = $_POST['testId'];
        echo "<h3>Przedmiot: ".$_POST['nazwaPrzedmiotu']."</h3><br /><form method='post'>";
        
        require_once "dbconnect.php";
        $conn = new mysqli($host, $user, $pass, $db);
        $result = $conn->query("SELECT zadania_id FROM paczkap WHERE paczkazad_id='$testId'");
        $i = 0;
                $_SESSION['odp'] = array("byWyrownacIndeksyZPost");
        while ($row = $result->fetch_row()) {
            $zad = $conn->query("SELECT * FROM zadania WHERE id='$row[0]'")->fetch_assoc();
            
            if ($zad['przedmiot_id'] == 12) {
                if ($zad['typ'] == 1) {
                    $zdanie = explode("#", $zad['tresc'], 2);
                    echo "<b>".++$i.". Uzupełnij lukę:</b><br /> ".$zdanie[0]."<input type='text' size='10' name='".$i."' style='text-align: center;'>".$zdanie[1]."<br /><br />";
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
        echo "<input type='submit' value='Zakończ test!' name='zakoncz' class='submitButton'></form>";
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