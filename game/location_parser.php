<?php
#$locations = parse_locations_file("locations.txt");
#print_locations($locations);
#$rand_location = choose_random_location($locations);
#echo $rand_location->name."<br>";
#echo choose_random_location($rand_location->roles);

class Location{
	public $name;
	public $roles;
	function __construct($loc_name, $loc_roles){
		$this->name = $loc_name;
		$this->roles = $loc_roles;
	}
}
function parse_locations_file($filename="locations.txt"){
	$locations = array();
	$lines = file($filename);
	$i=0;
	foreach ($lines as $line) {
		$firstchar = $line[0];
		# Kommentare: '#' als erstes Symbol oder leere Zeilen
#		if ( !($firstchar === "#" or preg_match("/^\d*$/",$line))){
		if ( !(preg_match("/^\s*#/",$line) or preg_match("/^\s*$/",$line))){
			$words = explode("\t", $line);
			$name = $words[0];
			$roles = explode(", ", $words[1]);
			$locations[$i] = new Location($name,$roles);
			$i++;
		}
	}
	if (sizeof($locations)==0) {echo "Es konnten keine Orte gefunden werden.";exit;}
	return $locations;
}

function print_locations($locations){
	foreach ($locations as $location)
		echo '<input type="checkbox" checked>'.$location->name."<br>";
	return;
}
function choose_random_location($locations){
	$size = count($locations);
	return $locations[rand(0,$size-1)];
}
function choose_random_from_array($arr){
	$size = count($arr);
	return $arr[rand(0,$size-1)];
}

function choose_random_role($location){
	$roles = $location->roles;
	$size = count($roles);
	return $roles[rand(0,$size-1)];
}

#function get_roles_for_location($location_name,$locations){
#	echo "";
#	return 0;
#}

?>

