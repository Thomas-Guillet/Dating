<?php

session_start();

include_once '../../../config/constantes.php';
include_once CONNEXION;
include_once MODELS.'comptes_criteres.php';

//On récupère le compte
$iIdCompte = $_SESSION[SESSION]['compte'];
//On ajoute les critères
insertCriteres($iIdCompte, $_POST['critere1'], $_POST['critere2'], $_POST['critere3'], $_POST['critere4'], $_POST['critere5']);

?>