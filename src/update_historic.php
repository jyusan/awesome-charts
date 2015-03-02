<?php
//Checks whether the last completed season's data has been saved.
//Ideally this is called on the first day of every second month (February, April..)
 require_once 'config/.keys.php'; //Auth key $admin_key is stored here
 require_once 'data_functions.php'; 

 //Check if qualified call
 if (!isset($_GET['key']) || htmlspecialchars($_GET["key"]) !=  $admin_key) {
	 die("Unauthorized call.");
 }
 
 $current_season_ids = getLeaderboardId(NULL);
 
 $penultimate_season = $current_season_ids[0] - 1;
 
 if(checkSeasonTable($penultimate_season)) {
	 echo "Historic data for season $penultimate_season is already saved.";
 } else {
	$ids = getLeaderboardId($penultimate_season);
	$xml = getLeaderboardXML($ids[1]);
	$data = getLeaderboardData($xml,$chars);
	if (saveLeaderboardData($penultimate_season,$data)) {
		echo "Data saved for season ".$penultimate_season."<br>\n";
	} else {
		echo "Couldn't save data for season ".$penultimate_season."<br>\n";
	}
 }
 
?>