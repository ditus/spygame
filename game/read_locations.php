<?php
print_locations();
get_roles_for_location("Theater");
function print_locations () {
#	$head="";
#	$body="";
#	$tail="";
	$filename = "locations.txt";
	$locations_file = fopen($filename, "r") or die("hell what");
	echo "<ul>";
	while (! feof($locations_file)) {
		$line = fgets($locations_file);
		if ($line!=""){
			$firstchar = $line[0];
			if ( $firstchar != "#" ){
				$words = explode("\t", $line);
				$location = $words[0];
				echo '<input type="checkbox" checked><b>'.$location."</b>";
				$roles = explode(", ",$words[1]);
				$size = count($roles);
				foreach ($roles as $role)
					echo " ".$role.",";
				echo "<br>";
			}
		}
	}
	echo "</ul>";
	fclose($locations_file);
	return 0;
}
function get_roles_for_location($loc){
	$filename = "locations.txt";
	$locations_file = fopen($filename, "r") or die("hell what");
	while (! feof($locations_file)) {
		$line = fgets($locations_file);
		if ($loc == explode("\t", $line)[0]){
			$roles = explode(", ",explode("\t", $line)[1]);
			foreach ($roles as $role)
				echo " ".$role.",";
		}
	}
	fclose($locations_file);
	return $roles;
}
class Location{
	public $name;
	public $roles;
	function __construct($loc_name, $loc_roles){
		$this->name = $loc_name;
		$this->roles = $loc_roles;
	}
}
function parse_locations_file($filename){
	$filename = "locations.txt";
	$locations_file = fopen($filename, "r") or die($filename." could not be opened!");
	$i=0;
	$locations;
	while (! feof($locations_file)) {
		$line = fgets($locations_file);
		if ($line!=""){
			$firstchar = $line[0];
			if ( $firstchar != "#" ){
				$words = explode("\t", $line);
				$name = $words[0];
				$roles = explode(", ", $words[1]);
				$locations[$i] = new Location($name,$roles);
#				echo $roles[0].$i."<br>";
				$i++;
			}
		}
	}
	return $locations;
}
$locations = parse_locations_file("locations.txt");
echo $locations[0]->name;

#function print_locations($locations){
#	foreach ($locations as $location)
#		echo " ".$location->name.",";
#	return;
#}

#function get_roles_for_location($location_name,$locations){
#	echo "";
#	return 0;
#}

?>

