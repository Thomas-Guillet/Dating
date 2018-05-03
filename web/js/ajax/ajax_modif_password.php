<?php

include_once '../../../config/constantes.php';
include_once CONNEXION;
include_once MODELS.'comptes.php';

//On crypte l'ancien password
$sOldPassword = substr($_POST['old_password'], 0, GDS_POS).GDS.substr($_POST['old_password'], GDS_POS, strlen($_POST['old_password']) - GDS_POS);
$sOldPassword = md5($sOldPassword);
//On récupère le compte
$aCompte = getComptebyId($_POST['id_compte']);
//On vérifie le mot de passe
if ($sOldPassword == $aCompte['password_compte']) {
	//On vérifie que le nouveau mot de passe est valide
	if (strlen($_POST['password1']) >= MDP_LENGHT_MIN) {
		//On vérifie que les mots de passe sont identiques
		if ($_POST['password1'] == $_POST['password2']) {
			//On crypte le mot de passe
			$sPassword = substr($_POST['password1'], 0, GDS_POS).GDS.substr($_POST['password1'], GDS_POS, strlen($_POST['password1']) - GDS_POS);
			$sPassword = md5($sPassword);
			//On enregistre le mot de passe
			updatePassword($_POST['id_compte'], $sPassword);
			echo 'ok';
		} else {
			echo 'Les deux mots de passe doivent être identiques';
		}
	} else {
		echo 'Le mot de passe doit contenir au moins '.MDP_LENGHT_MIN.' caractères';
	}
} else {
	echo 'Ancien mot de passe incorrect';
}

?>