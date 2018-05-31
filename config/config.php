<?php

//CONFIG DES CONSTANTES

if ($_SERVER['SERVER_NAME'] == 'localhost') {

	$sRoot = 'C:/wamp64/www/dating/';
	$sUrl = 'http://localhost/dating/';
	$sDossierLocal = 'dating/';

}else {

	$sRoot = '/';
	$sUrl = 'http://thomas-g.fr/';
	$sDossierLocal = '/';

}

define ('ROOT', $sRoot);
define ('URL', $sUrl);
define ('DOSSIER_LOCAL', $sDossierLocal);

?>
