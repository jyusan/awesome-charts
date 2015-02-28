<?php 
 require_once 'db.php';

$LEADERBOARD_URL = "http://steamcommunity.com/stats/204300/leaderboards/{ID}/?xml=1";

/**
* Creates an array of all players, with rank => favourite character
* Param: leaderboard xml, array of character data (invalid data is saved as -1)
*/
function getLeaderboardData($xml,$chars) {
	$stats = new SimpleXMLElement($xml);
	$stats_ret = array();
	foreach ($stats->entries->entry as $e) {
		$rank = intval($e->rank);
		$chid = hexdec(implode(array_reverse(str_split(str_split($e->details, 8)[6],2))));
		$chid = array_key_exists($chid,$chars) ? $chid : -1;
		$stats_ret[$rank] = $chid;
	}
	return $stats_ret;
}

/**
* Get array with character data
* id=>name
*/
function getCharacterData() {
	$result = DB::getInstance()->getCharacterData();
	$charkeys = array();
	foreach($result as $row) {
		$charkeys[$row["id"]]=$row["name"];
	}
	return $charkeys;
}

/**
* Fetches the leaderboard id for the season in the parameter, or the current one if not set
* Returns array with (season id, leaderboard id)
*/
function getLeaderboardId($seasonid) {
	global $SQL_GET_CURRENT_SEASON_IDS;
	global $SQL_GET_LEADERBOARD_ID;
	if (!isset($seasonid)) {
		//Season id not provided, getting current seasons	
		$row = DB::getInstance()->getCurrentSeasonAndLeaderboardId();
		print_r($row);
		$seasonid = $row['id'];
		$lbid = $row['leaderboard_id'];			
		return array($seasonid,$lbid);	
	} else {	
		$lbid = DB::getInstance()->getLeaderboardId($seasonid);
		echo $lbid;
		return array($seasonid,$lbid);	
	}
	return null;
}

/**
* Saves leaderboard data
* If season_id is not set, saves to current data table
*/
function saveLeaderboardData($season_id, $data) {
	if (isset($season_id)) {
		return DB::getInstance()->saveLeaderboardsDataForSeason($season_id, $data);
	} else {
		//Saving current seasons data
		return DB::getInstance()->saveLeaderboardsDataForCurrent($data);
	}
}

/**
* Downloads the xml with leaderboard data
* param = leaderboard id
*/
function getLeaderboardXML($id) {
	global $LEADERBOARD_URL;
	$url = str_replace("{ID}",$id,$LEADERBOARD_URL);
	echo $url."-".$id."\n";
	return file_get_contents($url);
}

?>