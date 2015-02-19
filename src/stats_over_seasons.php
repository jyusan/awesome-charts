<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Seasonal stats</title>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
<script type="text/javascript" src="scripts/canvasjs/canvasjs.min.js"></script>
</head>

<body>
   <div id="chartContainer" style="height: 300px; width: 100%;">
   </div>
	
 <?php 
 require_once 'config/.connection.php'; //MySQL connection info

$mysqli = new mysqli($mysql_host, $mysql_user, $mysql_password, $mysql_database);

/*
 * Use this instead of $connect_error if you need to ensure
 * compatibility with PHP versions prior to 5.2.9 and 5.3.0.
 */
if (mysqli_connect_error()) {
    die('Connect Error (' . mysqli_connect_errno() . ') '
            . mysqli_connect_error());
}

$character_stats = array();

$sql="SELECT * FROM characters";

// Fetch all
$chardata = array();
if(!$result = $mysqli->query($sql)){
    die('There was an error running the query [' . $db->error . ']');
} else {
	while($row = $result->fetch_assoc()){
		//echo $row['id'] . ' - '.$row['name'].'<br />';
		$chardata[$row['id']] = array("name"=>$row['name'], "mc" => $row['main_color'], "hc" => $row['highlight_color']);
		$character_stats[$row['id']] = array();
	}
}
// Free result set
mysqli_free_result($result);

//Get seasons (Current one as last)
$seasons = array();
$season_labels = "";
if(!$result = $mysqli->query("SELECT id FROM seasons WHERE start_date <= now() order by id")){
	die('There was an error running the query [' . $mysqli->error . ']');
} else {
	while($row = $result->fetch_assoc()) {
		$sid = $row['id'];
		array_push($seasons, $sid);
		$season_labels.= "\"Season ".$sid."\",";	
		
		//Init character stats
		foreach ($character_stats as $id => $cs) {
			$character_stats[$id][$sid] = 0;
		}
	}
}	
mysqli_free_result($result);

$season_labels = substr($season_labels,0,-1); // delete last comma

//Get stats for finished seasons
if(!$result = $mysqli->query("SELECT season_id, character_id, num_users FROM stats_seasonend order by season_id")){
	die('There was an error running the query [' . $mysqli->error . ']');
} else {
	while($row = $result->fetch_assoc()){
		$character_stats[$row['character_id']][$row['season_id']] = $row['num_users'];
	}
}	
mysqli_free_result($result);

//Get stat for current seasons
$current_season = end($seasons);
reset($seasons);
if(!$result = $mysqli->query("SELECT character_id, num_users FROM stats_current")){
	die('There was an error running the query [' . $mysqli->error . ']');
} else {
	while($row = $result->fetch_assoc()){
		$character_stats[$row['character_id']][$current_season] = $row['num_users'];
	}
}	

$mysqli->close();

?>
	
	 <script type="text/javascript">
  window.onload = function () {
    var chart = new CanvasJS.Chart("chartContainer",
    {

      title:{
      text: "Character usage statistics at the end of seasons"
      },
      axisX: {
        valueFormatString: "MMM",
        interval:1,
        intervalType: "month"
      },
      axisY:{
        includeZero: false

      },
      data: [
	  <?php foreach ($chardata as $id => $char) { 
		?> 
			{
				type: "line",
				showInLegend: true,
				lineThickness: 2,
				name: "<?php echo $char["name"]; ?>",
				dataPoints: [
				<?php foreach ($character_stats[$id] as $sid=>$v) { 
						echo "{x:".$sid.", y:".$v.", label: \"Season ".$sid."\"},";
					}
				?>
				]
			},
		<?php				
		}
		?>
      
      ],
	  legend:{
            cursor:"pointer",
            itemclick:function(e){
              if (typeof(e.dataSeries.visible) === "undefined" || e.dataSeries.visible) {
              	e.dataSeries.visible = false;
              }
              else{
                e.dataSeries.visible = true;
              }
              chart.render();
            }
          }
    });

    chart.render();
  }
  </script>
  
  TODO: Show all / hide all / starstorm only / core only
</body>

</html>