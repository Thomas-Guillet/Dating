<?php

include_once '../../../config/constantes.php';

session_start();

if (!isset($_SESSION[SESSION])) {
	$_SESSION[SESSION] = array();
}
$_SESSION[SESSION]['cookies'] = true;

?>