<?php
// Creates a player object with attributes name, ip, status, role
// @line: line of players.txt to assign all values
class Player{
	public $ip;
	public $name;
	public $status;
	public $location;
	public $role;
	function __construct($line){
		$values = explode("-UwU-", $line);
		$this->ip = $values[0];
		$this->name = $values[1];
		$this->status = $values[2];
		$this->location = $values[3];
		$this->role = $values[4];
	}

}

// Get all the players in players.txt
// Returns an array of Player objects
function get_players ($filename="players.txt") {
	$players_file = file($filename) or no_players_file();
	$players = array();
	for($i=0;$i<sizeof($players_file);$i++){
		$player = new Player($players_file[$i]);
		if ($player->name!=""){
			$players[$i] = $player;
		}
	}
	return $players;
}

// Print all the player names in players.txt in a HTML unordered list
function print_player_names($filename="players.txt"){
	if (file_exists($filename)){
		$players_file = file($filename);
		if (sizeof($players_file)==1 and $players_file[0]===""){
			fclose($filename);
			return;
		}
		# Create unordered list
		echo "<ul>";
		foreach ($players_file as $line){
			if(sizeof(explode("-UwU-",$line))<3){
				echo "<li><span color='red'>Unvalid Entry</span></li>";
				continue;
			}
			$player = new Player($line);
			$name = $player->name;
			if (strpos($player->status,"in")!==false) {
				$name .= " (ingame)";
			}
			if ($player->ip===$_SERVER['REMOTE_ADDR']) {
				$name = "<span style='color:blue;'><b>".$name."</b></span>";
			}
				echo "<li>".$name."</li>";
		}
		echo "</ul>";
	}
}

// Returns number of players (resp. lines in players.txt)
function count_players($filename="players.txt"){
	if (file_exists($filename)){
		$players_file = file($filename) or no_players_file();
		$n = 0;
		foreach ( $players_file as $line ) {
			if ($line != ""){
				$n++;
			}
		}
		return $n;
	} else {
		no_players_file();
	}
}

// Is called if no players.txt file is available and exits the process
function no_players_file(){
	echo "Noch keine Spieler vorhanden..";
	exit;
}

// Returns random player name in players.txt
function rand_player_name($filename="players.txt"){
	$players = get_players($filename);
	return $players[array_rand($players)]->name;
}

function count_next_players($filename="players.txt"){
	if (file_exists($filename)){
		$players_file = file($filename) or no_players_file();
		$n = 0;
		foreach ( $players_file as $line ) {
			if (strpos($line,"next")!==false){
				$n++;
			}
		}
		return $n;
	} else {
		no_players_file();
	}
}


?>

