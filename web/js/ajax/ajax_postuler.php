<?php

session_start();

include_once '../../../config/constantes.php';
include_once CONNEXION;
include_once MODELS.'comptes_criteres.php';
include_once MODELS.'annonces_postulants.php';
include_once MODELS.'annonces.php';
include_once MODELS.'comptes_permis.php';
include_once MODELS.'comptes.php';
include_once MODELS.'mails.php';
include_once MODELS.'newsletters_mails.php';
include_once MODELS.'logs_referal.php';
include_once MODELS.'comptes_referal.php';
include_once MODELS.'comptes_inconvenients.php';
include_once MODELS.'comptes_entreprises.php';
include_once MODELS.'compatibilites.php';
include_once CONTROLLERS.'algo.php';
include_once CONTROLLERS.'fonctions.php';
include_once MAILS.'mailing_demandeur_critere_60.php';

//On vérifie le compte
if ($_POST['compte']) {
	//On vérifie le type de compte
	if ($_SESSION[SESSION]['type'] == TYPE_COMPTE_DEMANDEUR) {
		//On récupère la candidature
		$aCandidature = getCandidature($_POST['annonce'], $_POST['compte']);
		if ($aCandidature) {
			echo 'Tu as déjà postulé à cette annonce.';
		} else {
			//On vérifie les critères
			$aCriteres = getCriteresByIdCompte($_POST['compte']);
			if ($aCriteres) {
				//On récupère l'annonce
				$aAnnonce = getAnnonceById($_POST['annonce']);
				//On récupère le departement de l'annonce
				$iDepAnnonce = substr($aAnnonce['cp_annonce'], 0, 2);
				//On récupère le compte
				$aCompte = getComptebyId($_POST['compte']);
				//On récupère le département du compte
				$iDepCompte = substr($aCompte['cp_compte'], 0, 2);
				if (($iDepCompte == $iDepAnnonce) || isset($_SESSION[SESSION]['annonces_verif_dep'][$_POST['annonce']])) {
					$bErrorPermis = false;
					if ($aAnnonce['permis_annonce'] == 'oui') {
						if (!isset($_SESSION[SESSION]['postule']['permis'])) {
							//On récupère le permis
							$aPermis = getPermisByIdCompte($_POST['compte']);
							if (!$aPermis || ($aPermis['etat_permis'] == 'non')) {
								$bErrorPermis = true;
							}
						}
					}
					if ($bErrorPermis) {
						echo 'modale_permis';
					} else {
						$bInconvenients = false;
						if ($aAnnonce['ids_inconvenients'] && !isset($_SESSION[SESSION]['annonces_verif_inconvenients'][$_POST['annonce']])) {
							//On récupère les ids inconvénients
							$aIdsInconvenients = unserialize($aAnnonce['ids_inconvenients']);
							foreach ($aIdsInconvenients as $iIdInc) {
								//On regarde si c'est un inconvénient
								$aInconvenient = getInconvenientActifByCompteAndId($_POST['compte'], $iIdInc);
								if (!$aInconvenient) {
									$bInconvenients = true;
								}
							}
						}
						if ($bInconvenients) {
							echo 'modale_inconvenients';
						} else {
							$iIdCompte = $_POST['compte'];
							//On ajoute la candidature
							insertCandidature($_POST['annonce'], $_POST['compte']);
							//On vérifie le succès jobs de l'utilisateur
							// check if user have unlocked the success
							$aCritereCompte = getCritereByIdCritereAndIdCompte(GAMIFICATION_JOBS_POSTULE, $iIdCompte);
							if(!$aCritereCompte){
								//On set le compteur à 0
								$iNbAnnoncesValides = 0;
								//On récupère le compte
								$aCompteCandidat = getComptebyId($iIdCompte);
								//On récupère le code postal du candidat
								$iCpCandidat = $aCompteCandidat['cp_compte'];
								//On récupère les critères du candidat
								$aCriteresCandidat = getCriteresByIdCompte($iIdCompte);
								// récupérer toutes les annonces auxquelle l'utilisateur à postulé
								$aCandidaturesPostulees = getCandidaturesByIdCompte($iIdCompte);
								foreach ($aCandidaturesPostulees as $aCandidature) {
									//On récupère l'annonce
									$aAnnonceTmp = getAnnonceById($aCandidature['id_annonce']);
									//On récupère la compatibilité de l'annonce
									$aCompa = getCompaByAnnonceAndCompte($aAnnonceTmp['id_annonce'], $iIdCompte);
									//On récupère la compatibilité
									if ($aCompa) {
										$fCompa = $aCompa['compa'];
									} else {
										//On récupère les critères de l'annonce
										$aCriteresAnnonce = getCriteresByIdAnnonce($aAnnonceTmp['id_annonce']);
										//On récupère les compatibilités
										$fCompa = getCompaCriteres($aCriteresCandidat, $aCriteresAnnonce);
									}
									//On applique le bonus local
									$fCompa = applyBonusLocal($fCompa, $iCpCandidat, $aAnnonceTmp['cp_annonce']);
									//On applique le malus permis
									$fCompa = applyMalusPermis($fCompa, $iIdCompte, $aAnnonceTmp['permis_annonce']);
									//On lisse la compatibilité
									$fCompa = smoothCompa($fCompa);
									if($fCompa > 50){
										$iNbAnnoncesValides++;
									}
								}
								if($iNbAnnoncesValides > 14){
									//Ajouter le succès
									addCritereByKeyAndIdCompte(GAMIFICATION_JOBS_POSTULE, $iIdCompte);
									//On envoi le mail
									mail60($iIdCompte);
								}
							}
							//On envoi un mail au recruteur
							$iIdAnnonce = $_POST['annonce'];
							$iIdNewsletter = 48;
							$iIdTracking = TRACKING_1;
							//On récupère la candidature
							$aCandidature = getCandidature($iIdAnnonce, $iIdCompte);
							$iIdCandidat = $aCandidature['id_annonce_candidat'];
							//On récupère l'id recruteur
							$iIdRecruteur = $aAnnonce['id_compte'];
							//On récupère les infos entreprise
							$aInfosEntreprise = getEntrepriseByIdCompte($iIdRecruteur);
							//On récupère l'état mailing
							$iEtatMailing = $aInfosEntreprise['etat_mailing_candidatures'];
							//On vérifie l'état mailing
							if ($iEtatMailing) {
								//On récupère le nombre d'envoi max
								$iNbEnvoiMax = MAILING_CANDIDATURE_NB;
								//On récupère le compte du recruteur
								$aCompteRecruteur = getComptebyId($iIdRecruteur);
								//On récupère l'id mail
								$iIdMailRecruteur = $aCompteRecruteur['id_mail'];
								//On récupère le nombre d'envois
								$aNbEnvois = getNbEnvoiMailingByIdNewsletterAndIdMailAndIdAnnonce($iIdNewsletter, $iIdMailRecruteur, $iIdAnnonce);
								$iNbEnvois = $aNbEnvois['nb_envois'];
								//On vérifie le nombre d'envois
								if ($iNbEnvois < $iNbEnvoiMax) {
									//On récupère les critères de l'annonce
									$aCriteresAnnonce = getCriteresByIdAnnonce($iIdAnnonce);
									//On récupère la compatibilité
									$aCompa = getCompaByAnnonceAndCompte($iIdAnnonce, $iIdCompte);
									if ($aCompa) {
										$fCompa = $aCompa['compa'];
									} else {
										//On récupère la compatibilité
										$fCompa = getCompaCriteres($aCriteres, $aCriteresAnnonce);
									}
									//On récupère le code postal du candidat
									$iCpCandidat = $aCompte['cp_compte'];
									//On récupère le code postal de l'annonce
									$iCpAnnonce = $aAnnonce['cp_annonce'];
									//On applique le bonus local
									$fCompa = applyBonusLocal($fCompa, $iCpCandidat, $iCpAnnonce);
									//On applique le malus permis
									$fCompa = applyMalusPermis($fCompa, $iIdCompte, $aAnnonce['permis_annonce']);
									//On lisse la compatibilité
									$fCompa = smoothCompa($fCompa);
									//On récupère la compatibilité minimum
									$iCompaMin = MAILING_CANDIDATURE_COMPA_MIN;
									//On vérifie la compatibilité
									if ($fCompa >= $iCompaMin) {
										//On récupère le titre de l'annonce
										$sTitreAnnonce = stripslashes($aAnnonce['titre_annonce']);
										//On récupère le téléphone
										$sTel = chunk_split($aCompte['tel_compte'], 2, ' ');
										//On récupère l'id mail
										$iIdMail = $aCompte['id_mail'];
										//On récupère le mail
										$aMail = getMailById($iIdMail);
										$sMail = stripslashes($aMail['mail']);
										//On récupère le permis
										$sPermis = NULL;
										$aPermis = getPermisByIdCompte($iIdCompte);
										if ($aPermis) {
											$sPermis = $aPermis['etat_permis'] == 'oui';
										}
										//On récupère le code annonce
										$sCodeAnnonce = stripslashes($aAnnonce['code_annonce']);
										//On récupère le mail recruteur
										$aMailRecruteur = getMailById($iIdMailRecruteur);
										$sMailRecruteur = stripslashes($aMailRecruteur['mail']);
										//On récupère le code mailing
										$sCodeMailing = genererCodeMailing();
										//On écrit le mail
										$sObjet = 'Uprigs : Nouvelle candidature à '.date('H:i').' sur l\'offre de '.$sTitreAnnonce;
										$sMessage = '
											<!DOCTYPE html>
											<html lang="fr">
												<head>
													<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
													<meta name="viewport" content="width=device-width, initial-scale=1.0">
													<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
													<title>'.$sObjet.'</title>
												</head>
												<style type="text/css">
													html {
														background-color:#E1E1E1;
														margin:0; padding:0;
													}
													body, #bodyTable, #bodyCell, #bodyCell {
														height:100% !important;
														margin:0; padding:0;
														width:100% !important;
														font-family:Helvetica, Arial, "Lucida Grande", sans-serif;
													}
													table {
														border-collapse:collapse;
													}
													table[id=bodyTable] {
														width:100%!important;
														margin:auto;
														max-width:500px!important;
														color:#7A7A7A;
														font-weight:normal;
													}
													img, a img {
														border:0;
														outline:none;
														text-decoration:none;
														height:auto;
														line-height:100%;
													}
													a {
														text-decoration:none !important;
														border-bottom: 1px solid;
													}
													h1, h2, h3, h4, h5, h6 {
														color:#5F5F5F;
														font-weight:normal;
														font-family:Helvetica;
														font-size:20px;
														line-height:125%;
														text-align:left;
														letter-spacing:normal;
														margin-top:0;
														margin-right:0;
														margin-bottom:10px;
														margin-left:0;
														padding-top:0;
														padding-bottom:0;
														padding-left:0;
														padding-right:0;
													}
													.ReadMsgBody {
														width:100%;
													}
													.ExternalClass {
														width:100%;
													}
													.ExternalClass, .ExternalClass p, .ExternalClass span, .ExternalClass font, .ExternalClass td, .ExternalClass div {
														line-height:100%;
													}
													table, td {
														mso-table-lspace:0pt;
														mso-table-rspace:0pt;
													}
													#outlook a {
														padding:0;
													}
													img {
														-ms-interpolation-mode: bicubic;
														display:block;
														outline:none;
														text-decoration:none;
													}
													body, table, td, p, a, li, blockquote {
														-ms-text-size-adjust:100%;
														-webkit-text-size-adjust:100%;
														font-weight:normal!important;
													}
													.ExternalClass td[class="ecxflexibleContainerBox"] h3 {
														padding-top: 10px !important;
													}
													h1 {
														display:block;
														font-size:26px;
														font-style:normal;
														font-weight:normal;
														line-height:100%;
													}
													h2 {
														display:block;
														font-size:20px;
														font-style:normal;
														font-weight:normal;
														line-height:120%;
													}
													h3 {
														display:block;
														font-size:17px;
														font-style:normal;
														font-weight:normal;
														line-height:110%;
													}
													h4 {
														display:block;
														font-size:18px;
														font-style:italic;
														font-weight:normal;
														line-height:100%;
													}
													.flexibleImage {
														height:auto;
													}
													.linkRemoveBorder {
														border-bottom:0 !important;
													}
													table[class=flexibleContainerCellDivider] {
														padding-bottom:0 !important;
														padding-top:0 !important;
													}
													body, #bodyTable {
														background-color:#E1E1E1;
													}
													#emailHeader {
														background-color:#E1E1E1;
													}
													#emailBody {
														background-color:#FFFFFF;
													}
													#emailFooter {
														background-color:#E1E1E1;
													}
													.nestedContainer {
														background-color:#F8F8F8;
														border:1px solid #CCCCCC;
													}
													.emailButton {
														background-color:#205478;
														border-collapse:separate;
													}
													.buttonContent {
														color:#FFFFFF;
														font-family:Helvetica;
														font-size:18px;
														font-weight:bold;
														line-height:100%;
														padding:15px;
														text-align:center;
													}
													.buttonContent a {
														color:#FFFFFF;
														display:block;
														text-decoration:none!important;
														border:0!important;
													}
													.emailCalendar {
														background-color:#FFFFFF;
														border:1px solid #CCCCCC;
													}
													.emailCalendarMonth {
														background-color:#205478;
														color:#FFFFFF;
														font-family:Helvetica, Arial, sans-serif;
														font-size:16px;
														font-weight:bold;
														padding-top:10px;
														padding-bottom:10px;
														text-align:center;
													}
													.emailCalendarDay {
														color:#205478;
														font-family:Helvetica, Arial, sans-serif;
														font-size:60px;
														font-weight:bold;
														line-height:100%;
														padding-top:20px;
														padding-bottom:20px;
														text-align:center;
													}
													.imageContentText {
														margin-top: 10px;
														line-height:0;
													}
													.imageContentText a {
														line-height:0;
													}
													#invisibleIntroduction {
														display:none !important;
													}
													span[class=ios-color-hack] a {
														color:#275100!important;
														text-decoration:none!important;
													}
													span[class=ios-color-hack2] a {
														color:#205478!important;
														text-decoration:none!important;
													}
													span[class=ios-color-hack3] a {
														color:#8B8B8B!important;
														text-decoration:none!important;
													}
													.a[href^="tel"], a[href^="sms"] {
														text-decoration:none!important;
														color:#606060!important;
														pointer-events:none!important;
														cursor:default!important;
													}
													.mobile_link a[href^="tel"], .mobile_link a[href^="sms"] {
														text-decoration:none!important;
														color:#606060!important;
														pointer-events:auto!important;
														cursor:default!important;
													}
													@media only screen and (max-width: 480px) {
														body {
															width:100% !important;
															min-width:100% !important;
														}
														table[id="emailHeader"], table[id="emailBody"], table[id="emailFooter"], table[class="flexibleContainer"], td[class="flexibleContainerCell"] {
															width:100% !important;
														}
														td[class="flexibleContainerBox"], td[class="flexibleContainerBox"] table {
															display: block;
															width: 100%;
															text-align: left;
														}
														td[class="imageContent"] img {
															height:auto !important;
															width:100% !important;
															max-width:100% !important;
														}
														img[class="flexibleImage"] {
															height:auto !important;
															width:100% !important;
															max-width:100% !important;
														}
														img[class="flexibleImageSmall"] {
															height:auto !important;
															width:auto !important;
														}
														table[class="flexibleContainerBoxNext"] {
															padding-top: 10px !important;
														}
														table[class="emailButton"] {
															width:100% !important;
														}
														td[class="buttonContent"] {
															padding:0 !important;
														}
														td[class="buttonContent"] a {
															padding:15px !important;
														}
														.padMobile {
															padding-left:30px;
															padding-right:30px;
														}
														.blockMobile {
															display: block;
														}
														.centerMobile {
															text-align: center !important;
														}
														.marginMobile30 {
															margin-top: 30px !important;
														}
														.fontSizeMobile30 {
															font-size: 30px !important;
														}
													}
												</style>
												<!--[if mso 12]>
													<style type="text/css">
														.flexibleContainer{display:block !important; width:100% !important;}
													</style>
												<![endif]-->
												<!--[if mso 14]>
													<style type="text/css">
														.flexibleContainer{display:block !important; width:100% !important;}
													</style>
												<![endif]-->
											</html>
											<body bgcolor="#E1E1E1" leftmargin="0" marginwidth="0" topmargin="0" marginheight="0" offset="0">
												<center style="background-color:#E1E1E1;">
													<table border="0" cellpadding="0" cellspacing="0" height="100%" width="100%" id="bodyTable" style="table-layout: fixed;max-width:100% !important;width: 100% !important;min-width: 100% !important;">
														<tr>
															<td align="center" valign="top" id="bodyCell">
																<table bgcolor="#FFFFFF"  border="0" cellpadding="0" cellspacing="0" width="600" id="emailBody">
																	<tr>
																		<td align="center" valign="top">
																			<table border="0" cellpadding="0" cellspacing="0" width="100%" style="color:#FFFFFF;margin-top:30px;">
																				<tr>
																					<td align="center" valign="top">
																						<table border="0" cellpadding="0" cellspacing="0" width="500" class="flexibleContainer">
																							<tr>
																								<td align="center" valign="top" width="500" class="flexibleContainerCell">
																									<table border="0" cellpadding="0" cellspacing="0" width="100%">
																										<tr>
																											<td align="center" valign="top" class="textContent">
																												<img src="'.IMG.'ma_candidature_uprigs.gif" /><br />
																												<h2 class="padMobile" style="text-align:center;font-weight:normal;font-family:Helvetica,Arial,sans-serif;font-size:23px;margin-bottom:10px;color:#007CFF;line-height:135%;">
																													Je suis intéressé(e) par votre poste de<br />
																													'.$sTitreAnnonce.'
																												</h2>
																											</td>
																										</tr>
																									</table>
																								</td>
																							</tr>
																						</table>
																					</td>
																				</tr>
																			</table>
																		</td>
																	</tr>
																	<tr mc:hideable>
																		<td align="center" valign="top">
																			<table border="0" cellpadding="0" cellspacing="0" width="100%">
																				<tr>
																					<td align="center" valign="top">
																						<table border="0" cellpadding="30" cellspacing="0" width="500" class="flexibleContainer">
																							<tr>
																								<td valign="top" width="500" class="flexibleContainerCell">
																									<table align="left" border="0" cellpadding="0" cellspacing="0" width="100%">
																										<tr>
																											<td align="left" valign="top" class="flexibleContainerBox">
																												<table border="0" cellpadding="0" cellspacing="0" width="210" class="blockMobile" style="max-width: 100%;">
																													<tbody class="blockMobile">
																														<tr class="blockMobile">
																															<td align="left" class="textContent blockMobile">
																																<h3 class="centerMobile" style="color:dimgray;line-height:125%;font-family:Helvetica,Arial,sans-serif;font-size:20px;font-weight:normal;margin-top:0;margin-bottom:3px;text-align:left;">
																																	Mes informations
																																</h3>
																																<div style="text-align:left;font-family:Helvetica,Arial,sans-serif;font-size:15px;margin-bottom:0;color:dimgray;line-height:135%;">
																																	<ul>
																																		<li>'.$sTel.'</li>
																																		<li>'.$sMail.'</li>
																																		<li>'.$iCpCandidat.'</li>

										';
										if ($sPermis) {
											if ($sPermis == 'oui') {
												$sMessage .= '
																																		<li>J\'ai le permis</li>
												';
											} else {
												$sMessage .= '
																																		<li>Je n\'ai pas le permis</li>
												';
											}
										}
										$sMessage .= '
																																	</ul>
																																</div>
																															</td>
																														</tr>
																													</tbody>
																												</table>
																											</td>
																											<td align="right" valign="top" class="flexibleContainerBox">
																												<table class="flexibleContainerBoxNext blockMobile" border="0" cellpadding="0" cellspacing="0" width="210" style="max-width: 100%;">
																													<tbody class="blockMobile">
																														<tr class="blockMobile">
																															<td align="left" class="textContent blockMobile">
																																<h3 class="centerMobile marginMobile30" style="color:dimgray;line-height:125%;font-family:Helvetica,Arial,sans-serif;font-size:20px;font-weight:normal;margin-top:0;margin-bottom:3px;text-align:center;">
																																	Ma compatibilité
																																</h3>
																																<div style="text-align:center;font-family:Helvetica,Arial,sans-serif;font-size:15px;margin-bottom:0;color:dimgray;line-height:135%;"><br />
																																	<span class="fontSizeMobile30" style="font-size:30px;color:green;">
																																		<b>'.$fCompa.' %</b>
																																	</span>
																																</div>
																															</td>
																														</tr>
																													</tbody>
																												</table>
																											</td>
																										</tr>
																									</table>
																								</td>
																							</tr>
																						</table>
																					</td>
																				</tr>
																			</table>
																		</td>
																	</tr>
																	<tr>
																		<td align="center" valign="top">
																			<table border="0" cellpadding="0" cellspacing="0" width="100%">
																				<tr style="padding-top:0;">
																					<td align="center" valign="top">
																						<table border="0" cellpadding="30" cellspacing="0" width="500" class="flexibleContainer">
																							<tr>
																								<td style="padding-top:0;" align="center" valign="top" width="500" class="flexibleContainerCell">
																									<table border="0" cellpadding="0" cellspacing="0" width="100%">
																										<tr>
																											<td valign="top" class="textContent">
																												<div mc:edit="body" class="padMobile" style="text-align:center;font-family:Helvetica,Arial,sans-serif;font-size:15px;margin-bottom:0;color:dimgrey;line-height:20px;">
																													<a href="'.URL.'tes-candidats/'.$sCodeAnnonce.'/'.$iIdMailRecruteur.'/'.$sCodeMailing.'/?ref=em_auto_'.$iIdNewsletter.'_1" title="Accéder à la fiche candidat" style="color:#007CFF;font-size:15px;">
																														En savoir plus sur votre candidat
																													</a>
																												</div>
																											</td>
																										</tr>
																									</table>
																								</td>
																							</tr>
																						</table>
																					</td>
																				</tr>
																			</table>
																		</td>
																	</tr>
																	<tr>
																		<td align="center" valign="top">
																			<table border="0" cellpadding="0" cellspacing="0" width="100%" bgcolor="white">
																				<tr>
																					<td align="center" valign="top">
																						<table border="0" cellpadding="0" cellspacing="0" width="500" class="flexibleContainer">
																							<tr>
																								<td align="center" valign="top" width="500" class="flexibleContainerCell">
																									<table border="0" cellpadding="0" cellspacing="0" width="100%">
																										<tr>
																											<td align="center" valign="top">
																												<table border="0" cellpadding="0" cellspacing="0" width="100%">
																													<tr>
																														<td valign="top" class="textContent">
																															<div mc:edit="body" style="text-align:center;font-family:Helvetica,Arial,sans-serif;font-size:15px;margin-bottom:0;color:dimgrey;line-height:135%;">
																																<hr />
																															</div>
																														</td>
																													</tr>
																												</table>
																											</td>
																										</tr>
																									</table>
																								</td>
																							</tr>
																						</table>
																					</td>
																				</tr>
																			</table>
																		</td>
																	</tr>
																	<tr>
																		<td align="center" valign="top">
																			<table border="0" cellpadding="0" cellspacing="0" width="100%" bgcolor="white">
																				<tr>
																					<td align="center" valign="top">
																						<table border="0" cellpadding="0" cellspacing="0" width="500" class="flexibleContainer">
																							<tr>
																								<td align="center" valign="top" width="500" class="flexibleContainerCell">
																									<table border="0" cellpadding="0" cellspacing="0" width="100%">
																										<tr>
																											<td align="center" valign="top">
																												<table border="0" cellpadding="0" cellspacing="0" width="100%">
																													<tr>
																														<td valign="top" class="textContent">
																															<div mc:edit="body" class="padMobile" style="text-align:center;font-family:Helvetica,Arial,sans-serif;font-size:15px;margin-bottom:0;color:dimgrey;line-height:20px;margin-top: 30px;">
																																<span style="font-size:20px;line-height:25px;color:#007CFF;">
																																	Augmentez vos chances<br />de réussir à contacter ce candidat
																																</span><br /><br />
																																Privilégiez le téléphone : <b>'.$sTel.'</b>
																															</div>
																														</td>
																													</tr>
																												</table>
																											</td>
																										</tr>
																									</table>
																								</td>
																							</tr>
																						</table>
																					</td>
																				</tr>
																			</table>
																		</td>
																	</tr>
																	<tr>
																		<td align="center" valign="top">
																			<table border="0" cellpadding="0" cellspacing="0" width="100%" bgcolor="white">
																				<tr>
																					<td align="center" valign="top">
																						<table border="0" cellpadding="0" cellspacing="0" width="500" class="flexibleContainer">
																							<tr>
																								<td align="center" valign="top" width="500" class="flexibleContainerCell">
																									<table border="0" cellpadding="0" cellspacing="0" width="100%">
																										<tr>
																											<td align="center" valign="top">
																												<table border="0" cellpadding="0" cellspacing="0" width="100%">
																													<tr>
																														<td valign="top" class="textContent">
																															<div mc:edit="body" class="padMobile" style="text-align:center;font-family:Helvetica,Arial,sans-serif;font-size:15px;margin-bottom:0;color:dimgrey;line-height:135%; margin-top: 30px;">
																																<span style="font-size:20px;color:#007CFF;">
																																	Consultez toutes vos candidatures
																																</span><br /><br />
																																Pour votre confort, vous ne recevez par email<br />
																																que les candidatures les plus pertinentes.<br />
																																Si vous souhaitez découvrir l\'ensemble des candidatures et les traiter,
																																<a href="'.URL.'tes-candidats/'.$sCodeAnnonce.'/'.$iIdMailRecruteur.'/'.$sCodeMailing.'/liste/?ref=em_auto_'.$iIdNewsletter.'_2" title="Accéder à la liste des candidats" style="color:#007CFF;">
																																	cliquez ici pour accéder à votre outil de gestion
																																</a>.
																															</div>
																														</td>
																													</tr>
																												</table>
																											</td>
																										</tr>
																									</table>
																								</td>
																							</tr>
																						</table>
																					</td>
																				</tr>
																			</table>
																		</td>
																	</tr>
																	<tr>
																		<td align="center" valign="top">
																			<table border="0" cellpadding="0" cellspacing="0" width="100%" bgcolor="white">
																				<tr>
																					<td align="center" valign="top">
																						<table border="0" cellpadding="0" cellspacing="0" width="500" class="flexibleContainer">
																							<tr>
																								<td align="center" valign="top" width="500" class="flexibleContainerCell">
																									<table border="0" cellpadding="0" cellspacing="0" width="100%">
																										<tr>
																											<td align="center" valign="top">
																												<table border="0" cellpadding="0" cellspacing="0" width="100%">
																													<tr>
																														<td valign="top" class="textContent">
																															<div mc:edit="body" style="text-align:center;font-family:Helvetica,Arial,sans-serif;font-size:15px;margin-bottom:0;color:dimgrey;line-height:135%; margin-top: 40px;">
																																<hr />
																															</div>
																														</td>
																													</tr>
																												</table>
																											</td>
																										</tr>
																									</table>
																								</td>
																							</tr>
																						</table>
																					</td>
																				</tr>
																			</table>
																		</td>
																	</tr>
																	<tr>
																		<td align="center" valign="top">
																			<table border="0" cellpadding="0" cellspacing="0" width="100%" bgcolor="white">
																				<tr>
																					<td align="center" valign="top">
																						<table border="0" cellpadding="0" cellspacing="0" width="500" class="flexibleContainer">
																							<tr>
																								<td align="center" valign="top" width="500" class="flexibleContainerCell">
																									<table border="0" cellpadding="0" cellspacing="0" width="100%">
																										<tr>
																											<td align="center" valign="top">
																												<table border="0" cellpadding="0" cellspacing="0" width="100%">
																													<tr>
																														<td valign="top" class="textContent">
																															<div mc:edit="body" style="text-align:center;font-family:Helvetica,Arial,sans-serif;font-size:15px;margin-bottom:0;color:dimgrey;line-height:135%; margin-top: 30px;">

																																Vous êtes satisfaits ?
																															</div>
																														</td>
																													</tr>
																												</table>
																											</td>
																										</tr>
																									</table>
																								</td>
																							</tr>
																						</table>
																					</td>
																				</tr>
																			</table>
																		</td>
																	</tr>
																	<tr>
																		<td align="center" valign="top">
																			<table border="0" cellpadding="0" cellspacing="0" width="100%">
																				<tr style="padding-top:0;">
																					<td align="center" valign="top">
																						<table border="0" cellpadding="30" cellspacing="0" width="500" class="flexibleContainer">
																							<tr>
																								<td align="center" valign="top" width="500" class="flexibleContainerCell">
																									<table border="0" cellpadding="0" cellspacing="0" width="100%" class="emailButton" style="background-color: #007CFF;">
																										<tr>
																											<td align="center" valign="middle" class="buttonContent" style="padding-top:15px;padding-bottom:15px;padding-right:15px;padding-left:15px;">
																												<a style="color:#FFFFFF;text-decoration:none;font-family:Helvetica,Arial,sans-serif;font-size:20px;line-height:135%;" href="'.URL.'deposer-une-annonce-d-emploi/'.$iIdMailRecruteur.'/'.$sCodeMailing.'/'.$iIdTracking.'/?ref=em_auto_'.$iIdNewsletter.'_3" target="_blank">
																													Déposer une nouvelle annonce
																												</a>
																											</td>
																										</tr>
																									</table>
																								</td>
																							</tr>
																						</table>
																					</td>
																				</tr>
																			</table>
																		</td>
																	</tr>
																	<tr>
																		<td align="center" valign="top">
																			<table border="0" cellpadding="0" cellspacing="0" width="100%" bgcolor="white">
																				<tr>
																					<td align="center" valign="top">
																						<table border="0" cellpadding="0" cellspacing="0" width="500" class="flexibleContainer">
																							<tr>
																								<td align="center" valign="top" width="500" class="flexibleContainerCell">
																									<table border="0" cellpadding="30" cellspacing="0" width="100%">
																										<tr>
																											<td align="center" valign="top">
																												<table border="0" cellpadding="0" cellspacing="0" width="100%">
																													<tr>
																														<td valign="top" class="textContent">
																															<div mc:edit="body" style="text-align:center;font-family:Helvetica,Arial,sans-serif;font-size:12px;margin-bottom:0;color:dimgrey;line-height:135%;">
																																<i>
																																	Si vous souhaitez ne plus recevoir les candidatures par email,<br />
																																	<a href="'.URL.'ne-plus-recevoir-les-candidatures/'.$iIdMailRecruteur.'/'.$sCodeMailing.'/?ref=em_auto_'.$iIdNewsletter.'_4" title="Ne plus recevoir les candidatures" style="color:#007CFF;">cliquez ici</a>.
																																</i>
																															</div>
																														</td>
																													</tr>
																												</table>
																											</td>
																										</tr>
																									</table>
																								</td>
																							</tr>
																						</table>
																					</td>
																				</tr>
																			</table>
																		</td>
																	</tr>
																</table>
															</td>
														</tr>
													</table>
												</center>
												<img src="'.URL.'controllers/tracking_ouverture_mail.php?idmail='.$iIdMailRecruteur.'&codemailing='.$sCodeMailing.'" />
											</body>
										';
										$sHeaders  = 'MIME-Version: 1.0' . "\r\n";
										$sHeaders .= 'Content-type: text/html; charset=utf-8' . "\r\n";
										$sHeaders .= 'From: Uprigs <info@uprigs.com>' . "\r\n";

										//echo $sMessage;
										//On envoi le message
										if (mail($sMailRecruteur, $sObjet, $sMessage, $sHeaders)) {
											//On enregitre l'envoi du mail
											insertNewsletterMailAnnonceCandidat($iIdNewsletter, $iIdMailRecruteur, $iIdAnnonce, $iIdCandidat, $sCodeMailing);
										}
									}
								}
							}
							echo 'ok';
						}
					}
				} else {
					echo 'modale_dep';
				}
			} else {
				echo 'modale_criteres';
			}
		}
	} else {
		echo 'Tu ne peux pas postuler avec ce compte.';
	}
} else {
	echo 'modale_connexion';
}

?>
