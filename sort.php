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

$string2 = file_get_contents("amsterdamRegion.geojson");
$json_a2 = json_decode($string2, true);
//print_r($json_a2['features']);
foreach ($json_a2['features'] as $key => $value) {
	$max['x'] = 0; $max['y'] = 0; $min['x'] = 100; $min['y'] = 100;
	//print_r($value);
	foreach ($value['geometry']['coordinates'] as $key2 => $value2) {
		foreach ($value2[0] as $key3 => $value3) {
			//print_r($value3);
			
			if($value3[0] < $min['y'])
				$min['y'] = $value3[0];
			else if($value3[1] < $min['x'])
				$min['x'] = $value3[1];

			if($value3[1] > $max['x'])
				$max['x'] = $value3[1];
			else if($value3[0] > $max['y'])
				$max['y'] = $value3[0];
		}
	}
	$center['x'] = $min['x'] + (($max['x'] - $min['x']) / 2);
	$center['y'] = $min['y'] + (($max['y'] - $min['y']) / 2);
	//print_r($value['properties']);
	foreach ($json_a['areas'] as $key4 => $value4) {
		if($value4['areaCode'] == $value['properties']['sdbc'])
			$json_a['areas'][$key4]['centerLatLng'] = $center;
	}
}

header('Content-type: application/json');
print_r(json_encode($json_a));
?>