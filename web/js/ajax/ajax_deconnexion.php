<?php

session_start();

include_once '../../../config/constantes.php';

$_SESSION[SESSION] = false;
setcookie('UprigsConnexion', NULL, -1, '/');

?>