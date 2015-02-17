<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Awesome charts</title>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
<script src="scripts/Chart.js"></script>
</head>

<body>
	<table>
		<tr>
			<td>
				<canvas id="myChart" width="400" height="400"></canvas>
			</td>
			<td>
				<p id="chart_label">Label</p>
			</td>
		</tr>
	</table>
	
 <?php 
 require_once 'config/.connection.php'; //MySQL connection info
 require_once 'get_stats.php';
 require_once 'html_colors.php';

$mysqli = new mysqli($mysql_host, $mysql_user, $mysql_password, $mysql_database);

/*
 * Use this instead of $connect_error if you need to ensure
 * compatibility with PHP versions prior to 5.2.9 and 5.3.0.
 */
if (mysqli_connect_error()) {
    die('Connect Error (' . mysqli_connect_errno() . ') '
            . mysqli_connect_error());
}

echo 'Success... ' . $mysqli->host_info . "\n<br>";

$sql="SELECT * FROM characters";

// Fetch all
$chardata = array();
if(!$result = $mysqli->query($sql)){
    die('There was an error running the query [' . $mysqli->error . ']');
} else {
	while($row = $result->fetch_assoc()){
		//echo $row['id'] . ' - '.$row['name'].'<br />';
		$chardata[$row['id']] = array("name"=>$row['name'], "mc" => $row['main_color'], "hc" => $row['highlight_color']);
	}
}
// Free result set
mysqli_free_result($result);

$mysqli->close();

$x=time(); 
$finalstats0 = getstats(file_get_contents("http://steamcommunity.com/stats/204300/leaderboards/483348/?xml=1"));
asort($finalstats0);
$finalstats = array_reverse($finalstats0,true);
echo '<br>'.(time()-$x)." seconds";

?>

	<script type="text/javascript">
		$(function(){
			
		$('#test').text("hhahh2");

		var options = {
			animateScale: false,
			animationSteps : 100,
			legendTemplate : "<table class=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<segments.length; i++){%><tr><td style=\"background-color:<%=segments[i].fillColor%>; width:5px;\"></td><td><%if(segments[i].label){%><%=segments[i].label%><%}%></td><td><%=segments[i].value%></td></tr><%}%></table>"
		};


		var data = [
		<?php foreach ($finalstats as $k => $v) { 
			if (array_key_exists($k,$chardata)) {
		?> 
			{
				value: <?php echo $v; ?>,
				label: "<?php echo $chardata[$k]["name"]; ?>",
				color: "<?php echo $chardata[$k]["mc"]; ?>",
				highlight: "<?php echo $chardata[$k]["hc"]; ?>"
			},
		<?php
			}
		}
		?>
		];

		var ctx = $("#myChart").get(0).getContext("2d");

		var myChart = new Chart(ctx).Pie(data,options);

		$('#chart_label').html(myChart.generateLegend());
		});
	</script>
</body>

</html>