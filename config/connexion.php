<?php

//CONNEXION A LA BASE DE DONNEES

// if ($_SERVER['SERVER_NAME'] == 'localhost') {

	define ('DB_HOST', 'localhost');
	define ('DB_DATABASE', 'dating');
	define ('DB_USERNAME', 'root');
	define ('DB_PASSWORD', '');

//TODO : configurer avant mise en production
// }

//Fonction de connexion
function getConnexion() {
	try {
		$pdo = new PDO("mysql:host=".DB_HOST.";dbname=".DB_DATABASE, DB_USERNAME, DB_PASSWORD);
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$pdo->setAttribute(PDO::MYSQL_ATTR_INIT_COMMAND, "SET NAMES 'utf8'");
		$pdo->exec("SET CHARACTER SET utf8");
		return ($pdo);
	} catch (PDOException $e) {
		$message = $e->getmessage();
		echo $message;
		exit();
	}
}

?>
