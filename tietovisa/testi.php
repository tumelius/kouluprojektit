<?php
// sallitut arvot 0-4
$x = '';

if (!($x>=0 && $x<=4) || !is_numeric($x) ) 
	echo $x .' = Ei sallittu arvo!';
else 
	echo $x . ' = Sallittu arvo!';


?>