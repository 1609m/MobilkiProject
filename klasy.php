<?php
	if (session_status() == PHP_SESSION_NONE)
	{
		session_start();
	}
	if(!isset($_SESSION['zalogowany']))
	{
		header('Location: index.php');
		exit();
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
	
	<?php $active = "klasy"; require "header.php";	?>
	<main>
		<div class="container-fluid">
			<div class="row">
				
				<div class="col-sm-8 my-3 mx-auto bg-secondary text-center text-light">
					<h2>Klasy</h2>
					
<?php
	
	require_once "dbconnect.php";		
	$conn =  new mysqli($host, $user, $pass, $db);
	
	if(isset($_POST['nowaKlasa']))
	{
		$klasa = $_POST['nowaKlasa'];
		$result = $conn->query("SELECT * FROM klasy");
		$dodKlasy = true;
		$error = "";

		while($row = $result->fetch_row())
		{
			if ($row[1] == $klasa) {
				$dodKlasy = false;
				$error = "Klasa o nazwie ".$klasa." już istnieje!";
			}
				
		}

		if (ctype_alnum($klasa) == false) {
			$dodKlasy = false;
			$error = "Nazwa klasy musi składać się ze znaków alfanumerycznych!";
		}
		
		if (strlen($klasa) < 2) {
			$dodKlasy = false;
			$error = "Nazwa klasy musi składać się przynajmniej z 2 znaków";
		}

		if ($dodKlasy == true) {
			$conn->query("INSERT INTO klasy VALUES(NULL, '$klasa')");
			echo "<h3 class='text-success font-weight-bold'>Klasa ".$klasa." została dodana pomyślnie!</h3><br>";
		} else {
			echo "<div class='error'>$error</div><br>";
		}
			
	}
	
?>

					<form method="post" action="klasy.php">
						<label>Wybierz klasę:
						<select name="klasa">
						
<?php
	
	$result = $conn->query("SELECT * FROM klasy");
	while($row = $result->fetch_row())
	{
		echo "<option value='$row[0]'>$row[1]</option>";
	}
	
echo<<<END
		</select>
		</label>
		<input type="submit" value="Wyświetl uczniów" class="submitButton">
	</form>
END;

	if(isset($_POST["klasa"]))
	{
		$klasa = $_POST["klasa"];
		
		$result = $conn->query("SELECT nazwa FROM klasy WHERE id = '$klasa'");
		$row = $result->fetch_row();
		echo "<b><h3>".$row[0]."</h3></b>";
		
		$result = $conn->query("SELECT * FROM uczniowie WHERE klasa_id='$klasa'");
		
echo<<<END
	<table id="tabelaKlas">
		<thead>
			<tr>
				<th>Lp</th><th>Imię</th> <th>Nazwisko</th> <th>Średnia wyników</th>
			</tr>
		</thead>
		<tbody>
END;
		for($i = 1; $row = $result->fetch_assoc(); $i++) 
		{
            $id = $row['id'];
            $resultSrednia = $conn->query("SELECT wynik FROM wyniki WHERE uczen_id='$id'");
            $srednia = 0;
            while ($rowSrednia = $resultSrednia->fetch_row()) {
                $srednia += $rowSrednia[0];
            }
            $srednia = $resultSrednia->num_rows > 0 ? $srednia / $resultSrednia->num_rows : "Brak";
			echo "<tr><td>$i</td><td>".$row['imie']."</td><td>".$row['nazwisko']."</td><td>".$srednia."</td></tr>";
		}
		echo "</tbody></table>";
		
	}
	
echo<<<END
	<br>
	<form action="klasy.php" method="post">
		
		<label>Dodaj nową klasę: <input type="text" name="nowaKlasa" size="10" placeholder=" nazwa klasy"></label>
		<input type="submit" value="Dodaj!" class="submitButton">
		
	</form>
	
END;
	
	$conn->close();
	
?>
					<br>
					
					
				</div>
			</div>
		</div>
	</main>
	
	
	
	
	
	<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
	
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
	
	<script src="js/bootstrap.min.js"></script>
</body>
</html>