<?php

session_start();

// для подключения к бд
define('DB_USER', 'vyalikovdb');
define('DB_PASS', 'Qwaszx12');
define('DB_HOST', 'localhost');
define('DB_NAME', 'vyalikov');


try
{
	// Соединяемся с БД
	$dbPDO = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASS); 
	$dbPDO->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch(PDOException $e)
{
	echo $e->getMessage();
}


?>