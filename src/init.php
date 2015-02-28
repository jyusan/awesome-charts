<?php
/**
* Script for saving all previous seasons.
* From 8 to latest.
*/
set_time_limit(0); //Can take long time

 require_once 'config/.keys.php'; //Auth key $admin_key is stored here
 require_once 'data_functions.php'; 

 //Check if qualified call
 if (!isset($_GET['key']) || htmlspecialchars($_GET["key"]) !=  $admin_key) {
	 die("Unauthorized call.");
 }
 
 if (!isset($_GET['current'])) {
	 die("Missing parameter \"current\"");
 }
  $current_season = $_GET['current'];
 

$chars = getCharacterData();

for($i=8; $i<$current_season; $i++) {
	$ids = getLeaderboardId($i);
	$xml = getLeaderboardXML($ids[1]);
	$data = getLeaderboardData($xml,$chars);
	if (saveLeaderboardData($ids[0],$data)) {
		echo "Data saved for season ".$i."<br>\n";
	} else {
		echo "Couldn't save data for season ".$i."<br>\n";
	}
}



?>