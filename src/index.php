<?php
	require_once 'data_functions.php'; 
	
	$lastUpdate = getLastUpdateTime();
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Awesome charts</title>
<link rel="stylesheet" type="text/css" href="main.css">
</head>
<body>
<center><img src="img/logo.png"	class="logoImg"><br/>
An experimental website for displaying Awesomenauts statistics<br/>
The website stores the favourite characters of the top 5000 ranked players and has different display options for data enthusiasts.
<hr/>
<h2>Features</h2>
<table id="menu">
	<tr class="menu_header">
		<td colspan="2"><b>Seasonal statistics</b></td>
	</tr>
	<tr>
		<td class="menu_cell">
			<a href="piechart.php"><img src="img/piechart.png" style="width:50%;"/><br/>Pie chart of character usage</a>
		</td>
		<td class="menu_cell">
			<a href="columnchart.php"><img src="img/columnchart.png" style="width:50%;"/><br/>Column chart of user data</a>
		</td>
	</tr>
	
	<tr class="menu_header">
		<td colspan="2"><b>Historical statistics</b></td>
	</tr>
	<tr>
		<td class="menu_cell">
			<a href="linechart.php"><img src="img/linechart.png" style="width:50%;"/><br/>Line chart display</a>
		</td>
		<td class="menu_cell">
			<a href="stackedchart.php"><img src="img/stackedchart.png" style="width:50%;"/><br/>Stacked area chart display</a>
		</td>
	</tr>
</table>
<hr/>
Last update of current leaderboard's data: <?php echo gmdate("D, d M Y H:i:s T",$lastUpdate);?>
<hr/>
<h2>Information</h2>
<a href="http://www.awesomenauts.com/">Awesomenauts</a> is awesome and is &copy; Ronimo Games.<br/>
This website was made by jyusan (Sue) in 2015. Code is publicly available at <a href="https://github.com/jyusan/awesome-charts">GitHub</a>, anyone is free to rehost/reuse/extend it.<br/>
More information/discussion available <a href="http://www.awesomenauts.com/forum/viewtopic.php?f=6&t=37307">at the official forums</a>.
	
</center>	
</body>

</html>