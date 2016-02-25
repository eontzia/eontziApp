	var reglas = {  
		nombre_empresa: {required:true,minlength:2},
		nombre: {required:true,minlength:2,lettersonly: true},  
		apellido:{required:true,minlength:2,lettersonly: true},
		correo:{required: true, email:true}, 
		telefono:{required:true,intlphone:true,minlength:9}	 
	};

	var mensajes = {  
		nombre_empresa:{minlength:"El nombre demasiado corto"},
		nombre: {required:"Nombre Requerido",minlength:"El nombre demasiado corto"}, 
		apellido:{required:"Nombre required",minlength:"El apellido demasiado corto"}, 
		correo:{required:"Email Requerido", email:"Formato de Email incorrecto"},  
		telefono:{required:"Falta número",minlength:"El número demasiado corto"}	
	};

	jQuery.validator.addMethod('intlphone', function(value) { 
		//return (value.match(/^((\+)?[1-9]{1,2})?([-\s\.])?((\(\d{1,9}\))|\d{1,4})(([-\s\.])?[0-9]{1,9}){1,2}(\s*(ext|x)\s*\.?:?\s*([0-9]+))?$/));
		return (value.match(/^\+?\d{1,3}?[- .]?\(?(?:\d{2,3})\)?[- .]?\d\d\d[- .]?\d\d\d\d$/));
	}, 'Por favor, introduce número válido, máx 12 caracteres(código de país incluido)p.ej:+34XXXXXXXXX');
	jQuery.validator.addMethod("lettersonly", function(value, element) {
		return this.optional(element) || /^[a-z]+$/i.test(value);
	}, "Por favor, introduce sólo las letras"); 

	$(document).ready(function(){ 
		//leer cookie
		var cookieValue=$.cookie('info');
		if(cookieValue==1){
			scrollto();
			$.removeCookie('info');
		} 
		$("#formreg").validate ({  
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
	function scrollto(){
		
		$("#formreg").ScrollTo({
			duration: 2600,
			duratonMode: 'all'
		});
			//$.removeCookie('info');	
		
	}
