<?php

session_start();

include_once '../../../config/constantes.php';

if (!isset($_SESSION[SESSION])) {
	$_SESSION[SESSION] = array();
}
$_SESSION[SESSION]['recherche'] = array(
	'search' => $_POST['search'],
	'option' => 'suiv',
	'id_annonce' => 0
);

?>