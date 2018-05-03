<?php

include_once 'config.php';

//CONSTANTES

define ('SESSION', 'dating');
define ('CONNEXION', ROOT.'config/connexion.php');

define ('MODELS', ROOT.'models/');
define ('CONTROLLERS', ROOT.'controllers/');
define ('VIEWS', ROOT.'views/');
define ('MAILS', ROOT.'mails/');

define ('HEADER', VIEWS.'reusable/header.php');
define ('FOOTER', VIEWS.'reusable/footer.php');

define ('WEB', URL.'web/');
define ('CSS', WEB.'css/');
define ('JS', WEB.'js/');
define ('AJAX', WEB.'js/ajax/');

define ('IMG', WEB.'img/');

?>
