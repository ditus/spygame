<?php

//require("location_parser.php");
// Searches for IP in players.txt for player status
// @line: IP addr of player
// Returns player game status (in, innext, next)
function get_player_status($ip,$filename="players.txt"){
	$content = file($filename);
	foreach ($content as $line){
		if (strpos($line,$ip)!==false){
			return explode("-UwU-",$line)[2];
		}
	}
	return false;
}


function players_assign_ingame_status($filename="players.txt"){
	$content = file($filename);
	for ($i=0; $i<sizeof($content); $i++) {
		if (strpos($content[$i],"next")){
			$content[$i] = change_player_status($content[$i],"innext");
		}
	}
	$content = implode("",$content);
	$file = fopen($filename,"w");
	fwrite($file, $content);
	fclose($file);
}

function players_assign_nogame_status($filename="players.txt"){
	$content = file($filename);
	for ($i=0; $i<sizeof($content); $i++) { 
		$status = explode("-UwU",$content[$i])[2];
		if (strpos($status,"in")){
			$content[$i] = change_player_status($content[$i],"next");
		}
	}
	$content = implode("",$content);
	$file = fopen($filename,"w");
	fwrite($file, $content);
	fclose($file);
}

// DEBUG Abfragen ob jemand ingame ist (enthält Zeile Substring "in") falsch, wenn "in" im Spielernamen vorhanden ist
// BZW wenn IP als Name eingetragen wird!?!?
function any_ingame($filename="players.txt"){
	$content = file($filename);
	foreach ($content as $line){
		$status = explode("-UwU-",$line)[2];
		if (strpos($status,"in")!==false){
			return true;
		}
	}
	return false;
}

function change_player_status($line,$new_status) {
	$line = explode("-UwU-",$line);
	$line[2] = $new_status;
	$line = implode("-UwU-",$line);
	return $line;
}

function change_my_status($new_status,$filename="players.txt"){
	$ip = $_SERVER["REMOTE_ADDR"];
	$content = file($filename);
	for ($i=0; $i<sizeof($content); $i++) { 
		if (strpos($content[$i],$ip)!==false){
			$content[$i] = change_player_status($content[$i],"next");
		}
	}
	$content = implode("",$content);
	$file = fopen($filename,"w");
	fwrite($file, $content);
	fclose($file);
}



function players_assign_location($filename="players.txt"){
	$seperator = "-UwU-";
	$sep = $seperator;
	// get location
	$locations = parse_locations_file("locations.txt");
	$current_location = choose_random_location($locations);
	// change location and role
	$content = file($filename);
	//choose a spy
	$nplayers = sizeof($content);
	$number_of_spy = rand(0,$nplayers-1);
	//choose beginner
	//beginner hat sternchen am ende von der Rolle
	$number_of_beginner = rand(0,$nplayers-1);
	for ($i=0; $i<$nplayers; $i++) {
		if (strpos($content[$i],"in")){
			//$content[$i] = change_player_status($content[$i],"innext");
			$line = explode($sep,$content[$i]);
			$role = choose_random_role($current_location);
#			if ($i == $number_of_beginner){
#				$role .= "*"; 
#			}
			if ($i == $number_of_spy){
				$newline = $line[0].$sep.$line[1].$sep.$line[2].$sep."spy".$sep."spy";
			} else {
				$newline = $line[0].$sep.$line[1].$sep.$line[2].$sep.$current_location->name.$sep.$role;
			}
			$newline .= "\n"; //file() returns array with newlines
			$content[$i] = $newline;
		}
	}
	$new_content = implode("",$content);
	$file = fopen($filename,"w");
	fwrite($file, $new_content);
	fclose($file);
	
}

function get_my_player($ip,$filename="players.txt"){
	$sep="-UwU-";
	$content = file($filename);
	foreach ($content as $line){
		if (explode($sep,$line)[0]===$ip){
			$player = new Player($line);
			break;
		}
	}
		return $player;
}

function get_start_player($filename="players.txt"){
	$content = file($filename);
	$player;
	foreach ($content as $line){
		if (! strpos($line,"*") ){
			$player = new Player($line);
			break;
		}
	}
	return $player;
}


?>

