<?php 
/*Required in main screen for signing in and out of game
IP,Playername and Game-Status are written as a line in players.txt for every signed-in player (seperated by "-UwU-")
If IP is already contained in players.txt playername will be changed
player-status:	next	->wants to play next round, 
		in	->ingame but not playing next round,
		innext	->ingame and wants to play next round
*/
$sep = "-UwU-";
if (isset($_REQUEST["player"])){ 
	$playername = $_REQUEST["player"]; 
	$fname = "players.txt";
	$found = -1;
	if (file_exists($fname)){
		$content = file($fname);
		$ip = $_SERVER['REMOTE_ADDR'];
		for($i=0;$i<sizeof($content);$i++){
			# Once player is found in a lilne
			if (strpos($content[$i],$ip)!==false){
				if ($found != -1) {
					die("Duplicate Entry of Player.");
				}
				$content[$i] = explode($sep,$content[$i],2)[0].$sep.$playername.$sep."next".$sep.$sep."\n";
				$found = $i;
			}
		}
	}
	# Add player as a line
	if ($found == -1) {
		$file = fopen($fname,"a") or die("Spieler konnte nicht hinzugefügt werden.");
		//fwrite($file,$_SERVER["REMOTE_ADDR"]."-UwU-".$playername."-UwU-next-UwU-\n");
		fwrite($file,$_SERVER["REMOTE_ADDR"].$sep.$playername.$sep."next".$sep.$sep."\n");
		fclose($file);
		return "added";
	} 
	# Change player name
	else {
		$file = fopen($fname,"w") or die("Spielername konnte nicht geändert werden.");
		fwrite($file,implode("",$content));
		fclose($file);
		return "changed";
	}
}
?>
