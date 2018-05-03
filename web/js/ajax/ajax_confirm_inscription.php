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
include_once MODELS.'annonces_postulants.php';
include_once MODELS.'logs_referal.php';
include_once MODELS.'annonces_criteres.php';
include_once MODELS.'compatibilites.php';
include_once MODELS.'liste_code_rome.php';
include_once MODELS.'comptes_preferences.php';
include_once MODELS.'comptes_inconvenients.php';
include_once MODELS.'comptes_jobs.php';
include_once MODELS.'gamification_comptes_criteres.php';
include_once MODELS.'gamification_criteres.php';
include_once CONTROLLERS.'fonctions.php';
include_once CONTROLLERS.'algo.php';
include_once MAILS.'mailing_demandeur_critere_58.php';

$sMail = $_POST['mail'];
$sTel = $_POST['tel'];
$sPassword = $_POST['password'];

$iPartie = 12;
$sType = 'href';
$sData = URL.'je-cherche-un-job/';

//On vérifie le password
if (strlen($sPassword) >= MDP_LENGHT_MIN) {

  //On crypte le password
  $sPassword = substr($sPassword, 0, GDS_POS).GDS.substr($sPassword, GDS_POS, strlen($sPassword) - GDS_POS);
  $sPassword = md5($sPassword);

  //On set les variables
  $sCP = $_SESSION[SESSION]['inscription']['cp'];
  $iCrit1 = $_SESSION[SESSION]['inscription']['crit1'];
  $iCrit2 = $_SESSION[SESSION]['inscription']['crit2'];
  $iCrit3 = $_SESSION[SESSION]['inscription']['crit3'];
  $iCrit4 = $_SESSION[SESSION]['inscription']['crit4'];
  $iCrit5 = $_SESSION[SESSION]['inscription']['crit5'];
  $aSpecificites = $_SESSION[SESSION]['inscription']['specificites'];
  $sContratsPreferences = $_SESSION[SESSION]['inscription']['sContratsPreferences'];
  $sDureesPreferences = $_SESSION[SESSION]['inscription']['sDureesPreferences'];
  $sJoursPreferences = $_SESSION[SESSION]['inscription']['sJoursPreferences'];
  $sHeuresPreferences = $_SESSION[SESSION]['inscription']['sHeuresPreferences'];
  $sExperiences = $_SESSION[SESSION]['inscription']['experiences'];
  $sCode = NULL;
  if (isset($_SESSION[SESSION]['inscription']['annonce'])) {
    $sCode = $_SESSION[SESSION]['inscription']['annonce'];
  }
  //On vérifie le numéro de téléphone
	$sTel = preg_replace("#[^0-9a-zA-Z]#", "", $sTel);
	if (verifTel($sTel)) {
		$_SESSION[SESSION]['inscription']['tel'] = $sTel;

    //On vérifie le mail
    if (verifMail($sMail)) {
      //On vérifie le compte
      $bCompteExist = false;
      //On récupère le mail
      $aMail = getMailByMail($sMail);
      if ($aMail) {
        $iIdMail = $aMail['id_mail'];
        //On récupère le compte
        $aCompte = getComptebyIdMail($iIdMail);
        if ($aCompte) {
          $bCompteExist = true;
        }
      } else {
        //On ajoute le mail
        insertMail($sMail, TYPE_COMPTE_DEMANDEUR);
        //On récupère le mail
        $aNewMail = getMailByMail($sMail);
        $iIdMail = $aNewMail['id_mail'];
      }
      if (!$bCompteExist) {
        $aDataUpdate = array();
        //On ajoute le compte
        insertCompte($sCP, $sTel, $iIdMail, $sPassword, TYPE_COMPTE_DEMANDEUR);
        $aDataUpdate['localisation'] = $sCP;
        //On récupère le compte
        $aNewCompte = getComptebyIdMail($iIdMail);
        $iIdCompte = $aNewCompte['id_compte'];
        $iIdTypeCompte = $aNewCompte['id_type_compte'];
        if ($iIdTypeCompte != TYPE_COMPTE_DEMANDEUR) {
          //On modifie le type mail
          updateTypeMail($iIdMail, TYPE_COMPTE_DEMANDEUR);
        }
        //On ajoute les spécificités
        foreach ($aSpecificites as $iIdInconvenient) {
    			insertCompteInconvenient($iIdCompte, $iIdInconvenient);
          $aDataUpdate['specificites'][] = $iIdInconvenient;
    		}
        //On ajoute les critères
        insertCriteres($iIdCompte, $iCrit1, $iCrit2, $iCrit3, $iCrit4, $iCrit5);
        $aDataUpdate['critere_1'] = $iCrit1;
        $aDataUpdate['critere_2'] = $iCrit2;
        $aDataUpdate['critere_3'] = $iCrit3;
        $aDataUpdate['critere_4'] = $iCrit4;
        $aDataUpdate['critere_5'] = $iCrit5;

        //On ajoute les type de contrats, les durees et les horaires
        insertComptePreferences($iIdCompte, $sJoursPreferences, $sHeuresPreferences, $sContratsPreferences, $sDureesPreferences);
        $aDataUpdate['jours'] = $sJoursPreferences;
        $aDataUpdate['heures'] = $sHeuresPreferences;
        $aDataUpdate['contrat'] = $sContratsPreferences;
        //On ajoute les experiences
        $aExperiences = json_decode($sExperiences, true);
        foreach ($aExperiences as $aExperience) {
          $iIdJob = $aExperience['id'];
          $iIdDuree = $aExperience['duree'];
          addJob($iIdJob, $iIdDuree, $iIdCompte);
          $aDataUpdate['metier'][] = $iIdJob;
        }

        updateInfoUser($iIdCompte, $aDataUpdate);

        //On débloque le succès "inscription"
        addCritereByKeyAndIdCompte(GAMIFICATION_INSCRIPTION, $iIdCompte);
        //On génère le code referal
        $sCodeReferal = genererCodeReferal($iIdCompte);
        //On ajoute le code referal
        insertCodeReferal($iIdCompte, $sCodeReferal);
        if(isset($_COOKIE['UprigsCodeReferal'])) {
          $sCodeReferalInscrit = $_COOKIE['UprigsCodeReferal'];
          $aCompteReferal = getCompteReferalByCodeReferal($sCodeReferalInscrit);
          if ($aCompteReferal) {
            $iIdCompteReferal = $aCompteReferal['id_compte'];
            addLogReferal($iIdCompteReferal, $iIdCompte);
            updatePointsByIdCompte($iIdCompteReferal, ATOUT_POINTS_INSCRIPTION);
            //Ajouter le succès referal
            //vérifier si l'utilisateur dispose du succès
            $aCritereCompte = getCritereByiIdCritereAndIdCompte(GAMIFICATION_REFERAL, $iIdCompteReferal);
            if(!$aCritereCompte){
              if($aCompteReferal['points'] > '4'){
                //Ajouter le succès
                addCritereByKeyAndIdCompte(GAMIFICATION_REFERAL, $iIdCompteReferal);
                //On envoi le mail
                mail58($iIdCompteReferal);
              }
            }
          }
          setcookie('UprigsCodeReferal', NULL, -1, '/');
        }
        $_SESSION[SESSION]['inscription']['mail'] = $sMail;
        //On récupère l'id log
        $iIdLog = $_SESSION[SESSION]['inscription']['log'];
        //On log l'id mail
        updateIdMailByIdLog($iIdLog, $iIdMail);
        //On récupère la session
        $sSession = serialize($_SESSION[SESSION]['inscription']);
        //On log
        updateLogInscription($iIdLog, $iPartie, $sSession);
        updateEtatByIdLog($iIdLog, $iPartie);
        //On supprime la session
        $_SESSION[SESSION]['inscription'] = NULL;
        if ($sCode) {
          $aAnnonceCode = getAnnonceByCode($sCode);
          $sVilleCode = stripslashes($aAnnonceCode['ville_annonce']);
          $sVilleSlugCode = slugify($sVilleCode);
          $sTitreCode = stripslashes($aAnnonceCode['titre_annonce']);
          $sTitreSlugCode = slugify($sTitreCode);
          $dDateSlugCode = date('d-m-Y', strtotime($aAnnonceCode['date_crea_annonce']));
          $sData = URL.$sVilleSlugCode.'/'.$sTitreSlugCode.'/'.$dDateSlugCode.'/'.$sCode.'/postuler/';
        }

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

        //On récupère le département
        $iDep = substr($sCP, 0, 2);
        if ($iDep == 97) {
          $iDep = substr($sCP, 0, 3);
        }

        //On récupère les annonces pour la recherche
        $aAnnoncesRecherche = getAnnoncesSuivByDep($iDep, 0);
        if (!$aAnnoncesRecherche) {
          $sType = 'href';
          $sData = URL.'je-cherche-un-job/felicitations/';
        }

        //On récupère les critères du compte
        $aCriteresCompte = getCriteresByIdCompte($iIdCompte);

        //On récupère les annonces du département en fonction d'une date
        $aAnnoncesDep = getAnnoncesByCpAndDate($iDep, ANNONCE_NB_JOURS_ACTIVE);
        $aCompas = array();
        foreach ($aAnnoncesDep as $aAnnonce) {
          //On récupère les critères de l'annonce
          $aCriteresAnnonce = getCriteresByIdAnnonce($aAnnonce['id_annonce']);
          //On calcule la compatibilité
          $fPourcent = getCompaCriteres($aCriteresCompte, $aCriteresAnnonce);
          $aCompas[] = array(
          'id_annonce' => $aAnnonce['id_annonce'],
          'id_compte' => $iIdCompte,
          'compa' => $fPourcent
          );
        }
        if ($aCompas) {
          //On insert en base
          insertCompas($aCompas);
        }

        //On récupère les x annonces compatibles à plus de x pourcents
        $aAnnoncesCompa = getAnnoncesByCompteAndCompa($iIdCompte, AJAX_INS_DEM_COMPA);
        $aAnnonces = array();
        $iMaxAnnonces = AJAX_INS_DEM_NB_ANNONCES;
        $iNbAnnonces = 0;
        foreach ($aAnnoncesCompa as $aAnnonceCompa) {
          //On récupère l'id annonce
          $iIdAnnonce = $aAnnonceCompa['id_annonce'];
          //On récupère la compa
          $iCompa = $aAnnonceCompa['compa'];
          //On récupère l'annonce
          $aAnnonce = getAnnonceById($iIdAnnonce);
            //On récupère l'entreprise
            $sEntreprise = stripslashes($aAnnonce['nom_entreprise']);
            //On récupère le contrat
            $aContrat = getTypeAnnonceById($aAnnonce['id_type_annonce']);
            $sContrat = stripslashes($aContrat['nom_type_annonce']);
            //On récupère la durée
            $aDuree = getDureeById($aAnnonce['id_duree_annonce']);
            $sDuree = stripslashes($aDuree['nom_duree_annonce']);
            //On récupère le code postal
            $sCp = $aAnnonce['cp_annonce'];
            //On récupère la ville
            $sVille = stripslashes($aAnnonce['ville_annonce']);
            $sVilleSlug = slugify($sVille);
            //On récupère le titre
            $sTitre = stripslashes($aAnnonce['titre_annonce']);
            if ($aAnnonce['id_code_rome']) {
              //On récupère l'id rome
              $iIdRome = $aAnnonce['id_code_rome'];
              //On récupère le code rome
              $aCodeRome = getCodeRomeById($iIdRome);
              //On récupère le label rome
              $sLabelRome = stripslashes($aCodeRome['label_rome']);
              //On slug le label
              $sTitreSlug = slugify($sLabelRome);
            } else {
              //On slug le titre
              $sTitreSlug = slugify($sTitre);
            }
            //On récupère la date de creation
            $dDateCrea = date('d-m-Y', strtotime($aAnnonce['date_crea_annonce']));
            //On récupère le code
            $sCode = stripslashes($aAnnonce['code_annonce']);
            //On récupère le permis
            $sPermis = stripslashes($aAnnonce['permis_annonce']);
            $aAnnonces[] = array(
            'compa' => $iCompa,
            'titre' => $sTitre,
            'contrat' => $sContrat,
            'ville_slug' => $sVilleSlug,
            'titre_slug' => $sTitreSlug,
            'date_crea' => $dDateCrea,
            'code' => $sCode,
            'entreprise' => $sEntreprise,
            'cp' => $sCp,
            'ville' => $sVille,
            'duree' => $sDuree,
            'permis' => $sPermis
            );
            $iNbAnnonces++;
            if ($iNbAnnonces == $iMaxAnnonces) {
              break;
            }
        }


        $iIdNewsletter = 7;
        //On génère le code mailing
        $sCodeMailing = genererCodeMailing();
        $iCompteur = 1;
        $sObjet = 'Inscription réussie. Prêt à trouver un job ?';
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
                        <td style="padding: 40px; padding-bottom: 0px; text-align: center; font-size: 15px; mso-height-rule: exactly; line-height: 30px; color: #555555;">
                          <p>
                            <span style="color:#007CFF;font-size:30px;"><b>Inscription réussie</b></span><br />
                            <span style="font-size:25px; color:dimgray;">
                              Prêt à trouver un job ?
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
                            Félicitations pour ton inscription,<br />
                            mais ce n\'est qu\'un premier pas !
                          </p>
                          <p>
                            <span style="color:#007CFf;">Uprigs</span> te conseille de postuler quotidiennement<br />
                            aux annonces sur ta plateforme.
                          </p>
                          <p>Maintenant que tu es inscris, tu obtiens automatiquement un privilège, celui de visualiser d\'un coup d\'oeil ton taux de compatibilité pour toutes les annonces présentes sur <span style="color:#007CFF;">Uprigs</span>.</p>
                          <p>Comme tu le sais, chaque job à des avantages et des inconvénients. Notre technologie détermine si les jobs proposés sont en adéquation avec tes attentes essentielles. Soit donc très attentif à ce pourcentage, plus il est haut, plus tu devrais postuler !</p>
                        </td>
                      </tr>
                    </table>
                  </td>
                </tr>
                ';
                if ($iNbAnnonces > 0) {
                  $sMessage .= '
                  <tr>
                    <td bgcolor="#ffffff">
                      <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                        <tr>
                          <td style="padding: 0px 40px; text-align: center; font-size: 15px; mso-height-rule: exactly; line-height: 30px; color: #555555;">
                            <p>
                              <span style="color:#007CFF;font-size:30px;"><b>Maintenant, passe à l\'action</b></span><br />
                              <span style="font-size:25px; color:dimgray;">
                                J\'ai <span style="color:#007CFF;"><b>'.$iNbAnnonces.'</b></span> jobs pour toi
                              </span>
                            </p>
                          </td>
                        </tr>
                      </table>
                    </td>
                  </tr>
                  ';

                  $sMessage .= '
                  <tr>
                    <td dir="ltr" bgcolor="#ffffff" align="center" height="100%" valign="top" width="100%" style="padding-bottom:30px; padding-top: 30px;">
                      <div style="width: 90%;">
                        ';
                        foreach ($aAnnonces as $aAnnonce) {
                          $sMessage .= '
                          <a href="'.URL.$aAnnonce['ville_slug'].'/'.$aAnnonce['titre_slug'].'/'.$aAnnonce['date_crea'].'/'.$aAnnonce['code'].'/'.$iIdMail.'/'.$sCodeMailing.'/?ref=em_auto_'.$iIdNewsletter.'_'.$iCompteur.'">
                            <table role="presentation" border="0" cellpadding="0" cellspacing="0" align="center" width="100%" style="max-width:660px; margin-bottom:100px;">
                              <tr>
                                <td align="center" valign="top" style="font-size:0;">

                                  <div style="display:inline-block; margin: 0 -2px; max-width: 200px; min-width:160px; vertical-align:top; width:100%;" class="stack-column">
                                    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                                      <tr>
                                        <td dir="ltr" style="padding: 0px 10px">
                                          <div style="display:inline-block; margin: 0 -2px; width:100%; min-width:200px; max-width:330px; vertical-align:top;" class="stack-column">
                                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                                              <tr>
                                                <td style="padding: 10px 10px;">
                                                  <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="font-size: 14px;text-align: left;">
                                                    <tr>
                                                      <td>
                                                        <div style="color:#32CD32; text-align:center;">
                                                          <div style="font-size:50px; height: 105px; width: 150px; margin: auto; padding-top: 45px; border-radius:200px; border: 10px solid #32CD32;">
                                                            <b>'.$aAnnonce['compa'].'%</b>
                                                          </div>
                                                        </div>
                                                      </td>
                                                    </tr>
                                                    <tr>
                                                      <td style="font-size: 20px; mso-height-rule: exactly; line-height: 25px; color: dimgray; padding-top: 20px; text-align:center;" class="stack-column-center">
                                                        Ta compatibilité<br />
                                                        avec ce job
                                                      </td>
                                                    </tr>
                                                  </table>
                                                </td>
                                              </tr>
                                            </table>
                                          </div>
                                        </td>
                                      </tr>
                                    </table>
                                  </div>

                                  <div style="display:inline-block; margin: 0 -2px; max-width:66.66%; min-width:320px; vertical-align:top;" class="stack-column">
                                    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                                      <tr>
                                        <td dir="ltr" style="mso-height-rule: exactly; line-height: 20px; color: dimgray; padding: 20px 10px 0; text-align: center;" class="center-on-narrow">
                                          <br />
                                          <span style="font-size: 25px;color:#007CFF">'.$aAnnonce['titre'].'</span>
                                          <br /><br />
                                          <span style="font-size: 20px;">'.$aAnnonce['entreprise'].'</span>
                                          <br />
                                          <span style="font-size: 20px">'.$aAnnonce['cp'].' '.$aAnnonce['ville'].'</span>
                                          <br /><br />
                                          <span style="font-size: 15px;">'.$aAnnonce['contrat'].'</span>
                                          <br />
                                          <span style="font-size: 15px;">'.$aAnnonce['duree'].'</span>
                                          ';
                                          if ($aAnnonce['permis'] == 'oui') {
                                            $sMessage .= '
                                            <br />
                                            <span style="font-size: 15px;">Permis souhaité</span>
                                            ';
                                          }
                                          $sMessage .= '
                                        </td>
                                      </tr>
                                    </table>
                                  </div>
                                </td>
                              </tr>
                            </table>
                            <br /><br />
                          </a>
                          ';
                          $iCompteur++;
                        }
                        $sMessage .= '
                      </div>
                    </td>
                  </tr>
                  ';
                }
                $sMessage .= '
                <tr>
                  <td dir="ltr" bgcolor="#ffffff" align="center" height="100%" valign="top" width="100%">

                    <table role="presentation" border="0" cellpadding="0" cellspacing="0" align="center" width="100%" style="max-width:660px; margin-bottom:100px;">
                      <tr>
                        <td align="center" valign="top" style="font-size:0;">
                          <div style="display:inline-block; margin: 0 -2px; max-width:66.66%; min-width:320px; vertical-align:top;" class="stack-column">
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                              <tr>
                                <td dir="ltr" style="font-size: 18px; mso-height-rule: exactly; line-height: 20px; color: dimgray; padding: 10px 10px 0; text-align: center;" class="center-on-narrow">
                                  <table role="presentation" cellspacing="0" cellpadding="0" border="0" class="center-on-narrow">
                                    <tr>
                                      <td style="border-radius: 3px; background: #0028C9; text-align: center;" class="button-td">
                                        <a href="'.URL.'je-cherche-un-job/'.$iIdMail.'/'.$sCodeMailing.'/?ref=em_auto_'.$iIdNewsletter.'_'.$iCompteur.'" style="background: #0028C9; border: 15px solid #0028C9; font-size: 20px; line-height: 1.1; text-align: center; text-decoration: none; display: block; border-radius: 3px; font-weight: bold;" class="button-a">
                                          <span style="color:#ffffff">Accéder aux annonces</span>
                                        </a>
                                      </td>
                                    </tr>
                                  </table>
                                  <br /><br />
                                </td>
                              </tr>
                            </table>
                          </div>

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
                          A tout de suite,<br />
                          Pascal, Co-fondateur et CEO chez <span style="color:#007CFF;">Uprigs</span>.
                          <br /><br />
                          <div style="text-align:center;margin-top:20px;">
                            <img src="'.URL.'web/img/uprigs_et_ong_conseil.png" alt="Uprigs & ONG Conseil" />
                        </div>
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
                    Si vous souhaitez ne plus recevoir de message, <a href="'.URL.'ne-plus-vouloir-recevoir-la-newsletter/'.$iIdMail.'/'.$sCodeMailing.'/?ref=em_auto_'.$iIdNewsletter.'_'.++$iCompteur.'" style="color:dimgray;">cliquez ici</a>.
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
          //On enregitre l'envoi du mail
          insertNewsletterMailCode($iIdNewsletter, $iIdMail, $sCodeMailing);
        }
      } else {
        $sType = 'error';
        $sData = 'Cet email est déjà enregistré';
      }
    } else {
      //Mail invalide
      $sType = 'error';
      $sData = 'Ton email n\'est pas valide';
    }
	} else {
    //Téléphone invalide
    $sType = 'error';
    $sData = 'Ton numéro de téléphone n\'est pas valide';
	}
}else{
  //Mot de passe invalide
  $sType = 'error';
  $sData = 'Ton mot de passe doit contenir au moins '.MDP_LENGHT_MIN.' caractères';
}

echo json_encode(array('type' => $sType, 'valeur' => $sData));
