<?php 
	class DbConnect {
		private $host = 'localhost';
		private $dbName = 'szkola_z_www';
		private $user = 'root';
		private $pass = '';

		public function connect() {
			try {
				$conn = new PDO('mysql:host=' . $this->host . '; dbname=' . $this->dbName, $this->user, $this->pass);
				$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				return $conn;
			} catch( PDOException $e) {
				echo 'Database Error: ' . $e->getMessage();
			}
		}
	}

	if(isset($_POST['aid'])) {
		$db = new DbConnect;
		$conn = $db->connect();

		$stmt = $conn->prepare("SELECT * FROM uczniowie WHERE klasa_id = " . $_POST['aid']);
		$stmt->execute();
		$uczniowie = $stmt->fetchAll(PDO::FETCH_ASSOC);
		echo json_encode($uczniowie);
	}

	if (isset($_POST['prz'])) {
		$db = new DbConnect;
		$conn = $db->connect();

		$stmt = $conn->prepare("SELECT nazwa FROM przedmioty, zajecia WHERE przedmioty.id = zajecia.przedmiot_id AND zajecia.nauczyciel_id =" .$_POST['prz']);
		$stmt->execute();
		$przedmioty = $stmt->fetchAll(PDO::FETCH_ASSOC);
		echo json_encode($przedmioty);
		
	}

	function loadKlasy() {
		$db = new DbConnect;
		$conn = $db->connect();

		$stmt = $conn->prepare("SELECT * FROM klasy");
		$stmt->execute();
		$klasy = $stmt->fetchAll(PDO::FETCH_ASSOC);
		return $klasy;
	}

	function loadNauczyciele() {
		$db = new DbConnect;
		$conn = $db->connect();

		$stmt = $conn->prepare("SELECT * FROM nauczyciele");
		$stmt->execute();
		$nauczyciele = $stmt->fetchAll(PDO::FETCH_ASSOC);
		return $nauczyciele;
	}

 ?>