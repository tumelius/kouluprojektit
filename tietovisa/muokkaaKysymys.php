<?php
// Tarkistetaan onko käyttäjä kirjautunut
session_start();
if (!isset($_SESSION['user'])) {
	header('Content-Type: text/html; charset=utf-8');
	header('refresh:4;url=tietovisa.php');
	die('<h1>Riittämättömät oikeudet!</h1>
		<p>Sinulla ei ilmeisesti ole oikeuksia muokata tietovisaa. 
		Mene testaamaan tietojasi.</p>');
}

if ( empty($_GET) || !isset($_GET['id']) ) {
	header('Content-Type: text/html; charset=utf-8');
	header('refresh:4;url=muokkaaVisaa.php');
	die('<h1>Et valinnut muokattavaa kysymystä!</h1>
		<p>Ilmeisesti et tullut oikealta sivulta.</p>');
}

$id = intval($_GET['id']);
$xml = simplexml_load_file('tietovisa.xml');
$taso = $xml->taso[$id];

$output = '';

$form = '
<p>Merkitse oikea vastaus tekstikentän lopussa olevalla radiobuttonilla</p>
<form action="muokkaaKysymysAction.php" method="post">
	<input type="hidden" name="id" value="'.$id.'" />
	<ul>
		<li>
			<label>Kysymys</label><br>
			<input type="text" name="kysymys" value="'.$taso->kysymys.'"/>
		</li>
		<li>
			<label>Pisteet</label><br>
			<input type="number" min="1" max="10" name="pisteet" value="'.$taso->attributes()->pisteet.'"/>
		</li>';

for ($i=0; $i<5; $i++) {		
	$j = $i + 1;
	
	if ($taso->vastaus->count() >= $i+1)
		$chk = ( $taso->vastaus[$i]->attributes()->oikein == 'ok' ) ? ' checked ' : '';
	else 
		$chk = '';
	
	$form .= '
		<li>
			<label>Vastaus ' . $j . '</label><br>
			<input type="text" name="vastaus'.$j.'" value="'.$taso->vastaus[$i].'"/>
			<input type="radio" name="oikein" value="'.$i.'" '.$chk.'/>
		</li>';	
}

$form .= '		
		<li>
			<input type="submit" name="btnMuokkaaKysymys" value="Tallenna muutokset" />
		</li>
		
	</ul>
</form>';

$output .= $form;

/*
echo $taso->kysymys . '<br>';
echo 'Pisteet: ' . $taso->attributes()->pisteet . '<br>';

foreach ($taso->vastaus as $vastaus) {
	echo $vastaus . '<br>';
}
*/

// Näytä kysymys

// Muokataan sitä

// Tallennetaan muutokset

?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title></title>
<link rel="stylesheet" type="text/css" href="css/tietovisa_screen.css" />
</head>
<body>
<h1>Kysymyksen muokkaus</h1>
<div class="painikkeet">
	<a href="muokkaaVisaa.php">Takaisin päävalikkoon</a>
	<a href="tietovisa.php">Testaa visaa</a>
</div>
<?php
echo $output;
?>
</body>
</html>
