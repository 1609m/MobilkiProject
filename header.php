<?php
	if (session_status() == PHP_SESSION_NONE)
		session_start();
?>
	<header>
		
		<nav class="navbar navbar-dark">
			
			<a href="index.php" class="navbar-brand">
				<img src="img/logo4t.png" width="90" height="90" class="d-inline-block align-middle" alt="Szkoła z WWW">
			</a>
			
			<div class="mr-auto">
				
				<a href="index.php" class="navbar-brand"><h2 class="ml-sm-4">Szkoła z <span class="www">W</span><span class="www">W</span><span class="www">W</span></h2></a>
				
				<a href="logowanie.php" class="text-center list-group-item list-group-item-dark list-group-item-action d-none logowanie">
					LOGOWANIE / REJESTRACJA
				</a>
				
				<ul class="navbar-nav navbar-expand-lg text-center">
					
					<li><ul class="navbar-nav navbar-expand-sm list-inline justify-content-center">
						
						<!--  KLASY  -->
						<li class="m-1 list-inline-item"><a href="klasy.php" class="list-group-item list-group-item-dark list-group-item-action klasy d-none">KLASY</a>
						</li>
						
						<!--  ZADANIA  -->
						<li class="m-1 list-inline-item zadania d-none"><div class="dropdown w-100">
							
							<a href="#" class="dropbtn dropdown-toggle list-group-item list-group-item-dark list-group-item-action z_zadane z_doZrobienia z_mat z_matU z_ang z_angU">ZADANIA</a>
							
							<div class="dropdown-content">

								<a href="zadanieMat.php" class="subnav list-group-item list-group-item-dark list-group-item-action z_mat">MATEMETYKA</a>

								<a href="zadanieMatU.php" class="subnav list-group-item list-group-item-dark list-group-item-action z_matU">MATEMETYKA</a>

								<a href="zadanieAng.php" class="subnav list-group-item list-group-item-dark list-group-item-action z_ang">J. ANGIELSKI</a>

								<a href="zadanieAngU.php" class="subnav list-group-item list-group-item-dark list-group-item-action z_angU">J. ANGIELSKI</a>
								
							 </div>
							 
						</div></li>
						
						<!--  TERMINARZ  -->
						<li class="m-1 list-inline-item"><a href="terminarz.php" class="list-group-item list-group-item-dark list-group-item-action terminarz d-none">TERMINARZ</a></li>
						
					</ul></li>
					
					<li><ul class="navbar-nav navbar-expand-sm list-inline justify-content-center">
						
						<!--  POWIADOMIENIA  -->
						<li class="m-1 list-inline-item powiadomienia d-none"><a href="powiadomienia.php" class="list-group-item list-group-item-dark list-group-item-action powiadomienia">POWIADOMIENIA</a></li>
						<li class="m-1 list-inline-item powiadomieniaU d-none"><a href="powiadomieniaU.php" class="list-group-item list-group-item-dark list-group-item-action powiadomieniaU">POWIADOMIENIA</a></li>
						
						<!--  MOJE KONTO  -->
						<li class="m-1 list-inline-item"><a href="mojeKonto.php" class="list-group-item list-group-item-dark list-group-item-action mojeKonto d-none">MOJE KONTO</a></li>
						
						<!--  WYLOGUJ  -->
						<li class="m-1 list-inline-item wyloguj d-none"><a href="wyloguj.php" class="list-group-item list-group-item-dark list-group-item-action">WYLOGUJ</a></li>
						
						
						
					</ul></li>
				</ul>
			</div>
			
		</nav>
		
	</header>
	
	<script src="js/jQuery-min.js"></script>
	
	<script type="text/javascript">
	
		var naucz = <?php if(isset($_SESSION['zalogowany'])) echo json_encode($_SESSION['zalogowany'][0]); else echo '""'; ?>;
		
		if(naucz == "u")
		{
			$('.mojeKonto').removeClass('d-none');
			$('.terminarz').removeClass('d-none');
			$('.zadania').removeClass('d-none');
			$('.z_ang').addClass('d-none');
			$('.z_mat').addClass('d-none');
			$('.powiadomieniaU').removeClass('d-none');
			$('.wyloguj').removeClass('d-none');
		}
		else if(naucz == "n")
		{
			$('.terminarz').removeClass('d-none');
			$('.klasy').removeClass('d-none');
			$('.zadania').removeClass('d-none');
			$('.mojeKonto').removeClass('d-none');
			$('.z_angU').addClass('d-none');
			$('.z_matU').addClass('d-none');
			$('.powiadomienia').removeClass('d-none');
			$('.wyloguj').removeClass('d-none');
		}
		else
		{
			$('.navbar-nav').addClass('d-none');
			$('.logowanie').removeClass('d-none');
		}
		// przywraca przycisk zadania, po usunieciu go przez zadane / doZrobienia
		if ($('.z_ang').hasClass('d-none') && naucz == "n" ) { 
			$('.z_ang').removeClass('d-none');
		} else if ($('.z_angU').hasClass('d-none') && naucz == "u") {
			$('.z_angU').removeClass('d-none');
		}
			

		
		$('.' + '<?php echo $active; ?>').addClass('active');
		
	</script>
	