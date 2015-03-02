<?php
/* Script for updating current leaderboard */
//set_time_limit(0); //Can take long time
 require_once 'config/.keys.php'; //Auth key $admin_key is stored here
 require_once 'data_functions.php'; 

 //Check if qualified call
 if (!isset($_GET['key']) || htmlspecialchars($_GET["key"]) !=  $admin_key) {
	 die("Unauthorized call.");
 } else {
	 $t = time();
	 $chars = getCharacterData();
	 // IDS
	$ids = getLeaderboardId(NULL);
	$xml = getLeaderboardXML($ids[1]);
	$data = getLeaderboardData($xml,$chars);
	//NULL -> saving to current leaderboard
	if (saveLeaderboardData(NULL,$data)) {	
		echo "Current leaderboard data saved (season ".$ids[0]."). Time spent on script: ".(time()-$t)." seconds.<br>\n";
	} else {
		echo "Couldn't save current leaderboard data (season ".$ids[0].", leaderboard ".$ids[1].")<br>\n";
	}
 }

?>