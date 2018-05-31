<?php
include_once '../config/constantes.php';
include_once CONTROLLERS.'functions.php';

$sTitle = 'Dating';
$sMetaDescription = 'Dis moi son job, je te dirais ses désirs';

$aData = csv_to_array('http://thomas-g.fr/web/ressources/data.csv', ';');

$result = array();
foreach ($aData as $aPeople) {
  $aCareer[$aPeople['career']] = $aPeople['career'];
}

?>
