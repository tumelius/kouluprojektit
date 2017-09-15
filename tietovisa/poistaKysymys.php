<?php
// tarkista että käyttäjä on kirjautunut
session_start();
if (!isset($_SESSION['user'])) {
	header('Content-Type: text/html; charset=utf-8');
	header('refresh:4;url=tietovisa.php');
	die('<h1>Riittämättömät oikeudet!</h1>
		<p>Sinulla ei ilmeisesti ole oikeuksia muokata tietovisaa. 
		Mene testaamaan tietojasi.</p>');
}

// poista kysymys
$id = intval($_GET['id']);

$xml = simplexml_load_file('tietovisa.xml');
unset($xml->taso[$id]);

$dom = new DOMDocument("1.0");
$dom->preserveWhiteSpace = false;
$dom->formatOutput = true;
$dom->loadXML($xml->asXML());
$dom->save('tietovisa.xml');

// näytä jäljelle jääneet kysmykset
header('Location: muokkaaVisaa.php');

