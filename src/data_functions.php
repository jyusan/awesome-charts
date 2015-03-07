<?php 
 require_once 'db.php';

$LEADERBOARD_URL = "http://steamcommunity.com/stats/204300/leaderboards/{ID}/?xml=1";

/**
* Creates an array of all players, with rank => {wins, losses, swins, slosses, kills, deaths, prestige, fchar}
* Param: leaderboard xml, array of character data (invalid data is saved as -1)
*/
function getLeaderboardData($xml,$chars) {
	$stats = new SimpleXMLElement($xml);
	$stats_ret = array();
	foreach ($stats->entries->entry as $e) {
		$rank = intval($e->rank);
		$details = str_split($e->details, 8);
		$wins = hexdec(implode(array_reverse(str_split($details[1],2))));
		$losses = hexdec(implode(array_reverse(str_split($details[2],2))));
		$swins = hexdec(implode(array_reverse(str_split($details[7],2))));
		$slosses = hexdec(implode(array_reverse(str_split($details[8],2))));
		$kills = hexdec(implode(array_reverse(str_split($details[3],2))));
		$deaths = hexdec(implode(array_reverse(str_split($details[4],2))));
		$prestige = hexdec(implode(array_reverse(str_split($details[5],2))));
		$chid = hexdec(implode(array_reverse(str_split($details[6],2))));
		$chid = array_key_exists($chid,$chars) ? $chid : -1;
		$stats_ret[$rank] = array("wins"=>$wins, "losses"=>$losses, "swins" => $swins, "slosses" => $slosses, 
		"kills"=> $kills, "deaths"=>$deaths, "prestige"=>$prestige, "fchar" =>$chid);
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
		$charkeys[$row["id"]]=array("name"=>$row["name"],"short"=>$row["short_name"]);
	}
	return $charkeys;
}

/**
* Fetches the leaderboard id for the season in the parameter, or the current one if not set
* Returns array with (season id, leaderboard id)
*/
function getLeaderboardId($seasonid) {
	if (!isset($seasonid)) {
		//Season id not provided, getting current seasons	
		$row = DB::getInstance()->getCurrentSeasonAndLeaderboardId();
		$seasonid = $row['id'];
		$lbid = $row['leaderboard_id'];			
		return array($seasonid,$lbid);	
	} else {	
		$lbid = DB::getInstance()->getLeaderboardId($seasonid);
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
		if(DB::getInstance()->saveLeaderboardsDataForCurrent($data)) {
			return DB::getInstance()->updateLastCurrentSaveTime();
		}
		return false;
	}
}

/**
* Downloads the xml with leaderboard data
* param = leaderboard id
*/
function getLeaderboardXML($id) {
	global $LEADERBOARD_URL;
	$url = str_replace("{ID}",$id,$LEADERBOARD_URL);
	//echo $url."-".$id."\n";
	return file_get_contents($url);
}

function getSeasonList() {
	if ($data = DB::getInstance()->getSeasonsWithData()) {
		$list = array();
		$last_key;
		foreach ($data as $d) {
			$list[$d] = "Season $d";
			$last_key=$d;
		}
		$list[$last_key] .= " (current)";
		return $list;
	}
	return NULL;
}

function getDataForSingleSeason($first,$last,$season,$charlist) {
	if(isset($season)) {
		//Historic data
		$raw_data = DB::getInstance()->getHistoricData($first,$last,$season);
	} else {
		//Current data
		$raw_data = DB::getInstance()->getCurrentData($first,$last);
	}
	
	//Init statistics array, structure: character id => {"sum","rank avg"}
	$data = array();
	foreach($charlist as $id=>$n) {
		$data[$id] = array("sum" => 0, "rank_avg" => 0);
	}
	
	//
	
	//Get character sums
	foreach($raw_data as $row) {
		$char = $row["char_id"];
		if(array_key_exists($char,$charlist)) {
			$data[$char]["sum"] += 1;
			$data[$char]["rank_avg"] += $row["rank"];
		}
	}
	
	//All data in new, sortable array with proper avg
	$data_sortable = array();
	foreach($data as $key=> $row) {
		$sum = $row["sum"];
		$ravg = (int)$row["rank_avg"];
		if ($ravg != 0) {
			$ravg /= $sum;
		} else {
			//No usage of the character, when sorted by rank it should be at the end
			$ravg = 5000;
		}
		array_push($data_sortable, array("id"=>$key,"sum"=>$sum,"rank_avg"=>(int)$ravg));
	}

	usort($data_sortable, 'sortBySum');	
	return array_reverse($data_sortable); //desc order
	
}

function sortBySum($a, $b) {
   return $a["sum"] - $b["sum"];
}

function getLastUpdateTime() {
	return DB::getInstance()->getLastCurrentSaveTime();
}

//Returns true if season has historic data saved
function checkSeasonTable($season_id) {
	return DB::getInstance()->checkSeasonTable($season_id);
}

?>