<?php
// muokkaaKysymysAction.php

// Tarkistetaan onko käyttäjä kirjautunut
session_start();
if (!isset($_SESSION['user'])) {
	header('Content-Type: text/html; charset=utf-8');
	header('refresh:4;url=tietovisa.php');
	die('<h1>Riittämättömät oikeudet!</h1>
		<p>Sinulla ei ilmeisesti ole oikeuksia muokata tietovisaa. 
		Mene testaamaan tietojasi.</p>');
}

// ei päästetä muualta, kuin muokkaaKysymys.php-sivulta
if (isset($_SERVER['HTTP_REFERER']) 
	&&
	strpos($_SERVER['HTTP_REFERER'],'muokkaaKysymys.php') === false) {
	
	header('Content-Type: text/html; charset=utf-8');
	header('refresh:2;url=muokkaaVisaa.php');
	die('Tulit väärältä sivulta!');

}


// otetaan post-muuttujat talteen, niin niitä on helpompi käyttää
$id = intval($_POST['id']); 		// intval, koska tärkeää käyttää integer-arvoa
$kysymys = $_POST['kysymys'];
$pisteet = $_POST['pisteet'];
$oikein = intval($_POST['oikein']);	// intval, koska tärkeää käyttää integer-arvoa
$v1 = $_POST['vastaus1'];
$v2 = $_POST['vastaus2'];
$v3 = $_POST['vastaus3'];
$v4 = $_POST['vastaus4'];
$v5 = $_POST['vastaus5'];


$xml = simplexml_load_file('tietovisa.xml');
$xml->taso[$id]->kysymys = $kysymys;
$xml->taso[$id]->attributes()->pisteet = $pisteet;


$taso = $xml->taso[$id];

// tähän väliin vielä pitää miettiä, miten vastaukset käsitellään

// Poistetaan kaikki vanhat kysymykset
unset($taso->vastaus);

// Käydään läpi annetut vastaukset
$n = 0;
for ($i=0; $i<=4; $i++) {		
	$j = $i + 1; // $j -- kuinka mones vastauskenttä on kyseessä
		
	// jos vastausriville on kirjoitettu jotain ...
	if (!empty(${'v'.$j})) {
		// ... lisätään uusi vastaus-elementti
		$taso->vastaus[] = ${'v'.$j}; // $v1, $v2, $v3 jne.	
	}
}

// Vielä lisätään attribuutti oikealle vastaukselle
$taso->vastaus[$oikein]->addAttribute('oikein','ok');

// Lopuksi tallennetaan muutokset 
$dom = new DOMDocument("1.0");
$dom->preserveWhiteSpace = false;
$dom->formatOutput = true;
$dom->loadXML($xml->asXML());
$dom->save('tietovisa.xml');

header('Content-Type: text/html; charset=utf-8');
header('refresh:2;url=muokkaaVisaa.php');
echo 'Nyt pitäisi olla muutosten tallessa!';
