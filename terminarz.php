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

  
	<!--<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.4.0/fullcalendar.css" />
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.0.0-alpha.6/css/bootstrap.css" />
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.4.0/fullcalendar.min.js"></script>
  
<script>
   
  $(document).ready(function() {
   var calendar = $('#calendar').fullCalendar({
    editable:true,
    header:{
     left:'prev,next today',
     center:'title',
     right:'month,agendaWeek,agendaDay'
    },
    events: 'load.php',
    selectable:true,
    selectHelper:true,
    select: function(start, end, allDay) {

     var title = prompt("Enter Event Title");
     if(title) {

      var start = $.fullCalendar.formatDate(start, "Y-MM-DD HH:mm:ss");
      var end = $.fullCalendar.formatDate(end, "Y-MM-DD HH:mm:ss");
      $.ajax({
       url:"insert.php",
       type:"POST",
       data:{title:title, start:start, end:end},
       success:function() {
        calendar.fullCalendar('refetchEvents');
        alert("Added Successfully");
       }
      })
     }
    },
    editable:true,
    eventResize:function(event)
    {
     var start = $.fullCalendar.formatDate(event.start, "Y-MM-DD HH:mm:ss");
     var end = $.fullCalendar.formatDate(event.end, "Y-MM-DD HH:mm:ss");
     var title = event.title;
     var id = event.id;
     $.ajax({
      url:"update.php",
      type:"POST",
      data:{title:title, start:start, end:end, id:id},
      success:function(){
       calendar.fullCalendar('refetchEvents');
       alert('Event Update');
      }
     })
    },

    eventDrop:function(event)
    {
     var start = $.fullCalendar.formatDate(event.start, "Y-MM-DD HH:mm:ss");
     var end = $.fullCalendar.formatDate(event.end, "Y-MM-DD HH:mm:ss");
     var title = event.title;
     var id = event.id;
     $.ajax({
      url:"update.php",
      type:"POST",
      data:{title:title, start:start, end:end, id:id},
      success:function()
      {
       calendar.fullCalendar('refetchEvents');
       alert("Event Updated");
      }
     });
    },

    eventClick:function(event)
    {
     if(confirm("Are you sure you want to remove it?"))
     {
      var id = event.id;
      $.ajax({
       url:"delete.php",
       type:"POST",
       data:{id:id},
       success:function()
       {
        calendar.fullCalendar('refetchEvents');
        alert("Event Removed");
       }
      })
     }
    },

   });
  });
   
</script>-->

 </head>
 <body>
 	
  	<!--<br />
	<h3 align="center">Witamy w Terminarzu</h3>
	<form action="terminarz.php" method="post">
		<input type="submit" name="powrot" value="wróć">
	</form>	
	<br />
	<div class="container">
		<div id="calendar"></div>
	</div>-->
	
  <?php $active = "terminarz"; require "header.php";	?>
	<main>
		<div class="container-fluid">
			<div class="row">
				<div class="col-lg-8 my-3 mx-auto bg-secondary text-center text-light">

          <h2>Terminarz</h2><br>
          <a href='terminarz.php?m=0' class='text-warning'>Wróć do dzisiaj</a>

<?php
  $date = new DateTime();
  
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
    <b style='display: inline-block; width: 450px;'>".$monthName[$date->format('n') - 1]." ".$date->format('Y')."</b>
    <a href='terminarz.php?m=".($month+1)."' class='text-warning'>></a></h2></form><br>";

  $offset = ($date->format('N') - $date->format('j')%7 + 7)%7;

  echo "<div class='row mx-auto display-inline-block pl-md-5'>";
  for($i = 0; $i < 6; $i++) {
    for($j = 1; $j <= 7; $j++) {

      echo "<div class='col-1 ml-sm-3 m-sm-3 card";
      $id = $i*7 + $j - $offset;
      if($id <= $date->format('t') && $id > 0) {
        if($id == $date->format('j') && $date == new DateTime())
          echo " bg-white text-dark'";
        else
          echo " bg-dark'";
        echo " id='".$id."'><h4>".$id."</h4></div>";
      }
      else
        echo " bg-secondary'></div>";

    }
    echo "<div class='w-100'></div>";
  }
  
  
  
?>
          </div>
				</div>
			</div>
		</div>
	</main>
	
	<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
	
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
	
	<script src="js/bootstrap.min.js"></script>
	
 </body>
</html>