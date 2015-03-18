<?php 
$string = file_get_contents("areadata.json");
$json_a = json_decode($string, true);
$csv = array_map('str_getcsv', file('summary.csv'));
unset($csv[0]);

foreach ($csv as $key => $value) {
	$code = $value[3];
	foreach ($json_a['areas'] as $key2 => $value2) {
		if($value2['areaCode'] == $code)
			$json_a['areas'][$key2]['emptySpace'] = $value[1];
	}
}

header('Content-type: application/json');
print_r(json_encode($json_a));
?>