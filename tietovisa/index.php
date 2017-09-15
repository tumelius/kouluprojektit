<?php
$xml = simplexml_load_file('tietovisa.xml');
$nimi = $xml->nimi;
$tekijä = $xml->tekijä;
$pvm = $xml->pvm;
$kysMäärä = $xml->taso->count();
$page_title = 'Tietovisa: '. $nimi;

$output = '';

$output .= 
'<dl>
	<dt>Tekijä:</dt>
		<dd>'. $tekijä .'</dd>
	<dt>Pvm:</dt>
	<dd>'. $pvm . '</dd>
	<dt>Kysymyksiä:</dt>
	<dd>'. $kysMäärä .'</dd>	
</dl>';

$form = '<form action="tarkistaVastaukset.php" method="get">';
$kysymysNro = 0;
foreach ($xml->taso as $taso){
	
	$form .= '
		<strong>' .$taso->kysymys . '</strong>
		<ul class="vastaukset">' . PHP_EOL;
	
	foreach ($taso->vastaus as $vastaus) {
		$style = '';
		$form .= "\t<li" . $style . '><input type="radio" name="'.$kysymysNro.'" value="'.$vastaus.'">' .$vastaus .'</li>' . PHP_EOL;
	
	}
	$form .= '</ul>' . PHP_EOL;
	$kysymysNro++;
}
$form .= '
		<input type="submit" value="Tarkista vastaukset" />
	</form>';
	
$output .= $form;
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title><?php echo $page_title; ?></title>
<link rel="stylesheet" type="text/css" href="css/tietovisa_screen.css" />
</head>
<body>
<h1><?php echo $nimi; ?></h1>
<nav class="painikkeet">
	<a href="muokkaaVisaa.php">Muokkaa visaa</a>
</nav>

<?php
echo $output;
?>

</body>
</html>