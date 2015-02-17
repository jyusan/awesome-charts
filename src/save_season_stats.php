<?php
/**
* Save old season stats
* Parameters: 
	* sid - season id
	* key - admin key (config/.keys.php)
*/
 require_once 'config/.connection.php'; //MySQL connection info
 require_once 'config/.keys.php'; //Auth key
 require_once 'get_stats.php';

 //Check if qualified call
 if (!isset($_GET['key']) || htmlspecialchars($_GET["key"]) !=  $season_save_key) {
	 die("Unauthorized call.");
 }
   
$mysqli = new mysqli($mysql_host, $mysql_user, $mysql_password, $mysql_database);

/*
 * Use this instead of $connect_error if you need to ensure
 * compatibility with PHP versions prior to 5.2.9 and 5.3.0.
 */
if (mysqli_connect_error()) {
    die('Connect Error (' . mysqli_connect_errno() . ') '
            . mysqli_connect_error());
}

$update_current = TRUE;
if (!isset($_GET['sid'])) {
	//Season id not provided, updating current seasons
	
	if(!$result = $mysqli->query("SELECT id,leaderboard_id FROM seasons WHERE start_date <= now() and end_date >= now()")){
		die('There was an error running the query [' . $mysqli->error . ']');
	} else {
		$row = $result->fetch_assoc();
		$season_id = $row['id'];
		$lbid = $row['leaderboard_id'];
	}
	
} else {	 
	$season_id = $_GET['sid'];
	$update_current = FALSE;
	//Get leaderboard ID based on param

	/* create a prepared statement */
	if ($stmt = $mysqli->prepare("SELECT leaderboard_id FROM seasons WHERE id = ?")) {

		/* bind parameters for markers */
		$stmt->bind_param("i", $season_id);

		/* execute query */
		$stmt->execute();

		/* bind result variables */
		$stmt->bind_result($lbid);

		/* fetch value */
		$stmt->fetch();

		printf("Season %d's leaderboard id is %d\n", $season_id, $lbid);

		/* close statement */
		$stmt->close();
	}
}


// Get list of character codes for checking if 
$charkeys = array();
if(!$result = $mysqli->query("SELECT * FROM characters")){
    die('There was an error running the query [' . $mysqli->error . ']');
} else {
	while($row = $result->fetch_assoc()){
		$charkeys[$row['id']] = $row['name'];
	}
}
// Free result set
mysqli_free_result($result);

$finalstats = getstats(file_get_contents("http://steamcommunity.com/stats/204300/leaderboards/".$lbid."/?xml=1"));

printf("<br><u>Stats for season %d:</u><br>\n",$season_id);
$sql = "";
foreach ( $finalstats as $key => $value) {
	if(array_key_exists($key,$charkeys)) {
		printf("Char #%d, %s: %d<br>\n",$key,$charkeys[$key],$value);
		if ($update_current === TRUE) {
			$sql .= "INSERT INTO stats_current(character_id,num_users) VALUES(".$key.",".$value.") ON DUPLICATE KEY UPDATE num_users=".$value.";";
		} else {
			$sql .= "INSERT INTO stats_seasonend(season_id,character_id,num_users) VALUES(".$season_id.",".$key.",".$value.");";
		}
	}
}

//Insert stats - TODO maybe prepared statement instead
if ($mysqli->multi_query($sql) === TRUE) {
    echo "New records created successfully";
} else {
    echo "Error: " . $sql . "<br>" . $mysqli->error;
}


/* close connection */
$mysqli->close();

?>