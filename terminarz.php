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
    <script type="text/javascript">
    
        function tog() {
            console.log('!');
            if($('#aside').hasClass('d-none')) {
                // $('#main').removeClass('mx-auto');
                // $('#main').addClass('d-inline-block');
                $('#aside').removeClass('d-none');
                $('#aside').addClass('d-inline-block');
            } else {
                $('#aside').addClass('d-none');
                $('#aside').removeClass('d-inline-block');
            }
        }

    </script>
	    <main>
		    <div class="container-fluid">
			    <div class="row">
				    <div class="col-sm-5 col-md-7 col-lg-8 my-3 mx-auto d-inline-block bg-secondary text-center text-light" id="main">

                        <h2>Terminarz</h2><br>
                        <a href='terminarz.php?m=0' class='text-warning'>Wróć do dzisiaj</a>
                        <button onclick="tog()">Click me</button> 

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

    echo "<div class='row mx-auto display-inline-block pl-lg-5'>";
    for($i = 0; $i < 6; $i++) {
        for($j = 1; $j <= 7; $j++) {

            echo "<div class='col-md-1 ml-sm-3 m-sm-1 m-lg-3 card";
            $id = $i*7 + $j - $offset;
            if($id <= $date->format('t') && $id > 0) {
                if($id == $date->format('j') && $date == new DateTime())
                    echo " bg-white text-dark'";
                else
                    echo " bg-dark'";
                echo " id='".$id."'><h4><a href='terminarz.php?m=".$month."&d=".$id."'>".$id."</a></h4></div>";
            } else
                echo " bg-secondary'></div>";

        }
        echo "<div class='w-100'></div>";
    }


?>
                    </div>
                </div>
                
                <div class="col-6 col-md-3 my-3 mx-auto bg-secondary text-center text-light d-none" id="aside">
                    <!-- TODO: BOCZNY PANEL -->
                    tralala
                </div>

			</div>
		</div>
	</main>
	
	<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
	
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
	
	<script src="js/bootstrap.min.js"></script>
	
</body>
</html>