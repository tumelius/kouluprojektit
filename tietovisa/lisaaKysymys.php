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

$output = '';

$form = '
<p>Merkitse oikea vastaus tekstikentän lopussa olevalla radiobuttonilla</p>
<form action="" method="post">
	<ul>
		<li>
			<label>Kysymys</label><br>
			<input type="text" name="kysymys" value="Edellinen kysymys"/>
		</li>
		<li>
			<label>Pisteet</label><br>
			<input type="number" min="1" max="10" name="pisteet" />
		</li>
		<li>
			<label>Vastaus 1</label><br>
			<input type="text" name="vastaus1" />
			<input type="radio" name="oikein" value="0" />
		</li>
		<li>
			<label>Vastaus 2</label><br>
			<input type="text" name="vastaus2" />
			<input type="radio" name="oikein" value="1" />
		</li>
		<li>
			<label>Vastaus 3</label><br>
			<input type="text" name="vastaus3" />
			<input type="radio" name="oikein" value="2" />
		</li>
		<li>
			<label>Vastaus 4</label><br>
			<input type="text" name="vastaus4" />
			<input type="radio" name="oikein" value="3" />
		</li>
		<li>
			<label>Vastaus 5</label><br>
			<input type="text" name="vastaus5" />
			<input type="radio" name="oikein" value="4" />
		</li>
		<li>
			<input type="submit" name="btnLisaaKysymys" value="Lisää" />
		</li>
		
	</ul>
</form>';

if (empty($_POST)) {
	$output .= '<p>Lisää kysymys täyttämällä lomake.</p>';
	$output .= $form;	
} else {
	$output .= '<p>Kiitos kysymyksestä! 
				Lisäämme sen kunhan keksimme miten...</p>';
	
	$kysymys = $_POST['kysymys'];
	$pisteet = $_POST['pisteet'];
	$vastaus1 = $_POST['vastaus1'];
	$vastaus2 = $_POST['vastaus2'];
	$vastaus3 = $_POST['vastaus3'];
	$vastaus4 = $_POST['vastaus4'];
	$vastaus5 = $_POST['vastaus5'];
	$oikein = (isset($_POST['oikein'])) ? intval($_POST['oikein']) : ' kissa ';

	$errors = array();
	
	if (empty($kysymys)) $errors[] = 'Et antanut kysymystä';
	if (empty($pisteet)) $errors[] = 'Et antanut pisteitä';
	if (!is_numeric($oikein))  $errors[] = 'Et antanut oikeaa vastausta';
	if (empty($vastaus1) && empty($vastaus2)) $errors[] = 'Ainakin kaksi vastausvaihtoehtoa pitää antaa';
	
	if (!empty($errors)) {
		$output .= '
		<ul class="virheet">
			<li>' .
			implode('</li><li>',$errors)
			. '</li>
		</ul>
		';
		
		$output .= $form;
		
	} else {
		
		$xml = simplexml_load_file('tietovisa.xml');
		$uusiKysymys = $xml->addChild('taso');
		$uusiKysymys->addChild('kysymys', $kysymys);
		$uusiKysymys->addChild('vastaus', $vastaus1);
		$uusiKysymys->addChild('vastaus', $vastaus2);
		if (!empty($vastaus3)) $uusiKysymys->addChild('vastaus', $vastaus3);
		if (!empty($vastaus4)) $uusiKysymys->addChild('vastaus', $vastaus4);
		if (!empty($vastaus5)) $uusiKysymys->addChild('vastaus', $vastaus5);
		$uusiKysymys->addAttribute('pisteet', $pisteet);
		$uusiKysymys->vastaus[$oikein]->addAttribute('oikein','ok');
		
		// Muotoilun vuoksi
		$dom = new DOMDocument("1.0");
		$dom->preserveWhiteSpace = false;
		$dom->formatOutput = true;
		$dom->loadXML($xml->asXML());
		$dom->save('tietovisa.xml');
		
		$output .= '<p>Kysymys lisättiin...</p>';
		header('refresh:4;url=muokkaaVisaa.php');
	}
				
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
<h1>Kysymyksen lisäys</h1>
<div class="painikkeet">
	<a href="muokkaaVisaa.php">Takaisin päävalikkoon</a>
	<a href="index.php">Testaa visaa</a>
</div>
<?php
echo $output;
?>
</body>
</html>