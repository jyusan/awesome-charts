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
$data = getDataForSingleSeason($first_rank,$last_rank,(($season_id==$current_season)?NULL:$season_id),$characters);



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
	  
	  
		var chart = new CanvasJS.Chart("chartContainer",
		{
			exportEnabled: true,
			//width:1000,
			height:500,
			title:{
				text: "<?php echo "Character usage statistics of users with ranks between $first_rank and $last_rank (Season $season_id)";?>",  
				fontSize: 20
			},
			data: [
			{
				type: "pie",
				showInLegend: true,	   
				toolTipContent: "{y} - #percent %",
				dataPoints: [
				<?php foreach ($data as $v) { 
				?> 
					{
						y: <?php echo $v["sum"]; ?>,
						legendText: "<?php echo $characters[$v["id"]]["name"]; ?>",
						indexLabel: "<?php echo $characters[$v["id"]]["short"]; ?>"
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
<center><h1>Character usage statistics</h1>
<h4>Current settings: <?php echo ($season_id === NULL)?"Current season":("Season ".$season_id);?> ::
From rank <?php echo $first_rank;?> to <?php echo $last_rank;?></h4>

<div id="control" >
<form id="controlForm" action="" method="get">
Season: <select name="season" id="seasonSelect">
<?php
foreach($season_list as $key=>$value) {
	echo "<option value=\"$key\">$value</option>\n";
}
?>
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
(The data is based on the leaderboard with each player's most user character)
<table id="dataTable" class="display" cellspacing="0">
	<thead>
		<tr>
			<th>Character</th>		
			<th>% of users</th>
			<th># of users</th>
			<th>Rank average</th>
		</tr>
    </thead>
	<tbody>
			<?php foreach ($data as $v) { 
			?> 
				<tr>
					<td class="charname"><?php echo $characters[$v["id"]]["name"]; ?></td>
					<td><?php echo number_format(($v["sum"]/($last_rank-$first_rank+1))*100,1); ?></td>
					<td><?php echo $v["sum"]; ?></td>
					<td><?php echo $v["rank_avg"]; ?></td>
				</tr>
			<?php
				}
			?>
	</table>
</table>

	
</body>
</html>