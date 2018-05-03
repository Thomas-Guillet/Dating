<?php

session_start();

include_once '../../../config/constantes.php';
include_once CONNEXION;
include_once MODELS.'comptes_permis.php';

//On récupère l'id compte
$iIdCompte = $_SESSION[SESSION]['compte'];

//On récupère le permis
$aPermis = getPermisByIdCompte($iIdCompte);
if ($aPermis) {
	//On modifie le permis
	updatePermis($iIdCompte, $_POST['permis']);
} else {
	//On ajoute le permis
	insertPermis($iIdCompte, $_POST['permis']);
}

if (!isset($_SESSION[SESSION]['postule'])) {
	$_SESSION[SESSION]['postule'] = array();
}
$_SESSION[SESSION]['postule']['permis'] = 'oui';

?>