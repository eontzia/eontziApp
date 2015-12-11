<?php
	header ('Content-type: text/html; charset=utf-8');
	session_start();

	require '../vendor/autoload.php';


	Slim\Slim::registerAutoloader();


	$app= new \Slim\Slim();
	$app->config(array(
		'debug' =>true ,
		'templates.path' =>'Templates'));

	?>