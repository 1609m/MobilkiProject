<?php
	session_start();
    if (isset($_SESSION['zalogowany'])) {
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
	<script src="https://www.google.com/recaptcha/api.js" async defer></script>
	<script>
		function toggle()
		{
			var tryb = document.getElementById('naucz');
			
			if(tryb.checked == true)
			{
				$('#klasa').addClass('d-none');
				$('#przedmioty').removeClass('d-none');
				$('#ludzik').addClass('d-none');
				$('#ludzik2').removeClass('d-none');
			}
			else
			{
				$('#klasa').removeClass('d-none');
				$('#przedmioty').addClass('d-none');
				$('#ludzik').removeClass('d-none');
				$('#ludzik2').addClass('d-none');
			}
		}
		
		
	</script>
	
	
	
</head>

<body>
	
	<?php $active = "logowanie"; require "header.php";	?>
	
	<main>
		<div class="container">
			<div class="row">
				
				<div class="col-9 col-lg-5 bg-secondary my-5 mx-auto text-light text-center">
					
					<br><h3>Logowanie</h3>
					<br>
					
					<form method="post" action="zaloguj.php">
						
						
						<label class="text-left">Login:<br><input type="text" name="log"></label><br>
						<label class="text-left">Hasło:<br><input type="password" name="pass"></label><br>
<?php
	if(isset($_SESSION['er_logowanie']))
	{
		echo '<div class="error">'.$_SESSION['er_logowanie'].'</div>';
		unset($_SESSION['er_logowanie']);
	}
?>
						<br>
						<input type="submit" value="Zaloguj" class="submitButton"><br><br>
						<div class="obrazek d-none d-lg-block">
							<br><br><br>
							<img src="img/ludzik.png" alt="zainteresowany ludzik" id="ludzik">
							<img src="img/ludzik2.png" alt="zainteresowany ludzik" id="ludzik2" class="d-none">
						</div>
						
					</form>
					
					
					
				</div>
				
				<div class="col-9 col-lg-5 bg-secondary my-5 mx-auto text-light text-center">
					
					<br><h3>Nie masz jeszcze konta?</h3>
					<br><h3>Zarejestruj się już dziś!</h3>
					<h5>I kontynuuj nauczanie z bezpiecznej odległości!</h5>
					<br>
					
					<form method="post" action="rejestruj.php">
						
						<h6>Status:</h6>
						<label><input type="radio" name="status" value="nauczyciel" onclick="toggle()" id="naucz"> Nauczyciel</label>
						<label><input type="radio" name="status" value="uczen" onclick="toggle()" checked> Uczeń</label>
						<br>
						
						<div class="formularz text-left">
						
						<label>Imię:<br><input type="text" name="imie" id="f_imie"></label><br>
<?php
	if(isset($_SESSION['er_imie']))
	{
		echo "</div>";
		echo '<div class="error">'.$_SESSION['er_imie'].'</div>';
		unset($_SESSION['er_imie']);
		echo"<br><div class='formularz text-left'>";
	}
?>
						
						<label>Nazwisko:<br><input type="text" name="nazwisko" id="f_nazwisko"></label><br>
<?php
	if(isset($_SESSION['er_nazwisko']))
	{
		echo "</div>";
		echo '<div class="error">'.$_SESSION['er_nazwisko'].'</div>';
		unset($_SESSION['er_nazwisko']);
		echo"<br><div class='formularz text-left'>";
	}
?>
						
						<label id="klasa"><br>Klasa: 
						<select name="klasa">
<?php
	require_once "dbconnect.php";
	$conn = new mysqli($host, $user, $pass, $db);
	
	$result = $conn->query("SELECT * FROM klasy");
	
	while($row = $result->fetch_row())
	{
		echo "<option value='$row[0]'>$row[1]</option>";
	}
?>
						</select><br><br></label>
						
						<label>Login:<br><input type="text" name="login" id="f_login"></label><br>
<?php
	if(isset($_SESSION['er_login']))
	{
		echo "</div>";
		echo '<div class="error">'.$_SESSION['er_login'].'</div>';
		unset($_SESSION['er_login']);
		echo "<br><div class='formularz text-left'>";
	}
?>
						
						<label>Hasło:<br><input type="password" name="haslo"></label><br>
<?php
	if(isset($_SESSION['er_haslo']))
	{
		echo "</div>";
		echo '<div class="error">'.$_SESSION['er_haslo'].'</div>';
		unset($_SESSION['er_haslo']);
		echo"<br><div class='formularz text-left'>";
	}
?>
						
						<label>Powtórz hasło:<br><input type="password" name="haslo2"></label><br>
						
						<span id="przedmioty" class="d-none">
						<label><br>Prowadzone przedmioty:</label><br>
<?php
	$result = $conn->query("SELECT * FROM przedmioty");
	while($row = mysqli_fetch_row($result))
	{
		$name = "p".$row[0];
		echo "<label><input type='checkbox' name='$name'> $row[1]</label><br>";
	}
	
	$conn->close();
?>
						<label>Inne przedmioty:<br><input type="text" name="przedmioty" placeholder="oddzielaj przecinkiem"></label><br>
						</span>
						</div>
						
						<br><br>
						<div class="g-recaptcha d-inline-block" data-sitekey="6Ld2K_gUAAAAAOPhA5ycglktUGbd2NloMVYpfLMx"></div><br>
<?php
	if(isset($_SESSION['er_captcha']))
	{
		echo '<div class="error">'.$_SESSION['er_captcha'].'</div><br>';
		unset($_SESSION['er_captcha']);
	}
?>
						
						<label><input type="checkbox" name="regulamin" id="f_reg"> Akceptuję regulamin</label><br>
<?php
	if(isset($_SESSION['er_reg']))
	{
		echo '<div class="error">'.$_SESSION['er_reg'].'</div><br>';
		unset($_SESSION['er_reg']);
	}
?>
						
						<input type="submit" value="Zarejestruj się" class="submitButton"><br><br>
						
						
						
						
					</form>
					
					
					
				</div>
				
				
				
			</div>
			
		</div>
		
	</main>
	
	
	
	
	
	<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
	
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
	
	<script src="js/bootstrap.min.js"></script>

	<script type="text/javascript">

		var imie = <?php if(isset($_SESSION['f_imie'])) echo json_encode($_SESSION['f_imie']); else echo '""'; unset($_SESSION['f_imie']) ?>;
		var nazwisko = <?php if(isset($_SESSION['f_nazwisko'])) echo json_encode($_SESSION['f_nazwisko']); else echo '""'; unset($_SESSION['f_nazwisko']) ?>;
		var login = <?php if(isset($_SESSION['f_login'])) echo json_encode($_SESSION['f_login']); else echo '""'; unset($_SESSION['f_login']) ?>;
		var reg = <?php if( isset($_SESSION['f_reg']) ) echo 'true'; else echo 'false'; unset($_SESSION['f_reg']) ?>;
		

		$('#f_imie').attr('value', imie);
		$('#f_nazwisko').attr('value', nazwisko);
		$('#f_login').attr('value', login);
		if(reg == true)
			$('#f_reg').attr('checked', 'checked');
		
	</script>
</body>
</html>