<?php 
 require_once 'data_functions.php';

//First rank
if (isset($_GET['first']) && ($first_rank = intval($_GET['first'])) != 0) {
	$first_rank = ($first_rank < 1 ) ? 1 : $first_rank;
	$first_rank = ($first_rank > 5000) ? 5000 : $first_rank;
} else {
	$first_rank = 1;
}

//Last rank
if (isset($_GET['last']) && ($last_rank = intval($_GET['last'])) != 0) {
	$last_rank = ($last_rank > 5000) ? 5000 : $last_rank;	
	$last_rank = ($last_rank < 1 ) ? 1 : $last_rank;
} else {
	$last_rank = 5000;
}

//Season
if (isset($_GET['season'])) {
	$season_id = $_GET['season'];
} else {
	$season_id= NULL;
}

$format_number=0;
//Season
if (isset($_GET['dataType'])) {
	$dt = $_GET['dataType'];
	switch($dt) {
		case "season_winratio":
			$sortFunction = "sortBySeasonWinRatio";
			$data_field="season_winratio_avg";
			$axis_title="Average win ratio %";
			$format_number = 100;
			break;
		case "season_kdratio":
			$sortFunction = "sortBySeasonKDRatio";
			$data_field="season_kdratio_avg";
			$axis_title="Average K/D ratio";
			$format_number = 1;
			break;
		case "winratio":
			$sortFunction = "sortByWinRatio";
			$data_field="winratio_avg";
			$axis_title="Average win ratio % (all time)";
			$format_number = 100;
			break;
		case "prestige":
			$sortFunction = "sortByPrestige";
			$data_field="prestige_avg";
			$axis_title="Average prestige";
			$format_number=1;
			break;
		case "rank":
			$sortFunction = "sortByRank";
			$data_field="rank_avg";
			$axis_title="Average rank";
			break;
		default:
			$sortFunction = "sortBySeasonWinRatio";
			$data_field="season_winratio_avg";
			$axis_title="Win ratio % (this season)";
			$format_number = 100;
			break;
	}
} else {
	$dt="season_winratio";
	$sortFunction = "sortBySeasonWinRatio";
	$data_field="season_winratio_avg";
	$axis_title="Average win ratio %";
}

//Load character data
$characters = getCharacterData();

//Season dropdown
$season_list = getSeasonList();
end($season_list);
$current_season = key($season_list);
reset($season_list);
// Set season id to correct number 
if ($season_id === NULL || $season_id > $current_season || $season_id < 8) $season_id = $current_season;

//Load data for display (season-id === NULL > current)
//data structure ..=>id,sum,rank_avg
$data = getDataForSingleSeason($first_rank,$last_rank,(($season_id==$current_season)?NULL:$season_id),$characters,$sortFunction);

?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Awesomecharts | Stats for season</title>
<!-- DataTables CSS -->
<link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.5/css/jquery.dataTables.css">
  
<!-- jQuery -->
<script type="text/javascript" charset="utf8" src="//code.jquery.com/jquery-1.10.2.min.js"></script>
  
<!-- DataTables -->
<script type="text/javascript" charset="utf8" src="//cdn.datatables.net/1.10.5/js/jquery.dataTables.js"></script>

<!-- Canvas JS -->
<script type="text/javascript" src="scripts/canvasjs/canvasjs.min.js"></script>

<link rel="stylesheet" type="text/css" href="main.css">

<script type="text/javascript">
	$(document).ready( function () {
		$('#dataTable').DataTable({
			paging: false,
			"order": [[ 2, "desc" ]]
		});
	
		//Set form values
		var element = document.getElementById('seasonSelect');
		element.value = <?php echo $season_id;?>;
		document.getElementById('firstRank').value=<?php echo $first_rank;?>;
		document.getElementById('lastRank').value=<?php echo $last_rank;?>;
		var element2 = document.getElementById('dataTypeSelect');
		element2.value = "<?php echo $dt;?>";
	  
	  
		var chart = new CanvasJS.Chart("chartContainer",
		{
			exportEnabled: true,
			//width:1000,
			height:500,
			title:{
				text: "<?php echo "Player statistics of users with ranks between $first_rank and $last_rank grouped by favourite character (Season $season_id)";?>",  
				fontSize: 20
			},
			axisY: {
				title: "<?php echo $axis_title; ?>"
			},
			data: [
			{
				type: "column",  			
				dataPoints: [
				<?php $dpi=1;
				foreach ($data as $v) { 
				?> 
					{
						x: <?php echo $dpi++; ?>,
						y: <?php echo ($format_number!=0)?number_format($v[$data_field]*$format_number,2):$v[$data_field]; ?>,
						label: "<?php echo $characters[$v["id"]]["short"]; ?>"
					},
				<?php
					}
				?>
				]
			}			
			]
		});

		chart.render();
	} );

</script>
</head>
<body>
<div id="logo"><a href="index.php" title="Back to home"><img src="img/logo.png"	class="logoImg"/></a></div>
<center><h1>Various player statistics</h1>

<div id="control" >
<form id="controlForm" action="" method="get">
Season: <select name="season" id="seasonSelect">
<?php
foreach($season_list as $key=>$value) {
	echo "<option value=\"$key\">$value</option>\n";
}
?>
</select>
Data type:
<select name="dataType" id="dataTypeSelect">
<option value="season_winratio" selected="true">Seasonal win ratio avg</option>
<option value="season_kdratio">Seasonal kill/death ratio avg</option>
<option value="winratio">All-time win ratio avg</option>
<option value="prestige">Prestige avg</option>
<option value="rank">Rank avg</option>
</select>
First rank: <input type="text" name="first" id="firstRank"/>
Last rank: <input type="text" name="last" id="lastRank"/>
<input type="submit" value="Refresh">
</form>
</div>


<div id="chartContainer" style="height: 500px; width: 100%;"></div>	
Click on any slice to make it pop out.
</center>
<h2>Raw data</h2>
<table id="dataTable" class="display" cellspacing="0">
	<thead>
		<tr>
			<th>Character</th>		
			<th>% of users</th>
			<th># of users</th>
			<th>Seasonal win ratio average</th>
			<th>Seasonal K/D ratio average</th>
			<th>All-time win ratio average</th>
			<th>Prestige average</th>
			<th>Rank average</th>
		</tr>
    </thead>
	<tbody>
			<?php foreach ($data as $v) { 
			?> 
				<tr>
					<td class="charname"><?php echo $characters[$v["id"]]["name"]; ?></td>
					<td><?php echo number_format(($v["sum"]/($last_rank-$first_rank+1))*100,1)."%"; ?></td>
					<td><?php echo $v["sum"]; ?></td>
					<td><?php echo number_format($v["season_winratio_avg"]*100,2)."%"; ?></td>
					<td><?php echo number_format($v["season_kdratio_avg"],2); ?></td>
					<td><?php echo number_format($v["winratio_avg"]*100,2)."%"; ?></td>
					<td><?php echo number_format($v["prestige_avg"],1); ?></td>
					<td><?php echo $v["rank_avg"]; ?></td>
				</tr>
			<?php
				}
			?>
	</table>
</table>

	
</body>
</html>