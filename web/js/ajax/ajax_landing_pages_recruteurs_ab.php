<?php

include_once '../../../config/constantes.php';

$aLandingPagesRecruteurs = array(
	0 => 'pourquoi-deposer-ton-annonce-sur-uprigs/',
	1 => 'solution-pour-recruteurs-pertinents/'
);

$iRand = rand(0, 1);
$sPage = $aLandingPagesRecruteurs[$iRand];

echo $sPage;

?>