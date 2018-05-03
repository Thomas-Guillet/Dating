<?php

session_start();

include_once '../../../config/constantes.php';
include_once CONNEXION;
include_once MODELS.'logs_referal.php';
include_once MODELS.'comptes_atouts.php';
include_once MODELS.'comptes_referal.php';

//On récupère les atouts
$sAtouts = $_POST['atouts'];
$aAtouts = explode(',', $sAtouts);
$iNbAtouts = count($aAtouts);

//On récupère l'id compte
$iIdCompte = $_SESSION[SESSION]['compte'];

//On récupère le compte referal
$aCompteReferal = getCompteReferalByIdCompte($iIdCompte);
//On récupère le nombre de points
$iNbPoints = $aCompteReferal['points'];
//On vérifie le nombre d'atouts
if ($iNbPoints >= ATOUT_1) {
	//On récupère le nombre d'atouts dispos
	if ($iNbPoints >= ATOUT_4) {
		$iNbAtoutsDispo = 4;
	} else if ($iNbPoints >= ATOUT_3) {
		$iNbAtoutsDispo = 3;
	} else if ($iNbPoints >= ATOUT_2) {
		$iNbAtoutsDispo = 2;
	} else {
		$iNbAtoutsDispo = 1;
	}
}
if ($iNbAtouts <= $iNbAtoutsDispo) {
	desactiverAtoutsByIdCompte($iIdCompte);
	foreach ($aAtouts as $iIdAtout) {
		//On ajoute l'atout
		insertAtout($iIdCompte, $iIdAtout);
	}
}

?>