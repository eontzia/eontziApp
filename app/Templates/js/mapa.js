	var mapa=null;
	var marcadores_bd=[];
	var ruta=[];
	var form=null;
	var enRuta=null;
	var geoActivado=null;
	var infoWindow = null;

	var URL="http://eontzia.zubirimanteoweb.com/";
	//var URL="http://localhost/workspace/eontziApp/";

	//
	function nuevoMarcadorBD(id,lat,lon,titulo,tipo,fecha,volumen,bateria,direccion,timeout){
		var estadoBatera="";
		if(bateria>0&&bateria<=25){
			estadoBatera="La batería está cerca de agotarse. "+bateria+"%";
		}else if(bateria>25&&bateria<55){
			estadoBatera="Buena, carga media. "+bateria+"%";
		}else{
			estadoBatera="Muy buena, carga completa o casi completa. "+bateria+"%";
		}
		//quitarMarcadores(marcadores_bd);
		var strImg=URL+"app/Templates/img/Container/tipo_"+tipo+".png";		
		var img={
			url:strImg,
			size: new google.maps.Size(45, 45)};

		var punto=new google.maps.LatLng(lat,lon);
		var marcador=new google.maps.Marker({
			id:id,
			tipo:tipo,
			position:punto,
			title:titulo,
			map:mapa,
			animation:google.maps.Animation.DROP,
			draggable:false,
			icon:img
		});
		window.setTimeout(function() {			
			marcadores_bd.push(marcador);			
		}, timeout);
		//Añadir evento al marcador
		marcador.addListener("click", function(){
			
			//getStreet(lat,lon,id,fecha,volumen,bateria,marcador);
			var PBarVolumen='<div class="progress progreso">';
				PBarVolumen+='<div id="pBarVolumen" class="progress-bar" role="progressbar" aria-valuenow="'+volumen+'" aria-valuemin="0" aria-valuemax="100" ';
				PBarVolumen+='>';
				PBarVolumen+='</div>';
				PBarVolumen+='</div>';
						
			var PBarBateria='<div class="progress progreso">';
				PBarBateria+='<div id="pBarBateria" class="progress-bar" role="progressbar" aria-valuenow="'+bateria+'" aria-valuemin="0" aria-valuemax="100" ';
				PBarBateria+='>';
				PBarBateria+='</div>';
				PBarBateria+='</div>';

			var content = '<div id="iw_container">' +
              '<div class="iw_title"><p>Id: '+id+'</p></div>' +
              '<div class="iw_content">'+
              'Volumen: '+volumen+'% '+PBarVolumen+
              'Estado de batería: '+estadoBatera+PBarBateria+
              'Fecha de medición: '+fecha+'<br>'+
              'Dirección: '+direccion+'</div>' +
              '</div>';
			if(!infoWindow){
  				infoWindow=new google.maps.InfoWindow({map: mapa});
  					infoWindow.setContent(content);
			}else{
				infoWindow.setContent(content);
			}

			infoWindow.open(mapa, marcador);

			//cuando lo haya cargado en el dom
			google.maps.event.addListener(infoWindow, 'domready', function() {
				//VOLUMEN
				if(volumen>85){
						$('#pBarVolumen').removeClass().addClass('progress-bar danger').css('width',volumen+'%');
					}else if(volumen>=55&&volumen<=85){
						$('#pBarVolumen').removeClass().addClass('progress-bar warning').css('width',volumen+'%');
					}else{
						$('#pBarVolumen').removeClass().addClass('progress-bar success').css('width',volumen+'%');
					}
				
					//BATERIA
					if(bateria>0&&bateria<=25){
						$('#pBarBateria').removeClass().addClass('progress-bar danger').css('width',bateria+'%');
					}else if(bateria>25&&bateria<55){
						$('#pBarBateria').removeClass().addClass('progress-bar warning').css('width',bateria+'%');
					}else{
						$('#pBarBateria').removeClass().addClass('progress-bar success').css('width',bateria+'%');
					}

			});
			centrarEnMapa(marcador.id);			
		});
	}

	function centrarEnMapa(id){
		$.each(marcadores_bd, function(i,item){						
			if(item.id==id){			
				mapa.setCenter(item.position);
				aplicarZoom(mapa,14,mapa.getZoom());
				return false;
			}			
		});
	}
	// the smooth zoom function
	function aplicarZoom (map, max, cnt) {
	    if (cnt >= max) {
	            return;
	        }
	    else {
	        z = google.maps.event.addListener(map, 'zoom_changed', function(event){
	            google.maps.event.removeListener(z);
	            aplicarZoom(map, max, cnt + 1);
	        });
	        setTimeout(function(){map.setZoom(cnt)}, 80); // 80ms is what I found to work well on my system -- it might not work well on all systems
	    }
	}
	
	//Borrar marcadores nuevos o bd
	function quitarMarcadores(marcadores){
		if (marcadores.length()!=0){
			for(i in marcadores){
				marcadores[i].setMap(null);
			}
			marcadores=[];
		}		
	}

	//Funcion para traer los puntos insertados en bd
	function getPosiciones(){
		$.ajax({
			type:"GET",
			//url:"http://localhost/workspace/eontziApp/app/getAllPos",	
			url:URL+"app/getAllPos",
			dataType:"JSON",
			data:"",
			success:function(data){
				console.log(data);
				if(data.estado=="OK"){
					$.each(data.mensaje,function(i,item){
						nuevoMarcadorBD(item.Dispositivo_Id,item.Latitud,item.Longitud,"Contenedor "+i,item.Tipo,item.Fecha,item.Volumen,item.Bateria,item.Direccion,i*150);
					})
				}
			},
			beforeSend:function(){

			},
			complete:function(){

			},
			error:function(){

			}
		});
	}

	function getLocalizacion(){
		navigator.geolocation.getCurrentPosition(function(position) {
			var pos = {
				lat: position.coords.latitude,
				lng: position.coords.longitude
  			};

			if(!infoWindow){
				infoWindow=new google.maps.InfoWindow({map: mapa});
			}
			
			infoWindow.setPosition(pos);
			infoWindow.setContent('Posición actual (aproximada)');
			mapa.setCenter(pos);
		}, 
		function() {
				//handleLocationError(true, infoWindow, map.getCenter());
				geoActivado=false;
		});
	}

	function newRuta(){		
		while(enRuta){
		}
	}	

	function initMap() {
		var punto=new google.maps.LatLng(43.308615, -1.893189);
		var config={
			zoom:12,
			center:punto,
			mapTypeId:google.maps.MapTypeId.ROADMAP
		};

		mapa = new google.maps.Map($('#mapa')[0],config);
		
		//SearchBox
		// Create the search box and link it to the UI element.
		var input = $('#pac-input')[0];
		var searchBox = new google.maps.places.SearchBox(input);		
		mapa.controls[google.maps.ControlPosition.TOP_LEFT].push(input);

		// Bias the SearchBox results towards current map's viewport.
		mapa.addListener('bounds_changed', function() {
			searchBox.setBounds(mapa.getBounds());
		});
		var markers = [];
		// [START region_getplaces]
		// Listen for the event fired when the user selects a prediction and retrieve
		// more details for that place.
		searchBox.addListener('places_changed', function() {
			var places = searchBox.getPlaces();

			if (places.length == 0) {
				return;
			}

			// Clear out the old markers.
			markers.forEach(function(marker) {
				marker.setMap(null);
			});
			markers = [];

			// For each place, get the icon, name and location.
			var bounds = new google.maps.LatLngBounds();
			places.forEach(function(place) {
				var icon = {
					url: place.icon,
					size: new google.maps.Size(71, 71),
					origin: new google.maps.Point(0, 0),
					anchor: new google.maps.Point(17, 34),
					scaledSize: new google.maps.Size(25, 25)
				};

				// Create a marker for each place.
				markers.push(new google.maps.Marker({
					map: mapa,
					icon: icon,
					title: place.name,
					position: place.geometry.location
				}));

				if (place.geometry.viewport) {
					// Only geocodes have viewport.
					bounds.union(place.geometry.viewport);
				} else {
					bounds.extend(place.geometry.location);
				}
			});
			mapa.fitBounds(bounds);
		});
		// [END region_getplaces]
		
		if(geoActivado){
			getLocalizacion();
		}else{
			if (navigator && navigator.geolocation) {
				geoActivado=true;
				getLocalizacion();
			}else {
			    // Browser doesn't support Geolocation
				handleLocationError(false, infoWindow, map.getCenter());
				geoActivado=false;
			}
		}
		
		getPosiciones();
		
	}

	
