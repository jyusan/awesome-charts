<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Awesome charts</title>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
<script src="scripts/Chart.js"></script>
<script type="text/javascript">
$(function(){
//alert("poop");
var data = {};



$('#test').text("hhahh");
});

</script>
</head>

<body>
<table>
<tr>
<td>
<canvas id="myChart" width="400" height="400"></canvas>
</td>
<td>
<p id="test">valami</p>
</td>
</tr>
</table>
 <?php 
 require_once 'config/.connection.php';
 require_once 'get_stats.php';
 require_once 'html_colors.php';
//$link = mysql_connect($mysql_host, $mysql_user, $mysql_password);
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

$sql="SELECT * FROM awesomedb.characters";

// Fetch all
$charkeys = array();
if(!$result = $mysqli->query($sql)){
    die('There was an error running the query [' . $db->error . ']');
} else {
	while($row = $result->fetch_assoc()){
		//echo $row['id'] . ' - '.$row['name'].'<br />';
		$charkeys[$row['id']] = $row['name'];
	}
}
// Free result set
mysqli_free_result($result);

$mysqli->close();

echo "<br>";
print_r($charkeys);
echo "<br>";
echo "<br>";
$x=time(); 
$finalstats0 = getstats(file_get_contents("http://steamcommunity.com/stats/204300/leaderboards/483348/?xml=1"),$charkeys);
asort($finalstats0);
$finalstats = array_reverse($finalstats0,true);
echo '<br>'.(time()-$x)." seconds";

?>

<script>
$(function(){
	
$('#test').text("hhahh2");

var options = {
	animateScale: false,
	animationSteps : 100,
	legendTemplate : "<table class=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<segments.length; i++){%><tr><td style=\"background-color:<%=segments[i].fillColor%>; width:5px;\"></td><td><%if(segments[i].label){%><%=segments[i].label%><%}%></td><td><%=segments[i].value%></td></tr><%}%></table>"
};


var data = [
<?php foreach ($finalstats as $k => $v) { 
	if (array_key_exists($k,$charkeys)) {
?> 
    {
		value: <?php echo $v; ?>,
		label: "<?php echo $charkeys[$k]; ?>",
		color: "<?php echo $html_colors[rand(0,139)]; ?>",
		highlight: "<?php echo $html_colors[rand(0,139)]; ?>"
	},
<?php
	}
}
?>
];

// Get context with jQuery - using jQuery's .get() method.
var ctx = $("#myChart").get(0).getContext("2d");
// This will get the first returned node in the jQuery collection.

var myChart = new Chart(ctx).Pie(data,options);

$('#test').html(myChart.generateLegend());
});
</script>

</body>

</html>