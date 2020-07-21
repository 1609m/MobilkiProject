<?php
	require_once "dbconnect.php";
	$conn = new mysqli($host, $user, $pass, $db);
	$conn->query("DELETE FROM odczytane WHERE odcz = 1");
	
	//uczen_id = '$dane[1]'

	$conn->close();
	session_start();
	session_unset();
	$_SESSION['logout'] = true;
	header('Location: index.php');
?>