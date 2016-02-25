var edicion=false;
var grados=1;
$(document).ready(function(){
	
		window.setInterval(function(){

		if(grados>366){
			grados=1;
			$('#cabecera-perfil').css('background-image','linear-gradient('+grados+'deg, #158199, #159957)');
			grados=grados+5;
			//console.log(grados);
		}else{
			$('#cabecera-perfil').css('background-image','linear-gradient('+grados+'deg, #158199, #159957)');
			grados=grados+5;
			//console.log(grados);
		}	
		
	}, 300);

	
	
	//***** VALIDACIONES ******
	//VALIDAR EL TRABAJADOR
	var reglas = {  
		inputNombre: {required:true,minlength:3},  
		inputApellido:{required:true,minlength:3},
		inputEmail:{required: true, email:true}, 
		inputTelefono:{required:true,digits:true,minlength:9},

	};

	var mensajes = {  
		inputNombre: {required:"Nombre requerido",minlength:"El nombre demasiado corto"}, 
		inputApellido:{required:"Nombre requerido",minlength:"El apellido demasiado corto"}, 
		inputEmail:{required:"Email requerido", email:"Formato de Email incorrecto"},  
		inputTelefono:{required:"Falta numero", digits:"aceptados solo numeros",min:"el numero incorrecto"}		
	};

	$("#modificarPerfil").validate({  
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


	$('#btnHabilitar').click(function(event){
		
		console.log(edicion);
		var allInputs= $(':input:text');
		if(!edicion){
			$.each(allInputs,function(key,value){
				$(value).attr('readonly',false);
			});
			edicion=true;
			$('#btnHabilitar').text("Deshabilitar edición");
		}else{
			$.each(allInputs,function(key,value){
				$(value).attr('readonly',true);
			});
			edicion=false;
			$('#btnHabilitar').text("Habilitar edición");
		}		
		return false;
	});

	$('#inputURL').blur(function(){
		var url=$('#inputURL').val();
		$('#img-perfil').attr('src',url);

	});
});