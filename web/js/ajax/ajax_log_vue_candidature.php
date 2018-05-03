<?php

include_once '../../../config/constantes.php';
include_once CONNEXION;
include_once MODELS.'annonces.php';
include_once MODELS.'logs_vues_candidatures.php';

//On récupère le code annonce
$sCode = $_POST['code'];
//On récupère l'annonce
$aAnnonce = getAnnonceByCode($sCode);
//On récupère l'id annonce
$iIdAnnonce = $aAnnonce['id_annonce'];
//On ajoute le log vue candidature
insertLogVueCandidature($iIdAnnonce);

?>