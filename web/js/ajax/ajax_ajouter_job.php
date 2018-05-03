<?php

include_once '../../../config/constantes.php';
include_once CONNEXION;
include_once MODELS.'comptes_jobs.php';
include_once MODELS.'comptes_jobs_duree.php';
include_once MODELS.'liste_code_rome.php';
include_once MODELS.'gamification_comptes_criteres.php';
include_once MODELS.'gamification_criteres.php';
include_once CONTROLLERS.'fonctions.php';
include_once MAILS.'mailing_demandeur_critere_56.php';

$iIdCodeRome = $_POST['iIdCodeRome'];
$iDureeJob = $_POST['iDureeJob'];
$iIdCompte = $_POST['iIdCompte'];

$iIdJob = addJob($iIdCodeRome, $iDureeJob, $iIdCompte);

$aUserJobs = getJobsByUserId($iIdCompte);
$aDataUpdate['metier'] = '';
foreach ($aUserJobs as $job) {
  $aDataUpdate['metier'] .= '/'.$job['id_code_rome'].'/';
}
updateInfoUser($iIdCompte, $aDataUpdate);

$aCodeRome = getCodeRomeById($iIdCodeRome);
$aDuree = getDureeJobsById($iDureeJob);

$aData = array();
$aData['label'] = htmlentities($aCodeRome['label_titre'], ENT_QUOTES);
$aData['duree'] = $aDuree['label'];
$aData['id'] = $iIdJob;
$sData = json_encode($aData);

//vérifier si l'utilisateur dispose du succès
$aCritereCompte = getCritereByIdCritereAndIdCompte(GAMIFICATION_EXPERIENCES, $iIdCompte);
if(!$aCritereCompte){
  $aCompteJobs = getNbJobByIdCompte($iIdCompte)['nb_jobs'];
  if($aCompteJobs > 2){
    //Ajouter le succès
    addCritereByKeyAndIdCompte(GAMIFICATION_EXPERIENCES, $iIdCompte);
    //on envoi le mail
    mail56($iIdCompte);
  }
}

echo $sData;
