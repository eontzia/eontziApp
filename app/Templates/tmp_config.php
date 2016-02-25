<!DOCTYPE html>
<html>
<head>
	<script src="//fast.eager.io/_uPAxwoIB0.js"></script>
	<link rel="stylesheet" type="text/css" href="./Templates/css/estilos.css">
	<link rel="stylesheet" type="text/css" href="../css/bootstrap.min.css">
	<link rel="icon" type="image/ico" href="../img/favicon.ico"/>
	<link rel="shortcut icon" href="../img/favicon.ico"/>
	<meta http-equiv="Content-Type" content="text/html"; charset="utf-8"/>
	<script src="../js/jquery.min.js"></script>
	<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0,minimum-scale=1.0">
	<!-- Latest compiled and minified JavaScript -->
	<script src="../js/bootstrap.min.js" ></script> 
	<script  src="../js/jquery.validate.min.js"></script> 

	<script src="../js/bootstrapValidator.min.js"></script>
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
							<?php echo $nombre ?>
							<span class="caret"></span></a>
							
						<ul class="dropdown-menu">
							<li><a href="inicio"><span class="showopacity glyphicon glyphicon-home"></span> Inicio</a></li>
							<li><a href="perfil"><span class="showopacity glyphicon glyphicon-user"></span> Perfil</a></li>
							<li><a href="logout"><span class="showopacity glyphicon glyphicon-off"></span> Cerrar sesi&oacute;n</a></li>                  
						</ul>
					</li>     
				</ul>
			</div><!-- /.navbar-collapse -->
		</div><!-- /.container-->
	</nav>

	<?php $res=json_decode($flash['message'],true); if(isset($res['mensaje'])):?>
		<div <?php echo $res['result'];?> role="alert">
			<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			<h4> <?php echo $res['mensaje'];?></h4>
		</div>
	<?php endif; ?>

	<!--Contenido-->
	<div id="cont-fluid" class="container-fluid">
		<div id="dispos" class="col-xs-12 col-md-12 col-lg-12">
			<!-- Zakładki -->
			<ul class="nav nav-tabs" role="tablist" color="red">
				<li class="active "><a id="glyph" class="glyphicon glyphicon-plus" href="#anadirDisp" role="tab" data-toggle="tab"> Añadir Dispositivo</a></li>
				<li><a class="glyphicon glyphicon-edit" id="modDisp" href="#modDispo" role="tab" data-toggle="tab"> Modificar Dispositivo</a></li>
				<li><a class="glyphicon glyphicon-plus"aria-hidden="true" href="#anadirTrab" role="tab" data-toggle="tab"> Añadir Trabajador</a></li>
				<li><a class="glyphicon glyphicon-edit" id="modTrab" aria-hidden="true" href="#modTraba" role="tab" data-toggle="tab"> Modificar Trabajador</a></li>
				<li><a class="glyphicon glyphicon-edit "id="modCli" href="#modCli" role="tab" data-toggle="tab"> Modificar Cliente</a></li>
			</ul>
			<!-- Zawartość zakładek -->
			<div class="tab-content">
				<!-- Zawartość zakładki 1 -->
				<div class="tab-pane active col-xs-offset-1 col-xs-10 col-sm-offset-2 col-sm-8 col-md-offset-3 col-md-6 col-lg-offset-3 col-lg-6" id="anadirDisp"  >
					<form action="anadirDispositivo" method="post" id="anadirDispositivo">
						<div class="form-group" >
							<label  class="col-xs-2">TIPO</label>
							<select id="tiposelect" name="tiposelect" class="form-control">
								<option value="1">Rechazo</option>
								<option value="2">Plástico</option>
								<option value="3">Papel</option>
								<option value="4">Orgánico</option>
								<option value="5">Vidrio</option>
								<option value="6">Aceite</option>
								<option value="7">Ropas</option>
								<option value="8">Pilas</option>
							</select>
						</div>
						<div>
							<button  class="btn btn-default" id="buscarCoordenadas">Obtener Coordenadas</button>
							<a href="#myMapModal" class="btn" data-toggle="modal">Abrir mapa</a>
						</div>
						<!--Modal mapa-->
						<div class="modal fade" id="myMapModal">
							<div class="modal-dialog">
								<div class="modal-content">
									<div class="modal-header">
										<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
										<h4 class="modal-title">Mapa</h4>
									</div>
									<div class="modal-body">																					
											<div id="map-canvas" ></div>
											<input id="pac-input" class="controls" type="text" placeholder="Búsqueda...">										
									</div>
									<div class="modal-footer">
										<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
										<button id="bt_modalMapaCerrar" type="button" data-dismiss="modal" class="btn btn-primary">Guardar</button>
									</div>
								</div>				     
							</div>				    
						</div><!-- FIN Modal mapa-->
						<div class="form-group">
							<label for="inputLatitude" class="col-xs-2">Latitude</label>
							<input  class="form-control" id="inputLatitude" name="inputLatitude" placeholder="Latitude">
						</div>
						<div class="form-group">
							<label for="inputLongitude" class="col-xs-2">Longitude</label>
							<input  class="form-control" id="inputLongitude" name="inputLongitude" placeholder="Longitude">
						</div>
						<button type="submit" class="btn btn-primary" >Añadir</button>
					</form>
				</div>
				<!-- Zawartość zakładki 2 -->
				<div class="tab-pane col-xs-offset-1 col-xs-10 col-sm-offset-2 col-sm-8 col-md-offset-3 col-md-6 col-lg-offset-3 col-lg-6" id="anadirTrab">
					<form  action="anadirTrabajador"  method="post" id="anadirTrabajador" >
						<div class="form-group">
							<label  class="col-xs-2">Nombre</label>
							<input type="text"  required="required" class="form-control" id="Nombre" name="Nombre" placeholder="Nombre">
						</div>
						<div class="form-group">
							<label for="inputApellido" class="col-xs-2">Apellido</label>
							<input type="text"  required="required" class="form-control" id="inputApellido" name="inputApellido" placeholder="Apellido">
						</div>
						<div class="form-group">
							<label  class="col-xs-2">Telefono</label>
							<input type="text" class="form-control"  name="Telefono" placeholder="Telefono">
						</div>
						<div class="form-group">
							<label  class="col-xs-2">Email</label>
							<input type="text" class="form-control" id="inputEmail" name="Email" placeholder="Email">
						</div>
						<div class="form-group">
							<label  class="col-xs-2">Perfil</label>
							<select class="form-control" class="selectpicker" id="selectperfil" name="selectperfil">
								<option value="2">Usuario</option>
								<option value="0">Administrator</option>
								<option id="encargado" name="encargado" value="1">Encargado</option>
							</select>
						</div>
						<div id="errorbox" style="color:red"></div>
						<!-- <input id="btn" type="submit" value="ENVIAR"/>         -->
						<button id="btnTrabajador" type="submit" class="btn btn-primary">Añadir</button>
					</form>
				</div>
				<!--zawartos zakladnki 5-->
		  <div class="tab-pane " id="modTraba">		  	
			<div class="container">
				<h2>Lista de los trabajadores</h2>
				<div id="listTrab"class="list-group">
						 
				</div>				
			</div>
				<!-- Modal -->
				<div id="myModalTrabajadores" class="modal fade" role="dialog">
				  <div class="modal-dialog">
					<!-- Modal content modificarTrabajador-->
					<form  action="modificarTrabajador" id="modificarTrabajador" method="post">
					<div class="modal-content">
					  <div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h4 class="modal-title">Datos del Trabajador</h4>
					  </div>
					  <div class="modal-body">
							 <div class="form-group" >
								<label  class="col-xs-2">Trabajador_Id</label>
								<input type="text" class="form-control" id="TrabId" name="TrabId" placeholder="Email" readonly>
							</div>
							<div class="form-group">
								<label  class="col-xs-4">Fecha creacion</label>
								<input type="text"  required="required" class="form-control" id="TrabFecha" name="TrabFecha" readonly>
						  </div>
							<div class="form-group">
								<label  class="col-xs-2">Nombre</label>
								<input type="text"  required="required" class="form-control" id="TrabNombre" name="TrabNombre" placeholder="Nombre">
						  </div>
						   <div class="form-group">
								<label for="inputApellido" class="col-xs-2">Apellido</label>
								<input type="text"  required="required" class="form-control" id="TrabApellido" name="TrabApellido" placeholder="Apellido">
							</div>
							<div class="form-group">
								<label  class="col-xs-2">Telefono</label>
								<input type="text" class="form-control"  id="TrabTelefono" name="TrabTelefono" placeholder="Contasena">
							</div>
							<div class="form-group">
								<label  class="col-xs-2">Email</label>
								<input type="text" class="form-control" id="TrabEmail" name="TrabEmail" placeholder="Email">
							</div>						
					  </div>
					  <div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
						<button  onclick="formHasChanged()" type="submit" class="btn btn-primary">Modificar</button>
					  </div>
					</div>
					</form>
				  </div>
				</div>
				<!--end of MODAL modificarTrabajador-->
			</div>
				<!-- Zawartość zakładki 3 -->
				<div class="tab-pane" id="modDispo">

					<!--<form  action="modyficarDispositivos"  method="post" id="modyficarDispositivos" >-->

						<div class="container">
							<h2>Lista de los Dispositivos</h2>
							<div id="listDisp" class="list-group">
												  
							</div>
						</div>
						<div id="errorbox" style="color:red"></div>
						<!-- <input id="btn" type="submit" value="ENVIAR"/>         -->
						<!-- <button id="btnmodyficarDispositivos" type="submit" class="btn btn-primary">Modificar</button> -->
					<!--</form>-->
					<!-- Modal -->
					<div id="myModalDispositivo" class="modal fade" role="dialog">
						<div class="modal-dialog">

							<!-- Modal content-->
							<form action="modDispositivo" id="btnmodDispositivos" method="post">
								<div class="modal-content">
									<div class="modal-header">
										<button type="button" class="close" data-dismiss="modal">&times;</button>
										<h4 class="modal-title">Datos del Dispositivo</h4>
									</div>
									<div class="modal-body">
										<div class="form-group">
											<label  class="col-xs-2">Dispositivo_Id</label>
											<input type="text" class="form-control" id="inputDisId" name="inputDisId" placeholder="Dispositivo_Id" readOnly="true">
										</div>
										<div class="form-group">
											<label  class="col-xs-2">Latitude</label>
											<input  type="text" class="form-control" id="inputLatitude" name="inputLatitude" placeholder="Latitude">
										</div>
										<div class="form-group">
											<label  class="col-xs-2">Longitude</label>
											<input  type="text" class="form-control" id="inputLongitude" name="inputLongitude" placeholder="Longitude">
										</div>
										<div class="form-group">

											<label  class="col-xs-2">Activo</label>
											<select class="form-control" id="Activo" class="selectpicker" name="Activo" placeholder="Activo">
												<option value="0">No</option>
												<option value="1">Sí</option>
											</select>
										</div>      
										<label  class="col-xs-2">Tipo</label>
										<select class="form-control" class="selectpicker"  id="Tipo" name="Tipo">
											<option value="1">Rechazo</option>
											<option value="2">Plástico</option>
											<option value="3">Papel</option>
											<option value="4">Orgánico</option>
											<option value="5">Vidrio</option>
											<option value="6">Aceite</option>
											<option value="7">Ropas</option>
											<option value="8">Pilas</option>
										</select>
									</div>
									<div class="modal-footer">
										<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
										<button id="" type="submit" class="btn btn-primary">Modificar</button>
									</div>
								</div>
							</form>
						</div>
					</div>
					<!--end of MODAL-->
				</div>
				<!-- Modal -->
				<div id="myModalCliente" class="modal fade" role="dialog">
					<div class="modal-dialog">

						<!-- Modal content-->
						<form action="modCliente" id="btnmodDispositivos" method="post">
							<div class="modal-content">
								<div class="modal-header">
									<button type="button" class="close" data-dismiss="modal">&times;</button>
									<h4 class="modal-title">Datos del cliente</h4>
								</div>
								<div id="Clientebody" class="modal-body">
									<div class="form-group">
										<label  class="col-xs-12 ">Nombre de la empresa</label>
										<input type="text"  required="required" class="form-control" id="Nombre_empresa" name="Nombre_empresa" placeholder="Nombre_empresa">
									</div>
									<div class="form-group">
										<label class="col-xs-12">Comprado</label>
										<select class="form-control selectpicker" id="Comprado" name="Comprado" placeholder="Comprado" >
											<option value="0">NO</option>
											<option value="1">SI</option>
										</select>
									</div>
									<div class="form-group">
										<label  class="col-xs-12">Comentarios</label>
										<input type="text" class="form-control"  id="Comentarios" name="Comentarios" placeholder="Comentarios">
									</div>
									<div class="form-group">
										<label  class="col-xs-12">NIF</label>
										<input type="text" class="form-control" id="NIF" name="NIF" placeholder="NIF">
									</div>
									<div class="form-group">
										<label  class="col-xs-12">Nombre de contacto</label>
										<input type="text" class="form-control" id="nombre_contacto" name="nombre_contacto" placeholder="Nombre de contacto">
									</div>
									<div class="form-group">
										<label  class="col-xs-12">Apellido</label>
										<input type="text" class="form-control" id="Apellido" name="Apellido" placeholder="Apellido">
									</div>
									<div class="form-group">
										<label  class="col-xs-12">Correo</label>
										<input type="text" class="form-control" id="Correo" name="Correo" placeholder="Correo">
									</div>
									<div class="form-group">
										<label  class="col-xs-12">Telefono</label>
										<input type="text" class="form-control" id="Telefono" name="Telefono" placeholder="Telefono">
									</div>

								</div>
								<div class="modal-footer">
									<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
									<button id="" type="submit" class="btn btn-primary">Modificar</button>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>	
	</div>
		
	<!---snip end-->
	<script type="text/javascript">
		var usr=<?php echo $_SESSION['id_usuario']?>
	</script>
	<script src="./Templates/js/config.js"></script>
	<script src="./Templates/js/mapa-modal.js"></script>
	<script src="https://maps.googleapis.com/maps/api/js?key=GOOGLE_API_KEY_AQUI_&libraries=places">
	</script>
	
	
</body>
</html>
