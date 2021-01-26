<?php
function game_run($filename="game_status.txt"){
	$file = fopen($filename,"w");
	fwrite($file,"run");
	fclose($file);
}

function game_stop($filename="game_status.txt"){
	$file = fopen($filename,"w");
	fwrite($file,"stop");
	fclose($file);
}

function is_game_running($filename="game_status.txt"){
	$line = file($filename)[0];
	if (strpos($line,"run")!==false){
		return true;
	} 
	return false;
}
