<?php

include_once '../../../config/constantes.php';
include_once CONNEXION;
include_once MODELS.'comptes.php';
include_once MODELS.'logs_comptes_etats.php';
include_once MODELS.'mails.php';
include_once MODELS.'logs.php';
include_once MODELS.'annonces_postulants.php';

//On récupère l'id compte
$iIdCompte = $_POST['compte'];
//On active le compte
activerCompte($iIdCompte);
//On log l'activation du compte
insertLogActivation($iIdCompte);
//On récupère le compte
$aCompte = getComptebyId($iIdCompte);
//On récupère le mail
$aMail = getMailById($aCompte['id_mail']);
$sMail = stripslashes($aMail['mail']);

//On connecte
session_start();
if (!isset($_SESSION[SESSION])) {
	$_SESSION[SESSION] = array();
}
$_SESSION[SESSION]['connexion'] = true;
$_SESSION[SESSION]['mail'] = $sMail;
$_SESSION[SESSION]['compte'] = $iIdCompte;
$_SESSION[SESSION]['type'] = $aCompte['id_type_compte'];
setcookie("UprigsConnexion", $iIdCompte, time()+60*60*24*30, '/');
//On récupère l'ip
$sIP = $_SERVER['REMOTE_ADDR'];
//On log
insertLog($iIdCompte, $sIP);
//critère connexion
checkCritereConnexion($iIdCompte);

if ($aCompte['id_type_compte'] == TYPE_COMPTE_DEMANDEUR) {

	echo 'je-cherche-un-job/';

} else {
	echo 'tes-annonces/';
}

?>
