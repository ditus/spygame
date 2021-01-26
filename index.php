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

	# 20 Sekunden zum neu laden UND 30 Sekunden Spielpause zwischen den Spielen, somit wird automatisch beigetreten
	#header("refresh:20;url='/spy/';"); 
	header("refresh:15"); 
	require("add_player.php");
	require("read_players.php");
	require("game/player_status.php");
	require("game/set_game_status.php");

	if (isset($_POST["finito"])){
		change_my_status("next");
		if (!any_ingame()) game_stop("game/game_status.txt");
	}

	if (isset($_POST["reset"])){
		players_assign_nogame_status();
		game_stop("game/game_status.txt");
	}

	//delete_player.php hier eingefügt, da sonst Probleme mit game_stop() Funktion
	//// konnte nicht zugreifen ohne require (nicht definiert)
	//// redeclare der Funktion, wenn require benutzt wurde
	if (isset($_POST["austragen"])){
		$ip = $_SERVER["REMOTE_ADDR"];
		$fname = "players.txt";
		$del = -1;
		if (file_exists($fname)){
			$content = file($fname);
			for($i=0;$i<sizeof($content);$i++){
				if (strpos($content[$i],$ip)!=="false"){
					$del = $i;
					break;
				}
			}
			array_splice($content,$del,1);
			$file = fopen($fname,"w");
			fwrite($file,implode("",$content));
			fclose($file);
		} 
	}


	if (isset($_POST["alle_austragen"])){
		$fname = "players.txt";
		if (file_exists($fname)){
			$file = fopen($fname,"w");
			fwrite($file,"");
			fclose($file);
		}
		game_stop("game/game_status.txt");
	}

	?>
</head>
<body>
<a href="?admin=true"><div style="height:5px;width:50px;"></div></a>
<div class="outerwrapper">
<div class="wrapper">
	<h1>SPYGAME</h1>
	<form action="." method="post">
	    <input type="text" name="player" placeholder="Name" required>
	    <input type="submit" name="mitspielen" value="Mitspielen">
	</form>
	<form action="." method="post">
		<input type="submit" name="austragen" value="Austragen">
		<?php
		# "Alle Eingetragenen entfernen"-Button nur enablen, wenn man /spy/?admin=true angegeben hat 
		if (isset($_GET["admin"]) and $_GET["admin"]){
			echo '<input type="submit" name="alle_austragen" value="Alle Eingetragenen entfernen">';
		} else {
			echo '<input type="submit" name="alle_austragen" value="Alle Eingetragenen entfernen" disabled>';
		}
		?>

	</form>
	<br>
	<b><?php echo count_players(); ?> Eingetragene Spieler:</b><br>
	<?php print_player_names(); ?>
	<form action="game" method="post">
		<input type='submit' name='start_game' value='Spiel f&uuml;r alle starten!'>
	</form>
	<?php  
	if (isset($_GET["admin"]) and $_GET["admin"]){
		echo "<form method='post'>";
		echo '<input type="submit" name="reset" value="RESET #adminpower">';
		echo "</form>";
	}
	?>
	</form>
	<?php
	if (isset($_GET["2less"]) and $_GET["2less"]){
		echo "<span style='color:red;'><i>Spiel ben&ouml;tigt mind. 3 Spieler!</i></span>";
	}
	if (isset($_GET["2late"]) and $_GET["2late"]){
		echo "<span style='color:red;'><i>Spiel l&auml;uft bereits.</i></span>";
	}
	if (isset($_GET["2fast"]) and $_GET["2fast"]){
		echo "<span style='color:red;'><i>Bitte zuerst mitmachen, bevor das Spiel gestartet wird.</i></span>";
	}
	?>
</div>
</div>
	
</body>
</html>
