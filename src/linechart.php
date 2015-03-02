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
 
//Load character data
$characters = getCharacterData();

//Season dropdown
$season_list = getSeasonList();
end($season_list);
$current_season = key($season_list);
reset($season_list);

//Data for the linechart display needs to be character_id => array of seasonid=>sum
$stats = array();
//Init main structure
foreach($characters as $id=>$v) {
	$stats[$id] = array();
}

foreach($season_list as $id=>$stuff) {
	$data = getDataForSingleSeason($first_rank,$last_rank,(($id==$current_season)?NULL:$id),$characters); //returns id,sum,rank_avg
	foreach ($data as $row) {
		$stats[$row["id"]][$id]=$row["sum"];
	}
}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Awesomecharts | Stats over the seasons</title>
<link rel="stylesheet" type="text/css" href="main.css">
<!-- DataTables CSS -->
<link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.5/css/jquery.dataTables.css">

<!-- jQuery -->
<script type="text/javascript" charset="utf8" src="//code.jquery.com/jquery-1.10.2.min.js"></script>
<!-- Canvas JS -->
<script type="text/javascript" src="scripts/canvasjs/canvasjs.min.js"></script>
<!-- DataTables -->
<script type="text/javascript" charset="utf8" src="//cdn.datatables.net/1.10.5/js/jquery.dataTables.js"></script>

<script type="text/javascript">
	$(document).ready( function () {
		$('#dataTable').DataTable({
			paging: false,
			"scrollX": true
		});
		
		document.getElementById('firstRank').value=<?php echo $first_rank;?>;
		document.getElementById('lastRank').value=<?php echo $last_rank;?>;
		
		$chart = new CanvasJS.Chart("chartContainer",
		{
			//if there are lot of seasons to display, enable zoom
			exportEnabled: true,
			//title cut
			title:{
				text: "<?php echo "Character usage statistics of users with ranks between $first_rank and $last_rank";?>",  
				fontSize: 20
			},
			axisX: {
				title:"Seasons",
				titleFontSize: 15,
				interval:1
			},
			axisY:{
				title:"Users favouring the character",
				titleFontSize: 15
			},
			data: [
			<?php foreach ($characters as $id => $char) { 
			?> 
				{
					type: "line",
					showInLegend: true,
					lineThickness: 2,
					name: "<?php echo $char["name"]; ?>",
					toolTipContent: "<?php echo $char["short"]; ?>: {y}<br/>(Season {x})",     
					dataPoints: [
					<?php foreach ($stats[$id] as $sid=>$v) { 
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
              $chart.render();
            }
			}
		});

		$chart.render();
	
		$( "#hideBtn" ).click(function() {
			$.each($chart.options.legend, function(k, e) {
				
					alert('key: ' + k + '\n' + 'value: ' + e);
				
			});
			$chart.render();
		});
	});
</script>
</head>
<body>
<center>
<h1>Character usage statistics over the seasons - line chart</h1>
<div id="control" >
<form id="controlForm" action="" method="get">
First rank: <input type="text" name="first" id="firstRank"/>
Last rank: <input type="text" name="last" id="lastRank"/>
<input type="submit" value="Refresh">
</form>
</div>
<div id="chartContainer" style="height: 500px; width: 100%;"></div>
Click on the labels to control the visibility specific character data points<br/>
</center>

<h2>Raw data</h2>
<table id="dataTable" class="display" cellspacing="0">
	<thead>
		<td>Character</td>
		<?php foreach($season_list as $season) {
			echo "<td>$season</td>\n";
		} ?>
	</thead>
	<tbody>
		<?php foreach ($characters as $id => $char) { 
		?> 
			<tr>
				<td class="charname"><?php echo $char["name"]; ?></td>
				<?php foreach ($stats[$id] as $sid=>$v) { 
						echo "<td>".$v."</td>";
					}
				?>
			</tr>
		<?php				
		}
		?>      
	</tbody>
</table>
</body>
</html>