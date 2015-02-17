<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Seasonal stats</title>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
<script src="scripts/Chart.js"></script>
</head>

<body>
	<table>
		<tr>
			<td>
				<canvas id="myChart" width="800" height="400"></canvas>
			</td>
			<td>				
				<form action="">
					 <button type="button" id="checkall">Check all</button> <button type="button" id="uncheckall">Uncheck all!</button> 
					 <p id="chart_label">
					 </p>
				</form>
			</td>
		</tr>
	</table>
	
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
		$(function(){			
			var datasets_master = [
				<?php foreach ($chardata as $id => $char) { 
				?> 
					{
						label: "<?php echo $char["name"]; ?>",
						fillColor: "rgba(220,220,220,0.2)",
						strokeColor: "<?php echo $char["mc"]; ?>",
						pointColor: "<?php echo $char["mc"]; ?>",
						pointStrokeColor: "#fff",
						pointHighlightFill: "#fff",
						pointHighlightStroke: "<?php echo $char["hc"]; ?>",
						data: [
						<?php foreach ($character_stats[$id] as $v) { 
								echo $v.",";
							}
						?>
						]
					},
				<?php				
				}
				?>
				];
			
		
		//Button listeners
		$( "#checkall" ).click(function() {
			$('.checkbox').prop('checked', true);
			reloadChart(false,datasets_master); //all data is shown
		});
		$( "#uncheckall" ).click(function() {
			$('.checkbox').prop('checked', false);
			reloadChart(true,datasets_master); //all data is shown
		});
		
		reloadChart(false,datasets_master);
		
		function reloadChart(check_boxes, data_orig) {
		if (check_boxes) {
			var data = {
				labels: [<?php echo $season_labels; ?>],
				datasets: []
			};
		} else {
			var data = {
				labels: [<?php echo $season_labels; ?>],
				datasets: data_orig
			};
		}			
			
		var options = {
			//Boolean - Whether to fill the dataset with a colour
			datasetFill : false,
			//String - A legend template
			legendTemplate : "<table class=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<datasets.length; i++){%><tr><td><span style=\"background-color:<%=datasets[i].strokeColor%>\">XXXX</span><input type=\"checkbox\" class=\"checkbox\" name=\"character\" checked=\"true\" value=\"<%=datasets[i].id%>\"><%if(datasets[i].label){%><%=datasets[i].label%><%}%></td></tr><%}%></table>"
		};

			//Chart init
			var ctx = $("#myChart").get(0).getContext("2d");
			var myLineChart = new Chart(ctx).Line(data, options);
			$('#chart_label').html(myLineChart.generateLegend());
		}
		
		});
	</script>
</body>

</html>