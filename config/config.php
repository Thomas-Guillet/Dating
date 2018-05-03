<?php

//CONFIG DES CONSTANTES

if ($_SERVER['SERVER_NAME'] == 'localhost') {

	$sRoot = 'C:/wamp64/www/dating/';
	$sUrl = 'http://localhost/dating/';
	$sDossierLocal = 'dating/';

//TODO : configurer avant mise en production
}

define ('ROOT', $sRoot);
define ('URL', $sUrl);
define ('DOSSIER_LOCAL', $sDossierLocal);

?>
