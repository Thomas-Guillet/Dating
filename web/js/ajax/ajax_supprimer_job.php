<?php

include_once '../../../config/constantes.php';
include_once CONTROLLERS.'functions.php';

$iIdJob = $_POST['iIdJob'];
$iIdCompte = getIdCompteByIdJob($iIdJob)['id_compte'];

deleteJob($iIdJob);

$aUserJobs = getJobsByUserId($iIdCompte);
$aDataUpdate['metier'] = '';
foreach ($aUserJobs as $job) {
  $aDataUpdate['metier'] .= '/'.$job['id_code_rome'].'/';
}
updateInfoUser($iIdCompte, $aDataUpdate);

echo 'success';
