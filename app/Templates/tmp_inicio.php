<!DOCTYPE html>
<html>
<head>
	<title>Inicio</title>
	<script src="//fast.eager.io/_uPAxwoIB0.js"></script>
	<link rel="stylesheet" type="text/css" href="Templates/css/estilos.css">
	<link rel="stylesheet" type="text/css" href="../css/bootstrap.min.css">
	<link rel="icon" type="image/ico" href="../img/favicon.ico"/>
	<link rel="shortcut icon" href="../img/favicon.ico"/>
	
	<meta http-equiv="Content-Type" content="text/html"; charset="utf-8"/>
	<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0,minimum-scale=1.0">
	<script type="text/javascript" src="../js/jquery.min.js"></script>
	<script type="text/javascript" src="../js/bootstrap.min.js"></script>
	
	<link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/css?family=Cabin+Condensed" />
	<link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/css?family=PT+Sans" />
</head>
<body>
	<!--Cabecera-->
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
						<img id="img-perfil-mini" class="img-circle" src="<?php echo $img?>">
						<?php echo $nombre ?><span class="caret"></span></a>
						<ul class="dropdown-menu">
							<li><a href="perfil"><span class="showopacity glyphicon glyphicon-user"></span> Perfil</a></li>
							<li><a href="panelcontrol"><span class="showopacity glyphicon glyphicon-wrench"></span> Configuración</a></li>
							<li><a href="logout"><span class="showopacity glyphicon glyphicon-off"></span> Cerrar sesi&oacute;n</a></li>                  
						</ul>
					</li>     
				</ul>
			</div><!-- /.navbar-collapse -->
		</div><!-- /.container-->
	</nav>
	
	<!--Contenido-->
	<div id="cont-fluid" class="container-fluid">
		<div id="dispos" class=" col-xs-12 col-sm-3 col-md-3 col-lg-3">
			<!--Js/css Tabla-->
				<script src="https://cdn.datatables.net/1.10.10/js/jquery.dataTables.min.js"></script>
				<script src="https://cdn.datatables.net/1.10.10/js/dataTables.bootstrap.min.js"></script>
				<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.10/css/dataTables.bootstrap.min.css">				
			<table id="example" class="table table-striped table-responsive table-bordered" cellspacing="0"  style="width:100%;text-align: center">
		        <thead>
		            <tr>
		                <th>TIPO</th>
		                <th>VOLUMEN</th>
		                <th>FUEGO</th>
		                <th>BATERIA</th>
		             </tr>
		        </thead>
		        <tbody>		        	
		        	
			        <?php if($res['estado']!="KO"){  foreach ($res['mensaje'] as  $value) {
			        	?>
			        	<tr onclick="centrarEnMapa( <?php echo $value['Dispositivo_Id']?>)">
				           	<td> 
				           		<?php echo '<img src="http://eontzia.zubirimanteoweb.com/app/Templates/img/Container/tipo_'.($value['Tipo']).'.png" " style="height:35px;width:35px;">';?> </td>
				           	<td> <?php echo $value['Volumen']."%";?>  </td> 
				           	<td> <?php echo $value['Fuego'];?>  </td>
				           	<td> <?php echo $value['Bateria']."%";?>  </td> 
			        	 </tr>
			        <?php }} ?>                        
		        </tbody>
		    </table>
				
		</div>		
		<div id="mapa" class="col-xs-12 col-sm-9 col-md-9 col-lg-9">
		</div>				
	</div>
	<input id="pac-input" class="controls" type="text" placeholder="Búsqueda...">


	<!--Js Mapa-->
	<script type="text/javascript" src="./Templates/js/mapa.js"></script>
	<script async defer
	src="https://maps.googleapis.com/maps/api/js?key=GOOGLE_API_KEY_AQUI_&libraries=places&callback=initMap">
	</script>
</body>
</html>