<?php

include_once '../../../config/constantes.php';
include_once CONNEXION;
include_once MODELS.'comptes_jobs_duree.php';

$aDureeJob = getAllActiveDuration();

$sHtml = '';

foreach ($aDureeJob as $key => $value) {
  $iId = $value['id_duree_job'];
  $sName = htmlentities($value['label'], ENT_QUOTES);
  $sHtml .= '<option value="'.$iId.'">'.$sName.'</option>';
}

echo $sHtml;
