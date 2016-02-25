	var reglas = {  
		nombre: {required:true,minlength:2,lettersonly: true},
		asunto:{required:false,minlength:2},	
		correo:{required: true, email:true}
	};
	var mensajes = {		
		nombre: {required:"Nombre Requerido",minlength:"El nombre demasiado corto"},
		asunto:{required:"Se recomienda introducir asunto",minlength:"Asunto demasiado corto"},		
		correo:{required:"Email Requerido", email:"Formato de Email incorrecto"}
	}; 	

	$(document).ready(function(){
		$("#formcontacto").validate ({
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
	});

	function crearCookie(){
		$.cookie("info",1);

	}

		