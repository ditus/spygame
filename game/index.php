<!DOCTYPE html>
<html>
<head>
	<title>Spygame</title>
	<link rel="stylesheet" type="text/css" href="style.css">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<?php
	#Display all errors
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);

	require("../read_players.php");
	require("set_game_status.php");
	require("location_parser.php");
	require("player_status.php");

	$players_file = "../players.txt";
	$player_status = get_player_status($_SERVER['REMOTE_ADDR'],$players_file);

	# Spieler ist eingetragen
	if (!$player_status){
		# Redirect auf die Main Seite mit ?2less=true
		$host  = $_SERVER["HTTP_HOST"];
		$uri   = rtrim(dirname($_SERVER["PHP_SELF"]), "/\\");
		header("Location: http://$host$uri/../?2fast=true");
		exit;
	}
	# mind. 3 Spieler sind bereit für nächste Runde
	if (count_next_players($players_file)<3) {
		# Redirect auf die Main Seite mit ?2less=true
		$host  = $_SERVER["HTTP_HOST"];
		$uri   = rtrim(dirname($_SERVER["PHP_SELF"]), "/\\");
		header("Location: http://$host$uri/../?2less=true");
		exit;
	}
	if ( is_game_running() ){
		if ($player_status==="next"){
			# Redirect auf die Main Seite mit ?2late=true
			$host  = $_SERVER["HTTP_HOST"];
			$uri   = rtrim(dirname($_SERVER["PHP_SELF"]), "/\\");
			header("Location: http://$host$uri/../?2late=true");
			exit;
		}
	} 

	# Start game and change player role
	if (!is_game_running()){
		game_run();
		players_assign_ingame_status($players_file);
		players_assign_location($players_file);
	}
	
	?>
</head>
<body>
<div class="outerwrapper">
<div class="wrapper">
	<div id="main">
		<h3>Ich bin...</h3>
		<?php
		# Ich bin...
		//require("location_parser.php"); 
		$ip = $_SERVER['REMOTE_ADDR'];
		$locations = parse_locations_file("locations.txt");
		$my_location = get_my_player($ip,$players_file)->location;
		$role = get_my_player($ip,$players_file)->role;
		if ($my_location === "spy") {
			echo "<h4>El Spionos</h4>";
		} else {
			echo "<b>Ort</b>: ".$my_location."<br>";
			echo "<b>Rolle</b>: ".$role."<br>";
		}

		# Spieler Auflistung + Beginner
		echo "<h3>".count_players($players_file)." Spieler:</h3>";
		print_player_names($players_file);
		$beginner_name = get_start_player($players_file)->name;
//		echo rand_player_name("../players.txt")." startet die Runde!";
		echo $beginner_name." startet die Runde!";
		?>

		<h3>Unsynchroner provisorisch Timer</h3>
		<div id="countdown"></div>
		<br>

		<form action=".." method="post">
			<input type="submit" name="finito" value="Finito!">
		</form>
		
	</div>
	<div id="locations">
		<?php
		echo "<h2>Orte:</h2>";
		print_locations($locations);
		?>
	</div>
</div>
</div>
<br><br>
<script type="application/javascript">
var timeleft = 60*6;
var downloadTimer = setInterval(function(){
  if(timeleft <= 0){
    clearInterval(downloadTimer);
    document.getElementById("countdown").innerHTML = "Finished";
  } else {
    document.getElementById("countdown").innerHTML = timeleft + " seconds remaining";
  }
  timeleft -= 1;
}, 1000);
</script>
</body>
</html>
