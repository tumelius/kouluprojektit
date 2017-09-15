<?php
if (empty($_GET)) {
	$output = 'Et vastannut vielä kysymyksiin';
} else {
	$output = '<h2>Tarkistetaan vastaukset...</h2>' . PHP_EOL;
	$xml = simplexml_load_file('tietovisa.xml');
	
	$i=0;
	$pisteet = 0;
	$kokonaisPisteet = 0;
	foreach ($xml->taso as $taso) {
		$tasonPisteet = $taso->attributes();
		$kokonaisPisteet += $tasonPisteet;
		foreach ($taso->vastaus as $vastaus) {
			if ($vastaus->attributes() == 'ok') {
				$output .= $taso->kysymys  . '</strong> (' . $tasonPisteet . 'p.) ';
				if ($vastaus == $_GET[$i++]) {
					$pisteet = $pisteet + $tasonPisteet;
					$output .= 'vastasit oikein<br>' . PHP_EOL;
				} else 
					$output .= '<span style="color: red;">vastasit väärin</span><br>' . PHP_EOL;
			}
		}
	}
	
	$output .= "<p>Sait $pisteet / $kokonaisPisteet pistettä.</p>";
}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title></title>
</head>
<body>
<?php echo $output; ?>
</body>
</html>