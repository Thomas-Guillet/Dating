<?php

session_start();

include_once '../../../config/constantes.php';

if (!isset($_SESSION[SESSION]['annonces_verif_inconvenients'])) {
	$_SESSION[SESSION]['annonces_verif_inconvenients'] = array();
}
$_SESSION[SESSION]['annonces_verif_inconvenients'][$_POST['annonce']] = true;

?>