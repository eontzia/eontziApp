<?php
	header ('Content-type: text/html; charset=utf-8');
	session_start();

	require 'vendor/autoload.php';
	//$url="http://eontzia.zubirimanteoweb.com";
	//$url="localhost/workspace/eontziApp";
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
	
	//Contacto
	$app->post('/contacto',function()use ($app){
		include_once 'Modelos/CorreoUser.php';
		$req=$app->request();
		$nombre=trim($req->post('nombre'));
		$email=trim($req->post("correo"));
		$asunto=trim($req->post("asunto"));
		$comentarios=trim($req->post("comentario"));

		if(!isset($nombre)||!isset($email)||!isset($asunto)||!isset($comentarios)){
			$mensaje="con_ko_ef";
			$app->redirect($app->urlFor('resultado',array('mensaje'=>$mensaje)));
		}else{
			$CorreoUser=new CorreoUser();
			$result=$CorreoUser->enviarCorreoContacto($nombre,$email,$asunto,$comentarios);
			if($result){
				$mensaje="con_ok";
				$app->redirect($app->urlFor('resultado',array('mensaje'=>$mensaje)));
			}else{
				$mensaje="con_ko";
				$app->redirect($app->urlFor('resultado',array('mensaje'=>$mensaje)));
			}
		}

	});
	//Registro
	$app->post('/registro',function()use ($app){
		require_once 'Modelos/Cliente.php';
		require_once 'Modelos/Utils.php';

		//Utils::escribeLog("Inicio Registro","debug");
		$req=$app->request();
		$nom_empresa=trim($req->post('nombre_empresa'));
		$nom=trim($req->post("nombre"));
		$ape=trim($req->post("apellido"));
		$telef=trim($req->post("telefono"));
		$email=trim($req->post("correo"));
		$result=Cliente::nuevoCliente($nom_empresa,$nom,$ape,$email,$telef);
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
		
		$usr=trim($app->request->post('NomUsuario'));
		$pass=md5(trim($app->request->post('pass')));

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

	$app->get('/panelcontrol',function() use ($app){
		if(!isset($_SESSION['id_usuario'])){
			//render login
			$app->redirect($app->urlfor('Inicio'));
		}
		else{
			$id=$_SESSION['id_usuario'];

			$json2=file_get_contents('http://eontzia.zubirimanteoweb.com/app/getUsrData/'.$id);				
			//$json2=file_get_contents('http://localhost/workspace/eontziApp/app/getUsrData/'.$id);
			$usr=json_decode($json2,true);
			$app->render('tmp_config.php',array('nombre'=>$usr['mensaje']['Nombre']." ".$usr['mensaje']['Apellido'],'img'=>$usr['mensaje']['Profile_ImageURL']));
		}
	})->name('panel');

	//Perfil
	$app->get('/perfil',function() use ($app){
		require_once 'Modelos/Trabajador.php';
		if(!isset($_SESSION['id_usuario'])){
			//render login
			$app->redirect($app->urlfor('Inicio'));
		}
		else{
			//Coger el idUsuario y consultar todos los datos y pasarlo al template
			$id=$_SESSION['id_usuario'];
			$res=Trabajador::getTrabajador($id);
			$app->render('tmp_perfil.php',array('res'=>$res));
		}
	})->name('perfil');

	//ruta DEMO
	$app->get('/demo',function() use($app){
		
		$_SESSION['id_usuario']=1;

		$_SESSION['cliente_id']='2';
		if(!isset($_SESSION['id_usuario']))
		{
			//render login
			$app->flash('message',"Debe iniciar sesión para acceder.");
			$app->redirect($app->urlFor('Inicio'));
		}
		else
		{	
			$id=$_SESSION['id_usuario'];
			$json=file_get_contents('http://eontzia.zubirimanteoweb.com/app/getAllPos/?id='.$id);
			$json2=file_get_contents('http://eontzia.zubirimanteoweb.com/app/getUsrData/'.$id);		
			//$json=file_get_contents('http://localhost/workspace/eontziApp/app/getAllPos/?id='.$id);			
			//$json2=file_get_contents('http://localhost/workspace/eontziApp/app/getUsrData/'.$id);
			$array=json_decode($json,true);
			$usr=json_decode($json2,true);
			$app->render('tmp_inicio.php',array('res'=>$array,'nombre'=>$usr['mensaje']['Nombre']." ".$usr['mensaje']['Apellido'],'img'=>$usr['mensaje']['Profile_ImageURL']));
		}		
	
	})->name('PaginaDemo');

	//Validación del usuario/trabajador
	$app->get('/usuario/validar/:correo/:key',function($correo,$key) use($app){
		require_once 'Modelos/Usuario.php';		

		$result=Usuario::validarUsuario($correo,$key);
		//0-> Fail , 1->OK, 2->Ya validado,3-> OK pero correo Fail
		/*Códigos de mensajes= 
		*-codigo-->mensaje*
		-err_usr_val-->Error al validar usuario
		-val_OK-->Validación correcta. Inicia sesión para acceder..
		-usr_reg-->El usuario ya está registrado
		-usrv_OK_em_F -->Usuario validad, falló envío correo.
		*/
		if($result==0){
			//Utils::escribeLog("KO","debug");
			$mensaje= "err_usr_val";
			$app->redirect($app->urlfor('resultado',array('mensaje'=>$mensaje)));
		}else if($result==1){
			//Utils::escribeLog("OK","debug");
			$mensaje="val_OK";
			$app->redirect($app->urlfor('resultado',array('mensaje'=>$mensaje)));
		}	
		else if($result==2){
			//Utils::escribeLog("Existe","debug");
			$mensaje="usr_reg";
			$app->redirect($app->urlfor('resultado',array('mensaje'=>$mensaje)));
		}else{
			$mensaje="usrv_OK_em_F";
			$app->redirect($app->urlfor('resultado',array('mensaje'=>$mensaje)));
		}		
	 });

	//anadirDispositivo
	$app->post('/anadirDispositivo',function() use ($app){
		require_once 'Modelos/Dispositivo.php';
		//require_once 'Modelos/Utils.php';
		//Utils::escribeLog("anadirDispositivo","debug");
		if(!isset($_SESSION['id_usuario'])){
			//render login
			$app->redirect($app->urlfor('Inicio'));
		}
		else{
			$req=$app->request();
			$tiposelect=$req->post('tiposelect');
			$inputLatitude=$req->post("inputLatitude");
			$inputLongitude=$req->post("inputLongitude");
			$usu=$_SESSION['id_usuario'];
			
			$json_dir=json_decode(file_get_contents('https://maps.googleapis.com/maps/api/geocode/json?latlng='.$inputLatitude.','.$inputLongitude.'&location_type=GEOMETRIC_CENTER&key=AIzaSyDD3NDLaalLek6GbFmNwipfqxJeuJeUrG4'), true);
			$dir=$json_dir['results'][0]['formatted_address'];
			$result=Dispositivo::anadirDispositivo($tiposelect,$inputLatitude,$inputLongitude,$usu,$dir);
			
			//0->KO / 1->OK 

			if($result==1){
				$mensaje=json_encode(array('result'=>'class="alert alert-success fade in"','mensaje'=>"El dispositivo ha sido insertado correctamente"));
				$app->flash('message',$mensaje);
				$app->redirect($app->urlfor('panel'));
			}else {
				$mensaje=json_encode(array('result'=>'class="alert alert-danger fade in"','mensaje'=>'error al actualizar.'));
				$app->flash('message',$mensaje);
				$app->redirect($app->urlfor('panel'));
			}
		}
	});

	//btnmodDispositivos
	$app->post('/modDispositivo',function() use($app){
		require_once 'Modelos/Dispositivo.php';
		if(!isset($_SESSION['id_usuario'])){
			//render login
			$app->redirect($app->urlfor('Inicio'));
		}
		else{
			$req=$app->request();
			$latitude=$req->post('inputLatitude');
			$longitude=$req->post('inputLongitude');
			$tipo=$req->post('Tipo');
			$activo=$req->post('Activo');
			$disid=$req->post('inputDisId');
			$client=$_SESSION['cliente_id'];
			
			$result=Dispositivo::ModDispositivo($disid,$client,$latitude,$longitude,$activo,$tipo);
			if($result==1){
				$mensaje=json_encode(array('result'=>'class="alert alert-success fade in"','mensaje'=>'El dispositivo ha sido modificado correctamente'));
				$app->flash('message',$mensaje);
				$app->redirect($app->urlfor('panel'));
			}else{
				$mensaje=json_encode(array('result'=>'class="alert alert-danger fade in"','mensaje'=>'No existe el dipsositivo'));
				$app->flash('message',$mensaje);
				$app->redirect($app->urlfor('panel'));
			}
		}
	});
	//modCliente
	$app->post('/modCliente',function() use ($app){
		require_once 'Modelos/Cliente.php';
		if (!isset($_SESSION['id_usuario'])){
			$app->redirect($app->urlFor('Inicio'));
		}else{
			$req=$app->request();
			$nom_empr=$req->post('Nombre_empresa');
			$compra=$req->post('Comprado');
			$coment=$req->post('Comentarios');
			$nif=$req->post('NIF');
			$nom=$req->post('nombre_contacto');
			$apell=$req->post('Apellido');
			$corr=$req->post('Correo');
			$tel=$req->post('Telefono');
			$client=$_SESSION['Client_Id'];
			
			$result=Cliente::changeCliente($nom_empr,$coment,$nif,$nom,$apell,$corr,$tel,$client,$compra);
			if($result==1){
				$mensaje=json_encode(array('result'=>'class="alert alert-success fade in"','mensaje'=>'Cliente modificado correctamente.'));
				$app->flash('message',$mensaje);
				$app->redirect($app->urlfor('panel'));
			}else{
				$mensaje=json_encode(array('result'=>'class="alert alert-danger fade in"','mensaje'=>'Error al actualizar los datos del cliente '.$nombre_empresa.'.'));
				$app->flash('message',$mensaje);
				$app->redirect($app->urlfor('panel'));
			
			}
		}
	});

//anadirTrabajador
 	$app->post('/anadirTrabajador',function() use ($app){
 	require_once 'Modelos/Trabajador.php';
 	//require_once 'Modelos/Utils.php';
 	//Utils::escribeLog("anadirTrabajador","debug");
 	if(!isset($_SESSION['id_usuario'])){
	 	//render login
		$app->redirect($app->urlfor('Inicio'));
 	}
 	else{
	 	$req=$app->request();
	 	$Nombre=$req->post('Nombre');
	 	$Apellido=$req->post("inputApellido");
	 	$Telefono=$req->post("Telefono");
	 	$Email=$req->post("Email");
	 	$Tipo=$req->post("selectperfil");
	 	$idUsu=$_SESSION['id_usuario'];

	 	//nuevoTrabajador($nombre,$apellido,$email,$telefono=null,$perfil=null,$idUsu=null,$idCli=null)
	 	$result=Trabajador::nuevoTrabajador($Nombre,$Apellido,$Email,$Telefono,$Tipo,$idUsu);
	 	 
	 //0->KO / 1->OK / 2->Existe el usuario / 3->registro OK correo KO
	 	/*Códigos de mensajes= 
	 	 
	 	-err_reg_usr-->Error al registrar el usuario
	 	-usr_reg_OK-->Usuario registrado correctamente.
	 	-usr_em_exist-->Usuario o email existentes
	 	-usr_OK_em_F -->Usuario registrado, correo fallido
	 	*/
	 	if($result==1){
	 		$mensaje=json_encode(array('result'=>'class="alert alert-success fade in"','mensaje'=>'El Trabajador insertado correctamente.'));
			$app->flash('message',$mensaje);
			$app->redirect($app->urlfor('panel'));
		}else{
			$mensaje=json_encode(array('result'=>'class="alert alert-danger fade in"','mensaje'=>'Error al insertar el trabajador.'));
			$app->flash('message',$mensaje);
			$app->redirect($app->urlfor('panel'));
		}
	}
 	});

	//modificarTrabajador
	$app->post('/modificarTrabajador', function() use ($app){
		require_once 'Modelos/Trabajador.php';
		if(!isset($_SESSION['id_usuario'])){
			//render login
			$app->redirect($app->urlfor('Inicio'));
		}
		else{
		$req=$app->request();
		$idUsu=trim($_SESSION['id_usuario']);
		$nom=trim($req->post('TrabNombre'));
		$apel=trim($req->post('TrabApellido'));
		$tel=trim($req->post('TrabTelefono'));
		$ema=trim($req->post('TrabEmail'));				

		$result=Trabajador::modTrabajador($nom,$apel,$tel,$ema,$idUsu,$imgURL);
		if($result==1){
			$mensaje=json_encode(array('result'=>'class="alert alert-success fade in"','mensaje'=>'El Trabajador modificado correctamente.'));
			$app->flash('message',$mensaje);
			$app->redirect($app->urlfor('panel'));
		}else if($result==0){
			$mensaje=json_encode(array('result'=>'class="alert alert-success fade in"','mensaje'=>'No existe el Trabajador.'));
			$app->flash('message',$mensaje);
			$app->flash('message',"No existe el Trabajador");
			$app->redirect($app->urlfor('panel'));
		}else {
			$app->flash('message',"El Trabajador no est&aacute; validado, valida para poder acceder.");
			$app->redirect($app->urlfor('panel'));
		}
		}
	});

	//modificarPerfil
	$app->post('/modPerfil', function() use ($app){
		require_once 'Modelos/Trabajador.php';
		if(!isset($_SESSION['id_usuario'])){
			//render login
			$app->redirect($app->urlfor('Inicio'));
		}
		else{
		$req=$app->request();
		$idUsu=trim($_SESSION['id_usuario']);
		$nom=trim($req->post('inputNombre'));
		$apel=trim($req->post('inputApellido'));
		$tel=trim($req->post('inputTelefono'));
		$ema=trim($req->post('inputEmail'));
		$imgURL=trim($req->post('inputURL'));		

		$result=Trabajador::modTrabajador($nom,$apel,$tel,$ema,$idUsu,$imgURL);
			if($result==1){
				$mensaje=json_encode(array('result'=>'class="alert alert-success fade in"','mensaje'=>'El perfil ha sido modificado correctamente.'));
				$app->flash('message',$mensaje);
				$app->redirect($app->urlfor('perfil'));
			}else if($result==0){
				$mensaje=json_encode(array('result'=>'class="alert alert-warning fade in"','mensaje'=>'No existe el Trabajador.'));
				$app->flash('message',$mensaje);
				$app->redirect($app->urlfor('perfil'));
			}else {
				$mensaje=json_encode(array('result'=>'class="alert alert-dangger fade in"','mensaje'=>'El Trabajador no est&aacute; validado, valida para poder acceder.'));
				$app->flash('message',$mensaje);
				$app->redirect($app->urlfor('perfil'));
			}
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
			$titulo="Error";
			$tipo='class="alert alert-warning"';
			$retmensaje="Error al registrar el usuario.";
		}else if($mensaje==='usr_reg_OK'){
			$titulo="Registro correcto";
			$tipo='class="alert alert-success"';
			$retmensaje="Usuario registrado correctamente.";
		}else if($mensaje==='usr_em_exist'){
			$titulo="Error";
			$tipo='class="alert alert-warning"';
			$retmensaje="Usuario o email existentes.";
		}else if($mensaje==='usr_OK_em_F'){
			$titulo="Error";
			$tipo='class="alert alert-warning"';
			$retmensaje="Usuario registrado, correo fallido.";
		}else if($mensaje==='err_usr_val'){
			$titulo="Error";
			$tipo='class="alert alert-warning"';
			$retmensaje="Error al validar usuario.";
		}else if($mensaje==='val_OK'){
			$titulo="Validación correcta";
			$tipo='class="alert alert-success"';
			$retmensaje="Validación correcta. Inicia sesión para acceder.";
		}else if($mensaje==='usr_reg'){
			$titulo="Error";
			$tipo='class="alert alert-warning"';
			$retmensaje="El usuario ya está registrado.";
		}else if($mensaje==='con_ok'){
			$titulo="Mensaje enviado";
			$tipo='class="alert alert-success"';
			$retmensaje="Hemos recibido el mensaje, procesaremos su mensaje lo más rápido posible.";
		}else if($mensaje==='con_ko'){
			$titulo="Error";
			$tipo='class="alert alert-warning"';
			$retmensaje="Error al enviar mensaje de contacto.";
		}else if($mensaje==='con_ko_ef'){
			$titulo="Error";
			$tipo='class="alert alert-warning"';
			$retmensaje="Por favor, revisa los datos en el formulario.";
		}else {
			$titulo="Error";
			$tipo='class="alert alert-warning"';
			$retmensaje="Usuario validado, falló envío correo.";
		}
		$app->render('info.php',array('mensaje'=>$retmensaje,'titulo'=>$titulo,'tipo'=>$tipo));
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
			$id=$_SESSION['id_usuario'];
			$json=file_get_contents('http://eontzia.zubirimanteoweb.com/app/getAllPos/?id='.$id);
			$json2=file_get_contents('http://eontzia.zubirimanteoweb.com/app/getUsrData/'.$id);
			//$json=file_get_contents('http://localhost/workspace/eontziApp/app/getAllPos/?id='.$id);
			//$json2=file_get_contents('http://localhost/workspace/eontziApp/app/getUsrData/'.$id);
			$array=json_decode($json,true);
			$usr=json_decode($json2,true);

			$app->render('tmp_inicio.php',array('res'=>$array,'nombre'=>$usr['mensaje']['Nombre']." ".$usr['mensaje']['Apellido'],'img'=>$usr['mensaje']['Profile_ImageURL']));
		}		
	})->name('PaginaInicio');

	$app->get('/logout',function()use ($app){
		session_unset();
		session_destroy();
		session_start();
		session_regenerate_id(true);
		$app->redirect($app->urlFor('Inicio'));
	});
	
	//**********RUTAS API*************

	//****Envio de datos//****
	$app->get('/nuevaLectura/:disId/:vol/:fuego/:bate',function($disId,$vol,$fuego,$bate) use($app){
		require_once 'Modelos/DisDatos.php';
		$resp=array();
		if($bate=='1'){
			$bate=rand(0,25);
		}else if($bate==2){
			$bate=rand(26,55);
		}else{
			$bate=rand(56,100);
		}
		$result=DisDatos::nuevaLectura($disId,$vol,$fuego,$bate);
		if ($result['estado']==1){
			$resp['estado']='OK';
			$resp['mensaje']=$result['resultado'];
			echo json_encode($resp, JSON_UNESCAPED_UNICODE);
		}else{
			$resp['estado']='KO';
			$resp['mensaje']=$result['resultado'];
			echo json_encode($resp, JSON_UNESCAPED_UNICODE);
			$app->response->setStatus(406);
		}
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
		echo json_encode($resp, JSON_UNESCAPED_UNICODE);
	});
	//devuelve la ultima lectura de los dispositivos que tengan lecturas
	$app->get('/getAllPos/',function() use($app){
		require_once 'Modelos/Dispositivo.php';
		if($app->request->get('id')==""){
			$idUsu=$_SESSION['id_usuario'];
		}else{
			$idUsu=$app->request->get('id');
		}
		
		$resp=array();
		$resultado=Dispositivo::getAllDisp($idUsu);
		if($resultado['estado']==1){
			$resp['estado']='OK';
			$resp['mensaje']=$resultado['resultado'];
		}else{
			$resp['estado']='KO';
			$resp['mensaje']=$resultado['resultado'];
		}
		echo json_encode($resp, JSON_UNESCAPED_UNICODE);
	});

	//devuelve los dispositivos del cliente
	$app->get('/getAllDispMod/',function() use($app){
		require_once 'Modelos/Dispositivo.php';
		if($app->request->get('id')==""){
			$idUsu=$_SESSION['id_usuario'];
		}else{
			$idUsu=$app->request->get('id');
		}
		
		$resp=array();
		$resultado=Dispositivo::getAllDispMod($idUsu);
		if($resultado['estado']==1){
			$resp['estado']='OK';
			$resp['mensaje']=$resultado['resultado'];
		}else{
			$resp['estado']='KO';
			$resp['mensaje']=$resultado['resultado'];
		}
		echo json_encode($resp, JSON_UNESCAPED_UNICODE);
	});

	//Get trabajador para modificar
		$app->get('/getAllTrabMod/', function() use ($app){
		require_once 'Modelos/Trabajador.php';
		if($app->request->get('id')==""){
			$idUsu=$_SESSION['id_usuario'];
		}else{
			$idUsu=$app->request->get('id');
		}

		$resp=array();
		$resultado=Trabajador::getAllTrabMod($idUsu);
		if($resultado['estado']=1){
			$resp['estado']='OK';
			$resp['mensaje']=$resultado['resultado'];
		}else{
			$resp['estado']='KO';
			$resp['mensaje']=$resultado['resultado'];
		}
		echo json_encode($resp);
	});

	//****Recogida de los datos del Cliente****//
	$app->get('/getCliente/:CliId',function($CliId) use ($app){
		require_once 'Modelos/Cliente.php';
		$resp=array();
		$resultado=Cliente::getClientes($CliId);
		if(!$resultado['estado']==1){
			$resp['estado']='OK';
			$resp['mensaje']=$resultado['resultado'];
		}else{
			$resp['estado']='KO';
			$resp['mensaje']='No hay Cliente con ID :'. $CliId;
		}
		echo json_encode($resp, JSON_UNESCAPED_UNICODE);
	});

	//Ruta para coger el nombre completo y la url de imagen del usuario
	$app->get('/getUsrData/:idUsu',function($idUsu) use($app){
		require_once 'Modelos/Usuario.php';
		$resp=array();
		$resultado=Usuario::getUsrData($idUsu);
		if($resultado['estado']==1){
			$resp['estado']='OK';
			$resp['mensaje']=$resultado['resultado'];
		}else{
			$resp['estado']='KO';
			$resp['mensaje']='No hay Usuario con ID :'. $idUsu;
		}
		
		echo json_encode($resp, JSON_UNESCAPED_UNICODE);
	});

	$app->run();

	?>