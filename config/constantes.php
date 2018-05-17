<?php
include_once 'config.php';

//CONSTANTES

define ('SESSION', 'dating');
define ('CONNEXION', ROOT.'config/connexion.php');
define ('MODELS', ROOT.'models/');
define ('CONTROLLERS', ROOT.'controllers/');
define ('VIEWS', ROOT.'views/');
define ('REUSABLES', VIEWS.'reusable/');
define ('MAILS', ROOT.'mails/');

define ('HEADER', REUSABLES.'header.php');
define ('FOOTER', REUSABLES.'footer.php');

define ('WEB', URL.'web/');
define ('CSS', WEB.'css/');
define ('JS', WEB.'js/');
define ('AJAX', WEB.'js/ajax/');

define ('IMG', WEB.'img/');

?>
