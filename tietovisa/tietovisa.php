<?php
$xml = simplexml_load_file('tietovisa.xml');
$nimi = $xml->nimi;
$tekijä = $xml->tekijä;
$pvm = $xml->pvm;
$kysMäärä = $xml->taso->count();
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Tietovisa: <?php echo $nimi; ?></title>
<style>
ul.vastaukset {
	list-style-type: none;
	margin: 5px; 
}
</style>
</head>
<body>
<h1 ><?php echo $nimi; ?></h1><pre>

Tekijä    : <?php echo $tekijä; ?><br>
Pvm       : <?php echo $pvm; ?><br>
Kysymyksiä: <?php echo $kysMäärä; ?>

</pre>

<nav>
	<a href="muokkaaVisaa.php">Muokkaa visaa</a>
</nav>

<form action="tarkistaVastaukset.php" method="get">
<?php

echo $xml->taso->kysymys[2];

$kysymysNro = 0;
foreach ($xml->taso as $taso){
	
	echo '<strong>' .$taso->kysymys . '</strong>' . PHP_EOL;
	
	echo '<ul class="vastaukset">' . PHP_EOL;
	foreach ($taso->vastaus as $vastaus) {
		//$style = ($vastaus->attributes() == 'ok') ? 'style="color:red"' : '';
		$style = '';
		echo "\t<li" . $style . '><input type="radio" name="'.$kysymysNro.'" value="'.$vastaus.'">' .$vastaus .'</li>' . PHP_EOL;
	
	}
	echo '</ul>' . PHP_EOL;
	$kysymysNro++;
}
?>
	<input type="submit" value="Tarkista vastaukset" />
</form>

</body>
</html>