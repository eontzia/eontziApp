<!DOCTYPE html>
<html lang="es">
<head>
	<title>EontziApp</title>
	<script src="//fast.eager.io/_uPAxwoIB0.js"></script>
	<link rel="stylesheet" type="text/css" href="Templates/css/estilos.css">
	<link rel="stylesheet" type="text/css" href="../css/bootstrap.min.css">
	<link rel="icon" type="image/ico" href="../img/favicon.ico"/>
	<link rel="shortcut icon" href="../img/favicon.ico"/>
	<meta http-equiv="Content-Type" content="text/html"; charset="utf-8"/>
	<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0,minimum-scale=1.0">
	<script type="text/javascript" src="../js/jquery.min.js"></script>
	<script type="text/javascript" src="../js/bootstrap.min.js"></script>
	<script type="text/javascript">
		$(document).ready(function(){
			var verpass=false;
			$('#txtLogIdUsuario').focus();
			$('#verPass').mousedown(function(){
				if(!verpass){
					$('#txtLogPass').attr('type','text');
					$('#txtLogPass').focus();
					verpass=true;
				}
			});
			$('#verPass').mouseup(function(){
				if(verpass){
					$('#txtLogPass').attr('type','password');
					$('#txtLogPass').focus();
					verpass=false;
				}
			});						
		});
	</script>
	<!--Fuentes -->
	<link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/css?family=Cabin+Condensed" />
	<link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/css?family=PT+Sans" />
</head>
<body>		
	<nav id="cabecera" class="navbar navbar-default navbar-fixed-top">
		<div class="container">
			<!-- Brand and toggle get grouped for better mobile display -->
			<div class="navbar-header">
				<button type="button" id="hamburger_cabecera" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false" style="background-color: #2A4C3B;">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button> 								
				<a id="titulo" href="http://eontzia.zubirimanteoweb.com/">				
					<div>
						<img class="logo" src="../img/logo_sin.png">
						<span class="site-name" >eOntziApp</span>
					</div>					
				</a>
			</div>
			<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1" >
				<ul class="nav navbar-nav navbar-right" >
					<li >
						<a href="index.html">Home</a>
					</li> 
					<li >
						<a href="info.html">Información</a>
					</li> 
					<li>
						<a href="info.html#formreg" >Registro</a></li> 
					<li >
						<a href="app/demo" target="_blank">Demo</a>
					</li>
					<li >
						<a href="app/logout">Login</a>
					</li>
				</ul>
			</div><!-- /.navbar-collapse -->
  		</div><!-- /.container-fluid -->
	</nav>
	<main>
		<section>
			<div id="container">
			<?php if(isset($flash['message'])):?>
				<div class="alert alert-warning fade in" role="alert">
					<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<strong>Atenci&oacute;n!</strong> <?php echo $flash['message']?>
				</div>
			<?php endif; ?>
				<div class="col-xs-offset-1 col-xs-10 col-sm-offset-2 col-sm-8 col-md-offset-3 col-md-6 col-lg-offset-3 col-lg-6 " id="formularios">
					<div class="login">						
						<h2 style="margin-top:0px;">Iniciar sesión</h2>
						<form class="form-horizontal" action="login" method="post">		
							<div class="form-group">
								<label for="txtLogIdUsuario" class="col-sm-3 control-label">Id usuario</label>
								<div class="col-sm-9">
									<input class="form-control" id="txtLogIdUsuario" type="text" name="NomUsuario" placeholder="Id usuario" tabindex="1" required>
								</div>
							</div>
							<div class="form-group">
								<label for="txtLogPass" class="col-sm-3 control-label">Contrase&ntilde;a</label>
								<div class="col-sm-9">
									<div class="input-group">									
										<input class="form-control" id="txtLogPass" type="password" name="pass" placeholder="Introduce contraseña" tabindex="2" required>
										<div class="input-group-btn" type="button">
											<button id="verPass" type="button" class="btn btn-default">
												<span id="verPassIcon" id="verPass" class="glyphicon glyphicon-eye-open"></span>
											</button>
										</div>									
									</div>								
								</div>
							</div>						
							
							<div class="form-group">
								<div class="col-sm-2 col-sm-10">
									<button class="btn btn-primary" type="submit"  tabindex="4">Iniciar sesión</button>
								</div>
							</div>																			
						</form>					
					</div><!--div Login-->								
				</div><!--div Formularios-->
			</div> <!--	div Container-->		
		</section>
	</main>				
</body>
</html>