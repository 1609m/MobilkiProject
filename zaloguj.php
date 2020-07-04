<?php
	session_start();
	
	if(isset($_POST['log']))
	{
		require_once "dbconnect.php";
		$conn = new mysqli($host, $user, $pass, $db);
		
		$login = $_POST['log'];
		$haslo = $_POST['pass'];
		$login = htmlentities($login, ENT_QUOTES, "UTF-8");
		
		if($result = $conn->query(sprintf("SELECT * FROM nauczyciele WHERE login = '%s'", mysqli_real_escape_string($conn, $login)) ))
		{
			$ile_loginow = $result->num_rows;
			if($ile_loginow > 0)
			{
				$row = $result->fetch_assoc();
				if(password_verify($haslo, $row['haslo']))
				{
					$_SESSION['zalogowany'] = "n,".$row['id'].",".$row['imie'];
					header('Location: index.php');
					exit();
				}
			}
			else if($result = $conn->query(sprintf("SELECT * FROM uczniowie WHERE login = '%s'", mysqli_real_escape_string($conn, $login)) ))
			{
				$ile_loginow = $result->num_rows;
				if($ile_loginow > 0)
				{
					$row = $result->fetch_assoc();
					if(password_verify($haslo, $row['haslo']))
					{
						$_SESSION['zalogowany'] = "u,".$row['id'].",".$row['imie'];
						header('Location: index.php');
						exit();
					}
				}
			}
			$_SESSION['er_logowanie'] = "Nieprawidłowy login lub hasło!";
			header('Location: logowanie.php');
		}
		
		$conn->close();
	}
	else
	{
		header('Location: logowanie.php');
	}
?>