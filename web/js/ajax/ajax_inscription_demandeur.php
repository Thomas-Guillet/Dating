<?php

session_start();

include_once '../../../config/constantes.php';
include_once CONNEXION;
include_once MODELS.'mails.php';
include_once MODELS.'comptes.php';
include_once MODELS.'liste_cp_ville.php';
include_once MODELS.'comptes_criteres.php';
include_once MODELS.'logs.php';
include_once MODELS.'newsletters_mails.php';
include_once MODELS.'logs_inscriptions.php';
include_once MODELS.'annonces.php';
include_once MODELS.'annonces_types.php';
include_once MODELS.'annonces_durees.php';
include_once MODELS.'comptes_referal.php';
include_once MODELS.'comptes_preferences.php';
include_once MODELS.'annonces_postulants.php';
include_once MODELS.'logs_referal.php';
include_once MODELS.'annonces_criteres.php';
include_once MODELS.'compatibilites.php';
include_once MODELS.'liste_code_rome.php';
include_once MODELS.'liste_annonce_avantages_inconvenients.php';
include_once MODELS.'comptes_inconvenients.php';
include_once CONTROLLERS.'fonctions.php';
include_once CONTROLLERS.'algo.php';

$sPourcent = 'ok';
//On récupère la partie
$iPartie = $_POST['partie'];

switch ($iPartie) {
	case 0 :
		//On créé la session
		if (!isset($_SESSION[SESSION])) {
			$_SESSION[SESSION] = array();
		}
		$_SESSION[SESSION]['inscription'] = array();
		//On créé le log
		insertLogInscription();
		//On récupère le log
		$aLog = getLastLogInscription();
		$iIdLog = $aLog['id_log_inscription'];
		//On met en session
		$_SESSION[SESSION]['inscription']['log'] = $iIdLog;
		if ($_POST['code']) {
			$_SESSION[SESSION]['inscription']['annonce'] = $_POST['code'];
		}
		//On récupère la session
		$sSession = serialize($_SESSION[SESSION]['inscription']);
		//On log
		updateLogInscription($iIdLog, $iPartie, $sSession);
		$sPourcent = 'no';
	break;
	case 1 :
		//On récupère l'id log
		$iIdLog = $_SESSION[SESSION]['inscription']['log'];
		//On récupère la session
		$sSession = serialize($_SESSION[SESSION]['inscription']);
		//On log
		updateLogInscription($iIdLog, $iPartie, $sSession);
	break;
	case 2 :
		$_SESSION[SESSION]['inscription']['crit1'] = $_POST['btn'];
		//On récupère l'id log
		$iIdLog = $_SESSION[SESSION]['inscription']['log'];
		//On récupère la session
		$sSession = serialize($_SESSION[SESSION]['inscription']);
		//On log
		updateLogInscription($iIdLog, $iPartie, $sSession);
	break;
	case 3 :
		$_SESSION[SESSION]['inscription']['crit2'] = $_POST['btn'];
		//On récupère l'id log
		$iIdLog = $_SESSION[SESSION]['inscription']['log'];
		//On récupère la session
		$sSession = serialize($_SESSION[SESSION]['inscription']);
		//On log
		updateLogInscription($iIdLog, $iPartie, $sSession);
	break;
	case 4 :
		$_SESSION[SESSION]['inscription']['crit3'] = $_POST['btn'];
		//On récupère l'id log
		$iIdLog = $_SESSION[SESSION]['inscription']['log'];
		//On récupère la session
		$sSession = serialize($_SESSION[SESSION]['inscription']);
		//On log
		updateLogInscription($iIdLog, $iPartie, $sSession);
	break;
	case 5 :
		$_SESSION[SESSION]['inscription']['crit4'] = $_POST['btn'];
		//On récupère l'id log
		$iIdLog = $_SESSION[SESSION]['inscription']['log'];
		//On récupère la session
		$sSession = serialize($_SESSION[SESSION]['inscription']);
		//On log
		updateLogInscription($iIdLog, $iPartie, $sSession);
	break;
	case 6 :
		$_SESSION[SESSION]['inscription']['crit5'] = $_POST['btn'];
		//On récupère l'id log
		$iIdLog = $_SESSION[SESSION]['inscription']['log'];
		//On récupère la session
		$sSession = serialize($_SESSION[SESSION]['inscription']);
		//On log
		updateLogInscription($iIdLog, $iPartie, $sSession);
	break;
	case 7 :
		$aContrats = json_decode($_POST['contrats']);
		$aDurees = json_decode($_POST['durees']);
		$sContrats = '';
		$sDurees = '';
		foreach ($aContrats as $contrat) {
			$sContrats .= $contrat;
		}
		foreach ($aDurees as $duree) {
			$sDurees .= $duree;
		}
		$_SESSION[SESSION]['inscription']['sContratsPreferences'] = $sContrats;
		$_SESSION[SESSION]['inscription']['sDureesPreferences'] = $sDurees;
		$iIdLog = $_SESSION[SESSION]['inscription']['log'];
		$sSession = serialize($_SESSION[SESSION]['inscription']);
		updateLogInscription($iIdLog, $iPartie, $sSession);
	break;
	case 8 :
		$sHeures = $_POST['dispos'];
		$aDispos = str_split($sHeures, 24);
		$sJours = '';
		foreach ($aDispos as $aDispo) {
		  if ($aDispo == '000000000000000000000000') {
		    $sJours .= '0';
		  } else {
		    $sJours .= '1';
		  }
		}
		$_SESSION[SESSION]['inscription']['sJoursPreferences'] = $sJours;
		$_SESSION[SESSION]['inscription']['sHeuresPreferences'] = $sHeures;
		$iIdLog = $_SESSION[SESSION]['inscription']['log'];
		$sSession = serialize($_SESSION[SESSION]['inscription']);
		updateLogInscription($iIdLog, $iPartie, $sSession);
	break;
	case 9 :
		//Récupérer les expériences
		$_SESSION[SESSION]['inscription']['experiences'] = $_POST['experiences'];
		$iIdLog = $_SESSION[SESSION]['inscription']['log'];
		$sSession = serialize($_SESSION[SESSION]['inscription']);
		updateLogInscription($iIdLog, $iPartie, $sSession);
	break;
	case 10 :
		$sSpecificites = $_POST['specificites'];
		$aSpecificites = explode("-", $sSpecificites);

		$_SESSION[SESSION]['inscription']['specificites'] = $aSpecificites;
		//On récupère l'id log
		$iIdLog = $_SESSION[SESSION]['inscription']['log'];
		//On récupère la session
		$sSession = serialize($_SESSION[SESSION]['inscription']);
		//On log
		updateLogInscription($iIdLog, $iPartie, $sSession);
	break;
	case 11 :
		//On vérifie le code postal
		$aCP = getVilleByCP($_POST['cp']);
		if ($aCP) {
			$_SESSION[SESSION]['inscription']['cp'] = $_POST['cp'];
			//On récupère l'id log
			$iIdLog = $_SESSION[SESSION]['inscription']['log'];
			//On récupère la session
			$sSession = serialize($_SESSION[SESSION]['inscription']);
			//On log
			updateLogInscription($iIdLog, $iPartie, $sSession);
		} else {
			$iPartie = 101;
			$sPourcent = 'no';
		}
	break;
}

include_once '../../../config/dialog_inscription.php';

//On récupère le dialogue
$sDialog = $aDialog[$iPartie];
//On récupère les boutons
$sButtons = $aButtons[$iPartie];

switch ($iPartie) {
	case 10 :
		$iPartie = 9.5;
		break;
	case 11 :
		$iPartie = 10;
		break;
}

echo json_encode(array('partie' => $iPartie, 'dialog' => $sDialog, 'buttons' => $sButtons, 'pourcent' => $sPourcent));

?>
