<?php

include_once '../../../config/constantes.php';
include_once CONNEXION;
include_once MODELS.'mails.php';
include_once MODELS.'comptes.php';
include_once MODELS.'logs.php';
include_once MODELS.'annonces_postulants.php';
include_once CONTROLLERS.'fonctions.php';

//On récupère le mail
$aMail = getMailByMail($_POST['mail']);
if ($aMail) {
	//On récupère le compte
	$aCompte = getComptebyIdMail($aMail['id_mail']);
	if ($aCompte) {
		//On vérifie le mot de passe
		$sPassword = substr($_POST['password'], 0, GDS_POS).GDS.substr($_POST['password'], GDS_POS, strlen($_POST['password']) - GDS_POS);
		$sPassword = md5($sPassword);
		if ($sPassword == $aCompte['password_compte']) {
			//On vérifie l'état du compte
			if ($aCompte['etat_compte']) {
				//On connecte
				session_start();
				if (!isset($_SESSION[SESSION])) {
					$_SESSION[SESSION] = array();
				}
				$_SESSION[SESSION]['connexion'] = true;
				$_SESSION[SESSION]['mail'] = $_POST['mail'];
				$_SESSION[SESSION]['compte'] = $aCompte['id_compte'];
				$_SESSION[SESSION]['type'] = $aCompte['id_type_compte'];
				setcookie("UprigsConnexion", $aCompte['id_compte'], time()+60*60*24*30, '/');
				//On récupère l'ip
				$sIP = $_SERVER['REMOTE_ADDR'];
				//On log
				insertLog($aCompte['id_compte'], $sIP);

				//critère connexion
				checkCritereConnexion($aCompte['id_compte']);

				if ($aCompte['id_type_compte'] == TYPE_COMPTE_DEMANDEUR) {

					echo json_encode(array('etat' => 'success', 'url' => 'je-cherche-un-job/'));

				} else {
					echo json_encode(array('etat' => 'success', 'url' => 'tes-annonces/'));
				}
			} else {
				echo json_encode(array('etat' => 'desactiver', 'compte' => $aCompte['id_compte']));
			}
		} else {
			echo json_encode(array('etat' => 'error', 'texte' => 'Mot de passe incorrect'));
		}
	} else {
		echo json_encode(array('etat' => 'error', 'texte' => 'Aucun compte ne correspond à cet email'));
	}
} else {
	echo json_encode(array('etat' => 'error', 'texte' => 'Uprigs ne connait pas cet email'));
}

?>
