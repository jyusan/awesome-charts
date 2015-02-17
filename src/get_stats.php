<?php
// Returns an array with as arr[character_id] = character_usage
function getstats($xml) {
	$stats = new SimpleXMLElement($xml);
	$stats_ret = array();
	foreach ($stats->entries->entry as $e) {
		$chid = hexdec(implode(array_reverse(str_split(str_split($e->details, 8)[6],2))));
		$stats_ret[$chid] = incrementElement($stats_ret,$chid);
	}

	return $stats_ret;
}

function incrementElement($array,$index) {
	$base = (array_key_exists($index,$array) ? $array[$index] : 0);
	return $base + 1;
}

?>