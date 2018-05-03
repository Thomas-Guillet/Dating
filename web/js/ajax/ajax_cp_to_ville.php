<?php

include_once '../../../config/constantes.php';
include_once CONNEXION;
include_once MODELS.'liste_cp_ville.php';


//On récupère les données
$sAlerteCP = '';
$sAlerteAdresse = '';
$sAlerteVille = '';

$sCP = $_POST['cp'];
$iVille = $_POST['ville'];


//On récupère les villes
$aVilles = getVillesByCP($sCP);
if ($aVilles) {
	//On formate
	$sSelect = '';
	foreach ($aVilles as $aVille) {
		//On récupère la ville
		$sVille = stripslashes($aVille['ville']);
		$sSelected = '';
		if ($aVille['id_cp_ville'] == $iVille) {
			$sSelected = 'selected';
		}
		$sSelect .= '<option value="'.$aVille['id_cp_ville'].'" '.$sSelected.'>'.$sVille.'</option>';
	}
	echo $sSelect;
} else {
	echo $sAlerteCP = 'error-cp';
}

?>