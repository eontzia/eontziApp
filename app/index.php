<?php
	header ('Content-type: text/html; charset=utf-8');
	session_start();

	require 'vendor/autoload.php';

	Slim\Slim::registerAutoloader();

	$app= new \Slim\Slim();
	$app->config(array(
		'debug' =>true ,
		'templates.path' =>'Templates'));
	//Raiz de /app
	$app-> map('/',function() use ($app){
		
		if(!isset($_SESSION['id_usuario'])){
			//render login
			$app->render('tmp_login.php');
		}
		else{
			//enviar al inicio
			$app->redirect($app->urlFor('PaginaInicio'));			
		}	
	})->via('GET')->name('Inicio');

	//Registro
	$app->post('/registro',function(){
		
	});

	$app->run();

	?>