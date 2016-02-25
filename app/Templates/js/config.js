$(document).ready(function(){
	//***** VALIDACIONES ******
	//VALIDAR EL TRABAJADOR
	var reglas = {  
		Nombre: {required:true,minlength:3},  
		inputApellido:{required:true,minlength:3},
		Email:{required: true, email:true}, 
		Telefono:{required:true,digits:true,minlength:9},

	};

	var mensajes = {  
		Nombre: {required:"Nombre requerido",minlength:"El nombre demasiado corto"}, 
		inputApellido:{required:"Nombre requerido",minlength:"El apellido demasiado corto"}, 
		Email:{required:"Email requerido", email:"Formato de Email incorrecto"},  
		Telefono:{required:"Falta numero", digits:"aceptados solo numeros",min:"el numero incorrecto"}		
	};

	$("#anadirTrabajador").validate({  
		rules:reglas,  
		messages:mensajes,			 
		highlight: function(element) {
			$(element).closest('.form-group').addClass('has-error');
		},
		unhighlight: function(element) {
			$(element).closest('.form-group').removeClass('has-error');
		},
		errorElement: 'span',
		errorClass: 'help-block',
		errorPlacement: function(error, element) {
			if(element.parent('.input-group').length) {
				error.insertAfter(element.parent());
			} else {
				error.insertAfter(element);
			}
		}
	});

	//END OF VALIDAR DEL TRABAJADOR

	//VALIDAR EL DISPOSITIVO
	var reglasDispositivo={
		inputLatitude:{required:true,floatvalid:true},
		inputLongitude:{required:true,floatvalid:true},
	};
	var mensajesDispositivo={
		inputLatitude:{required:"Latitud obligatoria",digits:"aceptados solo numeros"},
		inputLongitude:{required:"Longitud obligatoria",digits:"aceptados solo numeros"},
	};
	jQuery.validator.addMethod('floatvalid', function(value) { return (value.match(/^\-?([0-9]+(\.[0-9]+)?|Infinity)$/)); }, 'Por favor, introduce un número válido');
	//jQuery.validator.addMethod('floaton', function(value){return (value.match(/^\-?([0-9]+(\.[0-9]+)?/));}'insertar numero corecto');
		
	$("#anadirDispositivo").validate ({  
		rules:reglasDispositivo,  
		messages:mensajesDispositivo,			 
		highlight: function(element) {
			$(element).closest('.form-group').addClass('has-error');
		},
		unhighlight: function(element) {
			$(element).closest('.form-group').removeClass('has-error');
		},
		errorElement: 'span',
		errorClass: 'help-block',
		errorPlacement: function(error, element) {
			if(element.parent('.input-group').length) {
				error.insertAfter(element.parent());
			} else {
				error.insertAfter(element);
			}
		}
	});
		
	//END OF VALIDAR EL DISPOSITIVO
	//***** FIN VALIDACIONES *****
});//fin ready

	//**** AJAX ****
	//  AJAX PARA RECOGER EL CLIENTE 
	var div = document.getElementById("dom-target");
	var ID = div.textContent;
	var myClient= {};
	$.ajax({
		type:"GET",
		//url:"http://localhost/workspace/eontziApp/app/getCliente/"+ID,	
		url:"http://eontzia.zubirimanteoweb.com/app/getCliente/"+ID,
		//url:"http://localhost/workspace/Aitor/classes/chris_residuos/eontzia_/new_eontzia/eontzia/app/getCliente/"+ID,
		dataType:"JSON",
		data:"",
		success:function(data){
			//console.log(data);
			if(data.estado=="OK"){
				myClient.data=data.mensaje;
				//console.log(myClient);
				$('.modal-body #Nombre_empresa').val(data.mensaje['Nombre']);						  
			}
		},
		beforeSend:function(){
		},
		complete:function(){
		},
		error:function(){
		}
	});
	//END OF AJAX RECOGER EL CLIENTE

	//AJAX PARA RECOGER EL DISPOSITIVO
	var myData= {};
	$.ajax({
		type:"GET",
		//url:"http://localhost/workspace/eontziApp/app/getAllDispMod",	
		url:"http://eontzia.zubirimanteoweb.com/app/getAllDispMod",
		//url:"http://localhost/workspace/Aitor/classes/chris_residuos/eontzia_/new_eontzia/eontzia/app/getAllDispMod/",
		dataType:"JSON",
		data:"",
		success:function(data){
			if(data.estado=="OK"){
				myData.data = data.mensaje;
				//console.log(data.mensaje);
				$.each(data.mensaje, function(kk,vv) {
					 //$.each(vv, function(k, v) {
					  	//console.log(vv);
					  	var a=$("#listDisp");
					  	var direccion="";
					  	if(vv['Direccion']==null){
					  		direccion="No disponible";
					  	}else{
					  		direccion=vv['Direccion'];
					  	}
					  	$(a).append("<a id= "+vv['Dispositivo_Id']+" class='list-group-item'><h4 class='list-group-item-heading'>ID de dispositivo: "+vv['Dispositivo_Id']+"<img src='http://eontzia.zubirimanteoweb.com/app/Templates/img/Container/tipo_"+vv['Tipo']+".png' >"+"</h4><p class='list-group-item-text'>Dirección: "+direccion+" </p><p class='list-group-item-text'>Última fecha de modificación: "+vv['Fecha_modif']+" </p></a>");

					  //});
			});  
			}
		},
		beforeSend:function(){
		},
		complete:function(){
		},
		error:function(){
		}
	});
	//END OF AJAX DEL DISPOSITIVO
	//AJAX PARA RECOGER EL TRABAJADORES
	var myTrabajador= {};
	$.ajax({
			type:"GET",
			//url:"http://localhost/workspace/eontziApp/app/getAllTrabMod",	
			url:"http://eontzia.zubirimanteoweb.com/app/getAllTrabMod",
			//url:"http://localhost/workspace/Aitor/classes/chris_residuos/eontzia_/new_eontzia/eontzia/app/getAllTrabMod",
			dataType:"JSON",
			data:"",
			success:function(data){
				if(data.estado=="OK"){
					myTrabajador.data = data.mensaje;
					console.log(data.mensaje);
						$.each(data.mensaje, function(kk,trab) {
						 //$.each(vv, function(k, v) {
						  	//console.log(vv);
						  	var a=$('#listTrab');
						  	$(a).append("<a id="+trab['Trabajador_Id']+" class='list-group-item'><h4 class='list-group-item-heading'>ID de trabajador: "+
						  		trab['Trabajador_Id']+"<br>Nombre: "+trab['Nombre']+"<br> Apellido: "+trab['Apellido']+ "<br> Telefono: "+trab['Telefono']+"</h4><p class='list-group-item-text'> </p></a>");
						    
						  //});
						});  
				}
			},
			beforeSend:function(){
			},
			complete:function(){
			},
			error:function(){
			}
		});
	//END OF AJAX DEL DISPOSITIVO
	//**** FIN AJAX ****

	//**** EVENTOS ****
	//**** CLICK ****
	$("#buscarCoordenadas").click( function(){
		alert("Problem obteniendo coordenadas. Hay que añadirlas manualmente");
		$("#inputLatitude").focus(); 
		return false;
	});

	$('#encargado').click( function(){
		alert("selected");
	});
	//***** MODALS ****
	// FUNCIONAMIENTO DEL MODAL DE DISPOSITIVO
	$('#modDisp').click(function(){
		$("#listDisp a").not('.emptyMessage').click(function() {
		//alert('Dispositivo con ID ' + this.id);
		//console.log(myData);
		 
		for (var s in myData.data) {
		 	if (myData.data[s]["Dispositivo_Id"] == this.id){
		 		console.log(myData.data[s]);
				//console.log(myData.data[s]['Latitud']);
				$('.modal-body #inputLatitude').val(myData.data[s]['Latitud']);
				$('.modal-body #inputLongitude').val(myData.data[s]['Longitud']);
				$('.modal-body #Tipo').val(myData.data[s]['Tipo']);
				$('.modal-body #Activo').val(myData.data[s]['Activo']);
				$('.modal-body #inputDisId').val(myData.data[s]['Dispositivo_Id']);
			}
		}
			//	$('.modal-body #inputLatitude').val(latitude);     
			$('#myModalDispositivo').modal('show');
		});
	});

	//refrescar el mapa del modal al abrir el modal
	$('#myMapModal').on('shown.bs.modal', function () {		
		initMap();
	});
	//EVENTO CLICK PARA ABRIR MODAL MODIFICAR TRABAJADOR
	$("#modTrab").click(function(){
		$("#listTrab a").not('.emptyMessage').click(function() {
			
			for (var s in myTrabajador.data) {
				if (myTrabajador.data[s]["Trabajador_Id"] == this.id){
					console.log(myTrabajador.data[s]);
					//console.log(myData.data[s]['Latitud']);
					$('.modal-body #TrabFecha').val(myTrabajador.data[s]['Fecha_creacion']);
					$('.modal-body #TrabNombre').val(myTrabajador.data[s]['Nombre']);
					$('.modal-body #TrabApellido').val(myTrabajador.data[s]['Apellido']);
					$('.modal-body #TrabTelefono').val(myTrabajador.data[s]['Telefono']);
					$('.modal-body #TrabEmail').val(myTrabajador.data[s]['Email']);
					$('.modal-body #TrabId').val(myTrabajador.data[s]['Trabajador_Id']);					
				}
			}
			$('#myModalTrabajadores').modal('show');
		});	 
	});	
	//END OF MODAL DEL DISPOSITIVO

	//MODAL DEL CLIENTE
	$("#modCli").click(function(){
		console.log(myClient);
		for (var s in myClient.data) {
			console.log(myClient.data[s]);
			$('.modal-body #Nombre_empresa').val(myClient.data[0]['Nombre']);
			$('.modal-body #Comprado').val(myClient.data[0]['Comprado']);
			$('.modal-body #Comentarios').val(myClient.data[0]['Comentarios']);
			$('.modal-body #NIF').val(myClient.data[0]['NIF']);
			$('.modal-body #nombre_contacto').val(myClient.data[0]['Nombre_contacto']);
			$('.modal-body #Apellido').val(myClient.data[0]['Apellido_contacto']);
			$('.modal-body #Correo').val(myClient.data[0]['Correo_contacto']);
			$('.modal-body #Telefono').val(myClient.data[0]['Tel_contacto']);
		}

		$('#myModalCliente').modal('show');
	});
	//END OF MODAL DEL CLIENTE

	$('.modal-backdrop').click(function(){
		this.remove();
	});
	$('#myModalTrabajadores').on('hidden.bs.modal', function () {
		$('.modal-backdrop').remove();
	});
	$('#myModalDispositivo').on('hidden.bs.modal', function () {
		
			$('.modal-backdrop').remove();
				
	});
