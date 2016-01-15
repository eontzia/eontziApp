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
		require_once 'Modelos/Usuario.php';
		require_once 'Modelos/Utils.php';
		Utils::escribeLog("Inicio Registro","debug");
		
		$req=$app->request();
		$nom_empresa=$req->post('nombre_empresa');
		$nom=$req->post("nombre");
		$app=$req->post("appelido");
		$cont=$req->post("contasena");
		$email=$req->post("correo");
		$result=Usuario::nuevoUsuario($nom_empresa,$nom,$app,$cont,$email);
		//0->KO / 1->OK / 2->Existe el usuario / 3->registro OK correo KO
		/*Códigos de mensajes= 
		
		-err_reg_usr-->Error al registrar el usuario
		-usr_reg_OK-->Usuario registrado correctamente.
		-usr_em_exist-->Usuario o email existentes
		-usr_OK_em_F -->Usuario registrado, correo fallido
		*/
		if($result==0){
			//Utils::escribeLog("KO","debug");
			$mensaje= "err_reg_usr";
			$app->redirect($app->urlfor('resultado',array('mensaje'=>$mensaje)));
		}else if($result==1){
			//Utils::escribeLog("OK","debug");
			$mensaje="usr_reg_OK";
			$app->redirect($app->urlfor('resultado',array('mensaje'=>$mensaje)));
		}else if($result==2){
			//Utils::escribeLog("Existe","debug");
			$mensaje="usr_em_exist";
			$app->redirect($app->urlfor('resultado',array('mensaje'=>$mensaje)));
		}else{
			//Utils::escribeLog("Existe","debug");
			$mensaje="usr_OK_em_F";
			$app->redirect($app->urlfor('resultado',array('mensaje'=>$mensaje)));
		}
	});
	//login
	$app->post('/login',function()use ($app){
		require_once 'Modelos/Usuario.php';
		
		$usr=$app->request->post('NomUsuario');
		$pass=$app->request->post('pass');

		if(isset($usr) && isset($pass))
		{
			$result=Usuario::comprobarUsuario($usr,$pass);
			if($result==1){
				$app->redirect($app->urlFor('PaginaInicio'));
			}else if($result==0){
				$app->flash('message',"No existe el usuario");
				$app->redirect($app->urlFor('Inicio'));
			}else {
				$app->flash('message',"El usuario no est&aacute; validado, valida para poder acceder.");
				$app->redirect($app->urlFor('Inicio'));
			}
		}else
		{
			$app->flash('message',"Faltan datos por introducir.");
			$app->redirect($app->urlFor('Inicio'));
		}		
	});

	$app->get('/result/:mensaje',function($mensaje) use($app){
		/*
		-err_reg_usr-->Error al registrar el usuario
		-usr_reg_OK-->Usuario registrado correctamente.
		-usr_em_exist-->Usuario o email existentes
		-usr_OK_em_F -->Usuario registrado, correo fallido
		-err_usr_val-->Error al validar usuario
		-val_OK-->Validación correcta. Inicia sesión para acceder..
		-usr_reg-->El usuario ya está registrado
		-usrv_OK_em_F -->Usuario validad, falló envío correo.
		*/
		if($mensaje==='err_reg_usr'){
			$mensaje="Error al registrar el usuario.";
		}else if($mensaje==='usr_reg_OK'){
			$mensaje="Usuario registrado correctamente.";
		}else if($mensaje==='usr_em_exist'){
			$mensaje="Usuario o email existentes.";
		}else if($mensaje==='usr_OK_em_F'){
			$mensaje="Usuario registrado, correo fallido.";
		}else if($mensaje==='err_usr_val'){
			$mensaje="Error al validar usuario.";
		}else if($mensaje==='val_OK'){
			$mensaje="Validación correcta. Inicia sesión para acceder.";
		}else if($mensaje==='usr_reg'){
			$mensaje="El usuario ya está registrado.";
		}else {
			$mensaje="Usuario validado, falló envío correo.";
		}
		$app->render('info.php',array('mensaje'=>$mensaje));
	})->name('resultado');
	
	$app->get('/inicio',function() use($app){
		if(!isset($_SESSION['id_usuario']))
		{
			//render login
			$app->flash('message',"Debe iniciar sesión para acceder.");
			$app->redirect($app->urlFor('Inicio'));
		}
		else
		{
			$json=file_get_contents('http://eontzia.zubirimanteoweb.com/app/getAllPos');
			//$json=file_get_contents('http://localhost/workspace/eontziApp/app/getAllPos');
			$array=json_decode($json,true);
			$app->render('tmp_inicio.php',array('res'=>$array));
		}		
	})->name('PaginaInicio');
	
	//**********RUTAS API*************

	//****Envio de datos//****
	$app->get('/nuevaLectura/:disId/:vol/:fuego/:bate',function($disId,$vol,$fuego,$bate) use($app){
		require_once 'Modelos/DisDatos.php';
		$result=DisDatos::nuevaLectura($disId,$vol,$fuego,$bate);
		if ($result==1){
			echo "insert OK";
		}else{
			echo "insert KO";
			$app->response->setStatus(406);
		}
	});

	$app->get('/logout',function()use ($app){
		session_destroy();
		$app->redirect($app->urlFor('Inicio'));
	});

	//****Recogida de lecturas realizadas por dispositivo****//
	$app->get('/getLecturas/:disId',function($disId) use($app){
		require_once 'Modelos/DisDatos.php';
		$resp=array();
		$resultado=DisDatos::getLecturasByDisp($disId);
		if(!is_null($resultado)){
			$resp['estado']='OK';
			$resp['mensaje']=$resultado;
		}else{
			$resp['estado']='KO';
			$resp['mensaje']='No hay lecturas del dispositivo: '.$disId;
		}
		echo json_encode($resp);
	});

	$app->get('/getAllPos',function() use($app){
		require_once 'Modelos/Dispositivo.php';
		$idUsu=1;
		$resp=array();
		$resultado=Dispositivo::getAllDisp($idUsu);
		if($resultado['estado']==1){
			$resp['estado']='OK';
			$resp['mensaje']=$resultado['resultado'];
		}else{
			$resp['estado']='KO';
			$resp['mensaje']=$resultado['resultado'];
		}
		echo json_encode($resp);
	});


	$app->run();

	?>