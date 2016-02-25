<!DOCTYPE html>
<html lang="es">
<head>
	<title><?php echo $titulo ?></title>
	<link rel="stylesheet" type="text/css" href="../Templates/css/estilos.css">
	<link rel="stylesheet" type="text/css" href="../../css/bootstrap.min.css">
	<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0,minimum-scale=1.0">
	<link rel="icon" type="image/ico" href="../../img/favicon.ico"/>
	<link rel="shortcut icon" href="../../img/favicon.ico"/>

	<meta http-equiv="Content-Type" content="text/html"; charset="utf-8"/>

	<link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/css?family=Cabin+Condensed" />
	<link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/css?family=PT+Sans" />
	</head>
<body>
	<nav id="cabecera" class="navbar navbar-default navbar-fixed-top">
		<div class="container">
			<!-- Brand and toggle get grouped for better mobile display -->
			<div class="navbar-header">				
				<a id="titulo" href="http://eontzia.zubirimanteoweb.com/app">				
					<div>
						<img class="logo" src="../../img/logo_sin.png">
						<span class="site-name" >eOntziApp</span>
					</div>					
				</a>
			</div>
    		<!-- Collect the nav links, forms, and other content for toggling -->    		
    		<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1" >
				<ul class="nav navbar-nav navbar-right" >
					<li >
						<a href="../../index.html">Home</a>
					</li> 
					<li >
						<a href="../../info.html">Información</a>
					</li> 
					<li>
						<a href="../../info.html#formreg" >Registro</a></li> 
					<li >
						<a href="../app/demo">Demo</a>
					</li>
					<li >
						<a href="../app/logout">Login</a>
					</li>
				</ul>
			</div><!-- /.navbar-collapse -->
    		
  		</div><!-- /.end container -->
	</nav>

	<main>
		<div class="container">
			<div <?php echo $tipo?> role="alert">				
				<strong><h4> <?php echo $mensaje?></h4></strong>
				<p><a href="http://eontzia.zubirimanteoweb.com">Ir a inicio</a></p>
			</div>			
		</div>		
	</main>
</body>
</html>