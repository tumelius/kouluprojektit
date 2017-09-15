<?php
session_start();
include 'autentikoi.php';

if ( autentikoiBasic() === false ) {
	header("refresh:2;url=tietovisa.php");
	die("<h1 style=\"color: red;\">Pääsy evätty! Mene pois!</h1>");
}

$output = '<p>Tervetuloa '.$_SESSION['user'].' muokkaamaan tietokilpailun kysymyksiä.</p>';

$xml = simplexml_load_file('tietovisa.xml');

$i = 0; 
foreach ($xml->taso as $taso){
	$output .= '<div class="kysymys">
					<div class="painikkeet">
						<a href="muokkaaKysymys.php?id='.$i.'">Muokkaa</a>
						<a href="poistaKysymys.php?id='.$i.'">Poista</a>
					</div>
					<strong>' . ++$i . ': ' . $taso->kysymys . '</strong>
					<ul>';
	
	foreach ($taso->vastaus as $vastaus) {
		$o = ($vastaus->attributes()->oikein == 'ok') ? ' (oikea vastaus) ':'' ;
		$output .= '
			<li>'.$vastaus.$o.'</li>
		';
	}
	$output .= '</ul>
				</div>';
				
	
}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title></title>
<link rel="stylesheet" type="text/css" href="css/tietovisa_screen.css" />
</head>
<body>
<h1>Tietovisan muokkaustila</h1>
<div class="painikkeet">
	<a href="lisaaKysymys.php">Lisää uusi kysymys</a>
	<a href="index.php">Testaa visaa</a>
</div>
<?php
echo $output;
?>
</body>
</html>