<?php
	session_start();
	if(!isset($_SESSION['zalogowany']))
	{
		header('Location: logowanie.php');
		exit();
	}
	
	$anchor = "";
	if(isset($_POST['klucz']))
	{
		$zdanie = $_POST['zdanie'];
		$klucz = $_POST['klucz'];
		
		if(strpos($zdanie, "#") !== false)
		{
			if($klucz != "")
			{
				require_once "dbconnect.php";
				$conn = new mysqli($host, $user, $pass, $db);
				
				$result = $conn->query("SELECT tresc, klucz FROM zadania WHERE przedmiot_id = 12 AND typ = 1");
				$juzJest = false;
				while($row = $result->fetch_assoc())
				{
					if($row['tresc'] == $zdanie && $row['klucz'] == $klucz)
						$juzJest = true;
				}
				
				if($juzJest == false)
				{
					$conn->query("INSERT INTO zadania VALUES(NULL,12,1,'$zdanie','$klucz','')");
				}
				else
				{
					$_SESSION['er_dodaj1'] = "Takie  zadanie już istnieje.";
					$anchor = "er1";
				}
				
				$conn->close();
			}
			else
			{
				$_SESSION['er_dodaj1'] = "Wypełnij oba pola";
				$anchor = "er1";
			}
		}
		else
		{
			$_SESSION['er_dodaj1'] = "W zdaniu musi pojawić się znak #";
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
	
	<?php $active = "powiadomienia"; require "header.php";	?>
	<main>
		<div class="container-fluid">
			<div class="row">
				
				<div class="col-sm-8 col-lg-7 my-3 mx-auto bg-secondary text-center text-light">
					<h3>Powiadomienia</h3>
<?php
	if(isset($_POST['klucz']) && !isset($_SESSION['er_dodaj1']))
	{
		echo "<h3 class='text-success font-weight-bold'>Zadanie zostało dodane pomyślnie!</h3><br>";
	}
?>					
						
				
					<button onclick="rozwin('d1')" class="dropdown-toggle w-75 btn-secondary">Dodaj nowe powiadomienie</button><br>
					<div id="d1" class="rozwin">
						<br>
						<form action="powiadomienia.php" method="post">
							
							<label>Wybierz klasę
							<select name="klasa">
							
<?php
	require_once "dbconnect.php";		
	$conn =  new mysqli($host, $user, $pass, $db);
	$result = $conn->query("SELECT * FROM klasy");
	while($row = $result->fetch_row())
	{
		echo "<option value='$row[0]'>$row[1]</option>";
	}
	$conn->close();	
?>
							</select>
							</label>
						<br>
							
							<label>Wybierz przedmiot
							<select name="przedmiot">
							
<?php
	require_once "dbconnect.php";		
	$conn =  new mysqli($host, $user, $pass, $db);	
	$dane = explode(",", $_SESSION['zalogowany']);
	$result = $conn->query("SELECT * FROM nauczyciele WHERE id = '$dane[1]'");
	$row = $result->fetch_assoc();
	$id = $row['id'];
	$result = $conn->query("SELECT przedmioty.id, nazwa FROM przedmioty, zajecia WHERE przedmioty.id = zajecia.przedmiot_id AND nauczyciel_id = '$id'");
	while($row = $result->fetch_row())
	{
		echo "<option value='$row[0]'>$row[1]</option>";
		
	}
	$conn->close();	
		
?>
							</select>
							</label>
							<br>
							Napisz wiadomość<br>
							<textarea name="tekst" rows="6" cols="80"></textarea><br>
							<input type="submit" value="Wyślij">
						</form>
						<br>
<?php
	if(isset($_SESSION['er_dodaj1']))
	{
		echo "<div class='error'>".$_SESSION['er_dodaj1']."</div><br>";
		unset($_SESSION['er_dodaj1']);
	}
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