<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Awesome charts</title>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
<script type="text/javascript" src="scripts/canvasjs/canvasjs.min.js"></script>
</head>

<body>	
 <?php 
 require_once 'config/.connection.php'; //MySQL connection info
 require_once 'get_stats.php';

$mysqli = new mysqli($mysql_host, $mysql_user, $mysql_password, $mysql_database);

/*
 * Use this instead of $connect_error if you need to ensure
 * compatibility with PHP versions prior to 5.2.9 and 5.3.0.
 */
if (mysqli_connect_error()) {
    die('Connect Error (' . mysqli_connect_errno() . ') '
            . mysqli_connect_error());
}

$sql="SELECT * FROM characters";

// Fetch all
$chardata = array();
$mysqli->query("set names 'utf8'");
if(!$result = $mysqli->query($sql)){
    die('There was an error running the query [' . $mysqli->error . ']');
} else {
	while($row = $result->fetch_assoc()){
		$chardata[$row['id']] = array("name"=>$row['name']);
	}
}
// Free result set
mysqli_free_result($result);

$mysqli->close();

$finalstats0 = getstats(file_get_contents("http://steamcommunity.com/stats/204300/leaderboards/483348/?xml=1"));
asort($finalstats0);
$finalstats = array_reverse($finalstats0,true);

?>
	
	<script type="text/javascript">
  window.onload = function () {
    var chart = new CanvasJS.Chart("chartContainer",
    {
		width:1000,
		height:500,
      title:{
        text: "Character usage statistics among the top 5000 players"
      },
       data: [
      {
         type: "pie",
       showInLegend: true,	   
			toolTipContent: "{y} - #percent %",
       dataPoints: [
       //{  y: 4181563, legendText:"PS 3", indexLabel: "PlayStation 3" },
		<?php foreach ($finalstats as $k => $v) { 
			if (array_key_exists($k,$chardata)) {
		?> 
			{
				y: <?php echo $v; ?>,
				legendText: "<?php echo $chardata[$k]["name"]; ?>",
				indexLabel: "<?php echo $chardata[$k]["name"]; ?>"
			},
		<?php
			}
		}
		?>
       ]
     }
     ]
   });

    chart.render();
  }
  </script>

   <div id="chartContainer" style="height: 300px; width: 100%;">
   </div>
	
	
</body>

</html>