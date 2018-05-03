<?php

session_start();

include_once '../../../config/constantes.php';

if (!isset($_SESSION[SESSION]['annonces_verif_dep'])) {
	$_SESSION[SESSION]['annonces_verif_dep'] = array();
}
$_SESSION[SESSION]['annonces_verif_dep'][$_POST['annonce']] = true;

?>