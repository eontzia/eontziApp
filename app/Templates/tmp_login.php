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
	<script type="text/javascript" src="../js/jquery-1.11.3.min.js"></script>
	<script type="text/javascript" src="../js/bootstrap.min.js"></script>
	<script type="text/javascript">
		$(document).ready(function(){
			$('#txtLogIdUsuario').focus();		
		});
	</script>
</head>
<body>		
	<nav id="cabecera" class="navbar navbar-default">
		<div class="container">
			<!-- Brand and toggle get grouped for better mobile display -->
			<div class="navbar-header">								
				<a id="titulo" href="http://eontzia.zubirimanteoweb.com/">
					<img class="logo" src="../img/logo.png">
					<span class="site-name">E-ontziApp</span>
					<span class="site-desc">Web de E-ontzia.</span>
				</a>
			</div>
  		</div><!-- /.container-fluid -->
	</nav>
	<main>
		<section>
			<div id="container">
			<?php if(isset($flash['message'])):?>
				<div class="alert alert-warning alert-dismissible" role="alert">
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
									<input class="form-control" id="txtLogIdUsuario" type="text" name="idUsuario" placeholder="Id usuario" tabindex="1" required>
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
								<div class="col-sm-offset-0 col-sm-10">
									<div class="checkbox">
										<label>
											<input type="checkbox" tabindex="3"> Recu&eacute;rdame
										</label>
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