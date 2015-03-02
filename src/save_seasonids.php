<?php
/* Onetime script for saving season ids
Based on http://steamcommunity.com/stats/204300/leaderboards/?xml=1 (PLAYERRANKING__)
Season - leaderboards - has_entries
Season 1 - 145095 - 0
Season 2 - 145658 - 0
Season 3 - 165967 - 0
Season 4 - 167738 - 0
... no entries
Season 8 - 274884 - 1
Season 9 - 298119 - 1
Season 10 - 331874 - 1
Season 11 - 397491 - 1
Season 12 - 483346 - 1
Season 13 - 483347 - 1
Season 14 - 483348 - 1
Season 15 - 483349 - 0
Season 16 - 483350 - 0
Season 17 - 483351 - 0
Season 18 - 483352 - 0
Season 19 - 483353 - 0
Season 20 - 483354 - 0
Season 20 - 483355 - 0
Season 23 - 483357 - 0
Season 24 - 483358 - 0
Season 25 - 483359 - 0
Season 26 - 483360 - 0
... inc+1 until 59
Manually entered from 8 to 14, script for the rest
*/
require_once 'config/.connection.php'; //MySQL connection info
$mysqli= new mysqli($mysql_host, $mysql_user, $mysql_password, $mysql_database);
//Start
$lbid = 483349;
$season = 15;
$dates = array (
array("start"=>"02-01","end"=>"03-31"),
array("start"=>"04-01","end"=>"05-31"),
array("start"=>"06-01","end"=>"07-31"),
array("start"=>"08-01","end"=>"09-30"),
array("start"=>"10-01","end"=>"11-30"),
array("start"=>"12-01","end"=>"01-31"),
);
$dsize=sizeof($dates);
$di = 1; //s15 is from 0401
$year = 2015;

$stmt=$mysqli->prepare("INSERT INTO seasons(id, start_date, end_date,leaderboard_id) VALUES (?,?,?,?)");
$stmt->bind_param("issi",$season_id,$start_date,$end_date,$leaderboard_id);

for($season=15; $season < 60; $season++) {
	$start = $year."-".$dates[$di]["start"];
	if ($di===($dsize-1)) $year++;
	$end = $year."-".$dates[$di]["end"];
	
	//Stmt binding
	$season_id=$season;
	$start_date=$start;
	$end_date=$end;
	$leaderboard_id= $lbid++;
	if($stmt->execute()) {		
		echo "Season $season_id: $start_date to $end_date. Leaderboard id: $leaderboard_id<br>\n";
	} else {
		echo "Error: ".$mysqli->error."<br>\n";
	}
	
	
	$di++;
	if ($di===$dsize) {
		$di=0;
	}
	
}

$stmt->close();
$mysqli->close();


?>