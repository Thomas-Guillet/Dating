<?php

session_start();

include_once '../../../config/constantes.php';
include_once CONNEXION;
include_once MODELS.'logs_depot_annonce.php';
include_once MODELS.'mails.php';
include_once MODELS.'comptes.php';
include_once MODELS.'logs.php';
include_once MODELS.'comptes_entreprises.php';
include_once MODELS.'annonces_types.php';
include_once MODELS.'annonces_durees.php';
include_once MODELS.'liste_cp_ville.php';
include_once MODELS.'annonces.php';
include_once MODELS.'annonces_criteres.php';
include_once MODELS.'newsletters_mails.php';
include_once MODELS.'users.php';
include_once MODELS.'comptes_criteres.php';
include_once MODELS.'compatibilites.php';
include_once MODELS.'liste_annonce_avantages_inconvenients.php';
include_once CONTROLLERS.'fonctions.php';
include_once CONTROLLERS.'algo.php';

function logDepotAnnonce($iPartie) {
	//On récupère l'id log
	$iIdLog = $_SESSION[SESSION]['depot']['log'];
	//On récupère la session
	$sSession = serialize($_SESSION[SESSION]['depot']);
	//On log
	updateLogDepot($iIdLog, $iPartie, $sSession);
}

function mailFirstAnnonce($iIdMail, $sMail) {
	$iIdNewsletter = 46;
	//On génère le code mailing
	$sCodeMailing = genererCodeMailing();
	$sObjet = 'Première annonce envoyée à l\'équipe Uprigs. Prêts à dénicher la perle rare ?';
	$sMessage = '
	<!DOCTYPE html>
	<html lang="fr">
	<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>'.$sObjet.'</title>

	<style>

	html,
	body {
		margin: 0 auto !important;
		padding: 0 !important;
		height: 100% !important;
		width: 100% !important;
		font-family: "Open Sans",sans-serif;
	}

	* {
		-ms-text-size-adjust: 100%;
		-webkit-text-size-adjust: 100%;
	}

	div[style*="margin: 16px 0"] {
		margin:0 !important;
	}

	table,
	td {
		mso-table-lspace: 0pt !important;
		mso-table-rspace: 0pt !important;
	}

	table {
		border-spacing: 0 !important;
		border-collapse: collapse !important;
		table-layout: fixed !important;
		margin: 0 auto !important;
	}
	table table table {
		table-layout: auto;
	}

	img {
		-ms-interpolation-mode:bicubic;
	}

	.mobile-link--footer a,
	a[x-apple-data-detectors] {
		color:inherit !important;
		text-decoration: underline !important;
	}

	</style>

	<style>

	.button-td,
	.button-a {
		transition: all 100ms ease-in;
	}
	.button-td:hover,
	.button-a:hover {
		background: #007CFF !important;
		border-color: #007CFF !important;
	}

	@media screen and (max-width: 480px) {

		.fluid,
		.fluid-centered {
			width: 100% !important;
			max-width: 100% !important;
			height: auto !important;
			margin-left: auto !important;
			margin-right: auto !important;
		}
		.fluid-centered {
			margin-left: auto !important;
			margin-right: auto !important;
		}

		.stack-column,
		.stack-column-center {
			display: block !important;
			width: 100% !important;
			max-width: 100% !important;
			direction: ltr !important;
		}
		.stack-column-center {
			text-align: center !important;
		}

		.center-on-narrow {
			text-align: center !important;
			display: block !important;
			margin-left: auto !important;
			margin-right: auto !important;
			float: none !important;
		}
		table.center-on-narrow {
			display: inline-block !important;
		}

	}

	</style>

	</head>
	<body width="100%" bgcolor="lightgray" style="margin: 0;">
	<center style="width: 100%; background: lightgray;">

	<div style="max-width: 680px; margin: auto;">

	<table role="presentation" cellspacing="0" cellpadding="0" border="0" align="center" width="100%" style="max-width: 680px;">
	<tr>
	<td style="padding: 20px 0; text-align: center; color: #007CFF; font-size: 30px;">
	<b>Uprigs</b>
	</td>
	</tr>
	</table>

	<table role="presentation" cellspacing="0" cellpadding="0" border="0" align="center" width="100%" style="max-width: 680px;">

	<tr>
	<td bgcolor="#ffffff">
	<table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
	<tr>
	<td style="padding: 40px; padding-bottom: 0px; text-align: center; font-size: 15px; mso-height-rule: exactly; line-height: 35px; color: #555555;">
	<p>
	<span style="color:#007CFF;font-size:30px;">
	<b>Première annonce envoyée<br />
	à l\'équipe Uprigs.</b>
	</span>
	<br />
	<span style="font-size:25px; color:dimgray;">
	Prêts à dénicher la perle rare ?
	</span>
	</p>
	</td>
	</tr>
	</table>
	</td>
	</tr>

	<tr>
	<td bgcolor="#ffffff">
	<table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
	<tr>
	<td style="padding: 40px; padding-top:0px; text-align: center; font-size: 20px; mso-height-rule: exactly; line-height: 25px; color: dimgray;">
	<p>
	Félicitations pour votre première annonce,<br />
	mais ce n\'est qu\'un premier pas !
	</p>
	<p>
	<span style="color:#0028C9;">Uprigs</span> va rapidement évaluer la pertinence de votre annonce,<br />
	pour ensuite la proposer à nos nombreux profils compatibles.
	</p>
	<p>
	En parlant de compatibilité, vous saviez qu\'<span style="color:#0028C9;">Uprigs</span><br />
	tri pour vous les candidats les plus pertinents ?
	</p>
	<p>
	Fini le temps perdu à recevoir des candidats incompatibles,<br />
	nous ne vous proposons que des profils<br />
	capables de s\'épanouir sur le poste que vous proposez.
	</p>
	<p>
	Dès que votre annonce sera validée,<br />
	vous recevrez un email avec les détails.
	</p>
	<p>Merci pour votre confiance.</p>
	<p>A tout de suite sur <a href="'.URL.'tes-annonces/'.$iIdMail.'/'.$sCodeMailing.'/?ref=em_auto_'.$iIdNewsletter.'_1" title="Uprigs" style="color:#007CFF;">Uprigs.com</a>,</p>
	<p>Pascal, Co-fondateur et CEO chez <span style="color:#0028C9;">Uprigs</span>.</p>
	</td>
	</tr>
	</table>
	</td>
	</tr>

	</table>

	<table role="presentation" cellspacing="0" cellpadding="0" border="0" align="center" width="100%" style="max-width: 680px;">
	<tr>
	<td style="padding: 20px 10px;width: 100%;font-size: 12px; mso-height-rule: exactly; line-height:18px; text-align: center; color: dimgray;">
	Ce message est envoyé automatiquement. Veuillez ne pas y répondre.<br />
	Si vous souhaitez ne plus recevoir de message, <a href="'.URL.'ne-plus-vouloir-recevoir-la-newsletter/'.$iIdMail.'/'.$sCodeMailing.'/?ref=em_auto_'.$iIdNewsletter.'_2" style="color:dimgray;">cliquez ici</a>.
	<br><br>
	</td>
	</tr>
	</table>

	</div>
	</center>
	<img src="'.URL.'controllers/tracking_ouverture_mail.php?idmail='.$iIdMail.'&codemailing='.$sCodeMailing.'" />
	</body>
	</html>
	';
	$sHeaders  = 'MIME-Version: 1.0' . "\r\n";
	$sHeaders .= 'Content-type: text/html; charset=utf-8' . "\r\n";
	$sHeaders .= 'From: Uprigs <info@uprigs.com>' . "\r\n";
	if (mail($sMail, $sObjet, $sMessage, $sHeaders)) {
		//On enregistre l'envoi du mail
		insertNewsletterMailCode($iIdNewsletter, $iIdMail, $sCodeMailing);
	}
	//echo $sMessage;
}

function mailNewAnnonceInValidation() {
	$sMail1 = 'pascal@uprigs.com';
	$sMail2 = 'agathe@uprigs.com';
	$sObjet = 'Uprigs - Nouvelle annonce';
	$sMessage = '
	<div style="text-align:center; background-color:#007CFF; color:white; font-size:30px; padding-top:30px;">
	<b>Uprigs</b><br />
	Tu as une nouvelle annonce en attente de validation.<br /><br />
	</div>
	';
	$sHeaders  = 'MIME-Version: 1.0' . "\r\n";
	$sHeaders .= 'Content-type: text/html; charset=utf-8' . "\r\n";
	$sHeaders .= 'From: Uprigs <info@uprigs.com>' . "\r\n";
	mail($sMail1, $sObjet, $sMessage, $sHeaders);
	mail($sMail2, $sObjet, $sMessage, $sHeaders);
	//echo $sMessage;
}

function createAnnonce($iPartie) {
	//On récupère la session
	$sMail = $_SESSION[SESSION]['depot']['mail'];
	$iIdCompte = $_SESSION[SESSION]['depot']['compte'];
	$sEntreprise = $_SESSION[SESSION]['depot']['entreprise'];
	$sTitre = $_SESSION[SESSION]['depot']['titre'];
	$iIdRome = $_SESSION[SESSION]['depot']['idrome'];
	$aTaches = $_SESSION[SESSION]['depot']['taches'];
	$iCrit1 = $_SESSION[SESSION]['depot']['crit1'];
	$iCrit2 = $_SESSION[SESSION]['depot']['crit2'];
	$iCrit3 = $_SESSION[SESSION]['depot']['crit3'];
	$iCrit4 = $_SESSION[SESSION]['depot']['crit4'];
	$iCrit5 = $_SESSION[SESSION]['depot']['crit5'];
	$iContrat = $_SESSION[SESSION]['depot']['contrat'];
	$iDuree = $_SESSION[SESSION]['depot']['duree'];
	$sContratDuree = NULL;
	if ($iContrat != 2) {
		$sContratDuree = $_SESSION[SESSION]['depot']['contrat_duree'];
	}
	$sCP = $_SESSION[SESSION]['depot']['cp'];
	$iVille = $_SESSION[SESSION]['depot']['ville'];
	$sPermis = 'non';
	if (isset($_SESSION[SESSION]['depot']['permis'])) {
		$sPermis = $_SESSION[SESSION]['depot']['permis'];
	}
	$sHorairesJours = NULL;
	$sHorairesHeures = NULL;
	if (isset($_SESSION[SESSION]['depot']['horaires'])) {
		if (isset($_SESSION[SESSION]['depot']['horaires']['jours'])) {
			$aHorairesJours = explode(',', $_SESSION[SESSION]['depot']['horaires']['jours']);
			$sHorairesJours = serialize($aHorairesJours);
		}
		if (isset($_SESSION[SESSION]['depot']['horaires']['time_debut']) && isset($_SESSION[SESSION]['depot']['horaires']['time_fin'])) {
			$aHorairesHeures[] = $_SESSION[SESSION]['depot']['horaires']['time_debut'];
			$aHorairesHeures[] = $_SESSION[SESSION]['depot']['horaires']['time_fin'];
			$sHorairesHeures = serialize($aHorairesHeures);
		}
	}
	$sDescription = NULL;
	if (isset($_SESSION[SESSION]['depot']['description'])) {
		$sDescription = $_SESSION[SESSION]['depot']['description'];
	}
	$dDateDebut = NULL;
	if (isset($_SESSION[SESSION]['depot']['date_debut'])) {
		$dDateDebut = $_SESSION[SESSION]['depot']['date_debut'];
	}
	$sIdsAvantages = NULL;
	if (isset($_SESSION[SESSION]['depot']['avantages'])) {
		$aIdsAvantages = explode(',', $_SESSION[SESSION]['depot']['avantages']);
		$sIdsAvantages = serialize($aIdsAvantages);
	}
	$sIdsInconvenients = NULL;
	if (isset($_SESSION[SESSION]['depot']['inconvenients'])) {
		$aIdsInconvenients = explode(',', $_SESSION[SESSION]['depot']['inconvenients']);
		$sIdsInconvenients = serialize($aIdsInconvenients);
	}
	//On génère le code annonce
	$sCode = genererCodeAnnonce($iIdCompte);
	//On vérifie que le code est unique
	while (getAnnonceByCode($sCode)) {
		$sCode = genererCodeAnnonce($iIdCompte);
	}
	//On récupère la ville
	$aVille = getVilleById($iVille);
	$sVille = stripslashes($aVille['ville']);
	//On récupère les tâches
	$sTaches = serialize($aTaches);
	//On récupère l'ip
	$sIP = $_SERVER['REMOTE_ADDR'];

	addAnnonce($sCode, $iIdCompte, $sEntreprise, $sCP, $sVille, $sTitre, $iIdRome, $sTaches, $iContrat, $sContratDuree, $sPermis, $iDuree, $sHorairesJours, $sHorairesHeures, $sDescription, $dDateDebut, $sIdsAvantages, $sIdsInconvenients, $sIP);

	//On récupère l'annonce
	$aAnnonce = getAnnonceByCode($sCode);
	//On ajoute les critères
	addCriteres($aAnnonce['id_annonce'], $iCrit1, $iCrit2, $iCrit3, $iCrit4, $iCrit5);
	//On récupère l'id log
	$iIdLog = $_SESSION[SESSION]['depot']['log'];
	//On log
	updateEtatLogDepotByIdLog($iIdLog, $iPartie);
	//On supprime la session
	$_SESSION[SESSION]['depot'] = NULL;

	//On récupère le nombre d'annonces du recruteur
	$aNbAnnonces = getNbAnnoncesByIdCompte($iIdCompte);
	if ($aNbAnnonces['nb_annonces'] == 1) {
		//On récupère l'id mail
		$aIdMail = getMailByMail($sMail);
		$iIdMail = $aIdMail['id_mail'];
		mailFirstAnnonce($iIdMail, $sMail);
	}
	mailNewAnnonceInValidation();

}

$sPourcent = 'ok';

//On récupère la partie
$iPartie = $_POST['partie'];

switch ($iPartie) {
	case 0 :
	$bDisplay = true;
	//On vérifie le type user
	if (isset($_SESSION[SESSION]['connexion']) && $_SESSION[SESSION]['connexion']) {
		if ($_SESSION[SESSION]['type'] == TYPE_COMPTE_DEMANDEUR) {
			$bDisplay = false;
		}
	}
	if ($bDisplay) {
		//On créé la session
		if (!isset($_SESSION[SESSION])) {
			$_SESSION[SESSION] = array();
		}
		$_SESSION[SESSION]['depot'] = array();
		//On créé le log
		insertLogDepot();
		//On récupère le log
		$aLog = getLastLogDepot();
		$iIdLog = $aLog['id_log_depot'];
		//On met en session
		$_SESSION[SESSION]['depot']['log'] = $iIdLog;
		//On log
		logDepotAnnonce($iPartie);
		$sPourcent = 'no';
	} else {
		$iPartie = 404;
		$sPourcent = 'no';
	}
	break;
	case 1 :
	//On récupère l'id log
	$iIdLog = $_SESSION[SESSION]['depot']['log'];
	//On test la connexion
	if (isset($_SESSION[SESSION]['connexion']) && $_SESSION[SESSION]['connexion']) {
		//On récupère l'id compte
		$iIdCompte = $_SESSION[SESSION]['compte'];
		//On récupère le compte
		$aCompte = getComptebyId($iIdCompte);
		$iIdMail = $aCompte['id_mail'];
		//On récupère le mail
		$aMail = getMailById($iIdMail);
		$sMail = stripslashes($aMail['mail']);
		$_SESSION[SESSION]['depot']['mail'] = $sMail;
		$_SESSION[SESSION]['depot']['compte'] = $iIdCompte;
		//On log l'id mail
		updateIdMailByIdLogDepot($iIdLog, $iIdMail);
		$iPartie = 3;
	}
	//On log
	logDepotAnnonce($iPartie);
	break;
	case 2 :
	//On vérifie le mail
	if (verifMail($_POST['mail'])) {
		$_SESSION[SESSION]['depot']['mail'] = $_POST['mail'];
		//On récupère le mail
		$aMail = getMailByMail($_POST['mail']);
		if ($aMail) {
			$iIdMail = $aMail['id_mail'];
			//On récupère le compte
			$aCompte = getComptebyIdMail($iIdMail);
			if ($aCompte) {
				//On vérifie que c'est un recruteur
				if ($aCompte['id_type_compte'] == TYPE_COMPTE_RECRUTEUR) {
					$iPartie = 2;
					$_SESSION[SESSION]['depot']['compte'] = $aCompte['id_compte'];
				} else {
					$iPartie = 404;
					$sPourcent = 'no';
				}
			} else {
				$iPartie = 3;
			}
		} else {
			//On ajoute le mail
			insertMail($_POST['mail'], TYPE_COMPTE_RECRUTEUR);
			//On récupère le mail
			$aNewMail = getMailByMail($_POST['mail']);
			$iIdMail = $aNewMail['id_mail'];
			$iPartie = 3;
		}
		//On récupère l'id log
		$iIdLog = $_SESSION[SESSION]['depot']['log'];
		//On log l'id mail
		updateIdMailByIdLogDepot($iIdLog, $iIdMail);
		//On log
		logDepotAnnonce($iPartie);
	} else {
		$iPartie = 101;
		$sPourcent = 'no';
	}
	break;
	case 3 :
	//On crypte le password
	$sPassword = substr($_POST['password'], 0, GDS_POS).GDS.substr($_POST['password'], GDS_POS, strlen($_POST['password']) - GDS_POS);
	$sPassword = md5($sPassword);
	//On récupère le compte
	$iIdCompte = $_SESSION[SESSION]['depot']['compte'];
	$aCompte = getComptebyId($iIdCompte);
	if ($aCompte['password_compte'] == $sPassword) {
		//On connecte
		$_SESSION[SESSION]['connexion'] = true;
		$_SESSION[SESSION]['mail'] = $_SESSION[SESSION]['depot']['mail'];
		$_SESSION[SESSION]['compte'] = $iIdCompte;
		$_SESSION[SESSION]['type'] = $aCompte['id_type_compte'];
		setcookie("UprigsConnexion", $iIdCompte, time()+60*60*24*30, '/');
		//On récupère l'ip
		$sIP = $_SERVER['REMOTE_ADDR'];
		//On log
		insertLog($iIdCompte, $sIP);
		//On log
		logDepotAnnonce($iPartie);
	} else {
		$iPartie = 21;
		$sPourcent = 'no';
	}
	break;
	case 4 :
	//On récupère l'entreprise
	$sEntreprise = ucfirst($_POST['entreprise']);
	$_SESSION[SESSION]['depot']['entreprise'] = $sEntreprise;
	//On log
	logDepotAnnonce($iPartie);
	break;
	case 5 :
	//On récupère le titre du job
	$sTitre = ucfirst($_POST['titre']);
	$iRomeId = ucfirst($_POST['idrome']);

	$_SESSION[SESSION]['depot']['titre'] = $sTitre;
	$_SESSION[SESSION]['depot']['idrome'] = $iRomeId;
	//On log
	logDepotAnnonce($iPartie);
	break;
	case 6 :
	//On récupère la tache du job
	$sTache  = ucfirst($_POST['tache']);
	if (!isset($_SESSION[SESSION]['depot']['taches'])) {
		$_SESSION[SESSION]['depot']['taches'] = array();
	}
	$_SESSION[SESSION]['depot']['taches'][] = $sTache;
	//On log
	logDepotAnnonce($iPartie);
	break;
	case 7 :
	//On log
	logDepotAnnonce($iPartie);
	break;
	case 8 :
	$_SESSION[SESSION]['depot']['crit1'] = $_POST['btn'];
	//On log
	logDepotAnnonce($iPartie);
	break;
	case 9 :
	$_SESSION[SESSION]['depot']['crit2'] = $_POST['btn'];
	//On log
	logDepotAnnonce($iPartie);
	break;
	case 10 :
	$_SESSION[SESSION]['depot']['crit3'] = $_POST['btn'];
	//On log
	logDepotAnnonce($iPartie);
	break;
	case 11 :
	$_SESSION[SESSION]['depot']['crit4'] = $_POST['btn'];
	//On log
	logDepotAnnonce($iPartie);
	break;
	case 12 :
	$_SESSION[SESSION]['depot']['crit5'] = $_POST['btn'];
	//On log
	logDepotAnnonce($iPartie);
	break;
	case 13 :
	$iType = $_POST['type'];
	$_SESSION[SESSION]['depot']['contrat'] = $iType;
	if ($iType != 2) {
		//On récupère le nombre
		$iNumber = $_POST['number'];
		$sDuree = $_POST['duree'];
		if (($iNumber > 1) && ($sDuree != 'mois')) {
			$sDuree .= 's';
		}
		$_SESSION[SESSION]['depot']['contrat_duree'] = $iNumber.' '.$sDuree;
	}
	//On log
	logDepotAnnonce($iPartie);
	break;
	case 14 :
	$_SESSION[SESSION]['depot']['duree'] = $_POST['duree'];
	//On log
	logDepotAnnonce($iPartie);
	break;
	case 15 :
	//On vérifie le code postal
	$aCP = getVilleByCP($_POST['cp']);
	if ($aCP) {
		$_SESSION[SESSION]['depot']['cp'] = $_POST['cp'];
		//On log
		logDepotAnnonce($iPartie);
	} else {
		$iPartie = 141;
		$sPourcent = 'no';
	}
	break;
	case 16 :
	$_SESSION[SESSION]['depot']['ville'] = $_POST['ville'];
	//On log
	logDepotAnnonce($iPartie);
	break;
	case 17 :
	$_SESSION[SESSION]['depot']['permis'] = $_POST['permis'];
	if (isset($_POST['jours'])) {
		$_SESSION[SESSION]['depot']['horaires'] = array();
		$_SESSION[SESSION]['depot']['horaires']['jours'] = $_POST['jours'];
	}
	if (isset($_POST['timeDebut']) && isset($_POST['timeFin'])) {
		$_SESSION[SESSION]['depot']['horaires']['time_debut'] = $_POST['timeDebut'];
		$_SESSION[SESSION]['depot']['horaires']['time_fin'] = $_POST['timeFin'];
	}
	if (isset($_POST['description'])) {
		$_SESSION[SESSION]['depot']['description'] = $_POST['description'];
	}
	if (isset($_POST['dateDebut']) && (date('Y-m-d', strtotime($_POST['dateDebut'])) >= date('Y-m-d'))) {
		$_SESSION[SESSION]['depot']['date_debut'] = date('Y-m-d', strtotime($_POST['dateDebut']));
	}
	if (isset($_POST['avantages'])) {
		$_SESSION[SESSION]['depot']['avantages'] = $_POST['avantages'];
	}
	if (isset($_POST['inconvenients'])) {
		$_SESSION[SESSION]['depot']['inconvenients'] = $_POST['inconvenients'];
	}
	if (isset($_SESSION[SESSION]['connexion']) && $_SESSION[SESSION]['connexion']) {
		createAnnonce($iPartie);
	} else {
		$iPartie = 18;
	}
	break;
	case 19 :
	if (isset($_POST['nom'])) {
		//On récupère le nom et le prénom
		$_SESSION[SESSION]['depot']['nom_contact'] = $_POST['nom'];
		$_SESSION[SESSION]['depot']['prenom_contact'] = $_POST['prenom'];
	}
	//On vérifie le numéro de téléphone
	$sTel = preg_replace("#[^0-9a-zA-Z]#", "", $_POST['tel']);
	if (verifTel($sTel)) {
		$_SESSION[SESSION]['depot']['tel'] = $sTel;
		//On log
		logDepotAnnonce($iPartie);
	} else {
		$iPartie = 181;
		$sPourcent = 'no';
	}
	break;
	case 20 :
	//On vérifie le password
	if (strlen($_POST['password']) >= MDP_LENGHT_MIN) {
		$bErreur = false;
		//On crypte le password
		$sPassword = substr($_POST['password'], 0, GDS_POS).GDS.substr($_POST['password'], GDS_POS, strlen($_POST['password']) - GDS_POS);
		$sPassword = md5($sPassword);
		//On récupère la session
		$sMail = $_SESSION[SESSION]['depot']['mail'];
		$sCP = $_SESSION[SESSION]['depot']['cp'];
		$sTel = $_SESSION[SESSION]['depot']['tel'];
		$sEntreprise = $_SESSION[SESSION]['depot']['entreprise'];
		$sNomContact = $_SESSION[SESSION]['depot']['nom_contact'];
		$sPrenomContact = $_SESSION[SESSION]['depot']['prenom_contact'];
		//On récupère le mail
		$aMail = getMailByMail($sMail);
		if ($aMail) {
			$iIdMail = $aMail['id_mail'];
			$iIdTypeMail = $aMail['id_type_mail'];
			//On récupère le compte
			$aCompte = getComptebyIdMail($iIdMail);
			if ($aCompte) {
				$bErreur = true;
			} else {
				//On ajoute le compte
				insertCompte($sCP, $sTel, $iIdMail, $sPassword, TYPE_COMPTE_RECRUTEUR);
				//On récupère le compte
				$aNewCompte = getComptebyIdMail($iIdMail);
				$iIdCompte = $aNewCompte['id_compte'];
				$iIdTypeCompte = $aNewCompte['id_type_compte'];
				if ($iIdTypeMail != TYPE_COMPTE_RECRUTEUR) {
					//On modifie le type mail
					updateTypeMail($iIdMail, TYPE_COMPTE_RECRUTEUR);
				}
				$_SESSION[SESSION]['depot']['compte'] = $iIdCompte;
				//On ajoute les infos entreprise
				insertEntreprise($iIdCompte, $sEntreprise, $sNomContact, $sPrenomContact);
				//On connecte
				$_SESSION[SESSION]['connexion'] = true;
				$_SESSION[SESSION]['mail'] = $sMail;
				$_SESSION[SESSION]['compte'] = $iIdCompte;
				$_SESSION[SESSION]['type'] = $iIdTypeCompte;
				setcookie("UprigsConnexion", $iIdCompte, time()+60*60*24*30, '/');
				//On récupère l'ip
				$sIP = $_SERVER['REMOTE_ADDR'];
				//On log
				insertLog($iIdCompte, $sIP);
				//Tracking Pascal -> header_no_ref
				$_SESSION[SESSION]['tracking'] = array();
				$_SESSION[SESSION]['tracking']['first_annonce'] = true;
			}
		} else {
			$bErreur = true;
		}
		if (!$bErreur) {
			createAnnonce($iPartie);
		} else {
			$iPartie = 200;
			$sPourcent = 'no';
		}
	} else {
		$iPartie = 191;
		$sPourcent = 'no';
	}
	break;
}

include_once '../../../config/dialog_depot_annonce.php';

//On récupère le dialogue
$sDialog = $aDialog[$iPartie];
//On récupère les boutons
$sButtons = $aButtons[$iPartie];

echo json_encode(array('partie' => $iPartie, 'dialog' => $sDialog, 'buttons' => $sButtons, 'pourcent' => $sPourcent));

?>
