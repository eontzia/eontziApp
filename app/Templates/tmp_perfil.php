
<!DOCTYPE html>
<html>
<head>
	<title>Perfil</title>
	<script src="//fast.eager.io/_uPAxwoIB0.js"></script>
	<link rel="stylesheet" type="text/css" href="Templates/css/estilos.css">
	<link rel="stylesheet" type="text/css" href="../css/bootstrap.min.css">
	<link rel="icon" type="image/ico" href="../img/favicon.ico"/>
	<link rel="shortcut icon" href="../img/favicon.ico"/>
	<meta http-equiv="Content-Type" content="text/html"; charset="utf-8"/>
	<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0,minimum-scale=1.0">
	<script type="text/javascript" src="../js/jquery.min.js"></script>
	<script type="text/javascript" src="../js/bootstrap.min.js"></script>
	<script  src="../js/jquery.validate.min.js"></script>
	<script src="../js/bootstrapValidator.min.js"></script>
	<script src="Templates/js/perfil.js"></script>
	<meta name="google-signin-client_id" content="GOOGLE_ID.apps.googleusercontent.com">
	<link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=Cabin+Condensed" />
	<link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=PT+Sans" />
	<script src="https://apis.google.com/js/platform.js" async defer></script>
</head>
<body>
	<!--Navbar-->
	<nav id="cabecera" class="navbar navbar-default navbar-fixed-top">
		<div class="container-fluid">
			<!-- Brand and toggle get grouped for better mobile display -->
			<div class="navbar-header">	
				<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<a id="titulo" href="http://eontzia.zubirimanteoweb.com/app">				
					<div>
						<img class="logo" src="../img/logo_sin.png">
						<span class="site-name" >eOntziApp</span>
					</div>					
				</a>
			</div>
			<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
				<ul class="nav navbar-nav navbar-right">			
					<li class="dropdown">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
							
							<img id="img-perfil-mini" class="img-circle" src="<?php echo $res['resultado'][0]['Profile_ImageURL']?>">
							
							<?php echo $res['resultado'][0]['nombreCompleto']?>
							<span class="caret"></span>
						</a>
						<ul class="dropdown-menu">
							<li><a href="inicio"><span class="showopacity glyphicon glyphicon-home"></span> Inicio</a></li>
							<li><a href="panelcontrol"><span class="showopacity glyphicon glyphicon-wrench"></span> Configuración</a></li>
							<li><a href="logout"><span class="showopacity glyphicon glyphicon-off"></span> Cerrar sesi&oacute;n</a></li>                  
						</ul>
					</li>     
				</ul>
			</div><!-- /.navbar-collapse -->
		</div><!-- /.container-->
	</nav><!--Fin Navbar-->
	<?php $rest=json_decode($flash['message'],true); if(isset($rest['mensaje'])):?>
		<div <?php echo $rest['result'];?> role="alert">
			<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			<h4> <?php echo $rest['mensaje'];?></h4>
		</div>
	<?php endif; ?>
	<div id="contenido" class="row">
		<div id="body-perfil" class="col-xs-offset-1 col-xs-10 col-sm-offset-2 col-sm-8 col-md-offset-2 col-md-8 con-lg-offset-2 col-lg-8">
			<div id="cabecera-perfil" class="container-fluid">	
				<div id="bt-google" class="g-signin2 pull-right" data-onsuccess="onSignIn" data-title="Entrar con Google"></div>	
				<div id="imagen-perfil">
					<img id="img-perfil" class=" img-perfil" src="<?php echo $res['resultado'][0]['Profile_ImageURL']?>">
				</div>
			</div>
			
			<div id="contenido-perfil">
				<form  action="modPerfil"  method="post" id="modificarPerfil" >
					<div class="form-group">
						<label  for="Nombre">Nombre</label>
						<input type="text"  required="required" value="<?php echo $res['resultado'][0]['Nombre']?>" class="form-control form-control-green" id="inputNombre" name="inputNombre" placeholder="Nombre" readonly>
					</div>
					<div class="form-group">
						<label for="inputApellido" >Apellido</label>
						<input type="text"  required="required" value="<?php echo $res['resultado'][0]['Apellido']?>" class="form-control form-control-green" id="inputApellido" name="inputApellido" placeholder="Apellido" readonly>
					</div>
					<div class="form-group">
						<label  for="inputTelefono">Telefono</label>
						<input type="text" class="form-control form-control-green" value="<?php echo $res['resultado'][0]['Telefono']?>" id="inputTelefono" name="inputTelefono" placeholder="Telefono" readonly>
					</div>
					<div class="form-group">
						<label for="inputEmail">Email</label>
						<input type="text" class="form-control form-control-green" value="<?php echo $res['resultado'][0]['Email']?>" id="inputEmail" name="inputEmail" placeholder="Email" readonly>
					</div>
					<div class="form-group">
						<label for="inputEmail">URL imágen</label>
						<input type="text" class="form-control form-control-green" value="<?php echo $res['resultado'][0]['Profile_ImageURL']?>" id="inputURL" name="inputURL" placeholder="URL imágen" readonly>
					</div>
					
					<button id="btnModifTrab" type="submit" class="btn btn-warning">Modificar</button>
					<button id="btnHabilitar" class="btn btn-primary pull-right">Habilitar edición</button>
				</form>
			</div>
					
		</div>
	</div>
	<script type="text/javascript">
		function onSignIn(googleUser) {
			var profile = googleUser.getBasicProfile();			
			$('#img-perfil').attr('src',profile.getImageUrl());
			$('#inputURL').val(profile.getImageUrl());
			$('#inputEmail').val(profile.getEmail());
		}
	</script>

</body>
</html>