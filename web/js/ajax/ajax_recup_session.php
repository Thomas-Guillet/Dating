<?php

include_once '../../../config/constantes.php';

session_start();
$iIdCompte = $_SESSION[SESSION]['compte'];
$iTypeCompte = $_SESSION[SESSION]['type'];

echo json_encode(array('compte' => $iIdCompte, 'type_compte' => $iTypeCompte));

?>