	var mapa=null;
	var marcadores_bd=[];
	var ruta=[];
	var form=null;
	var enRuta=null;
	var geoActivado=null;
	var infoWindow = null;

	//
	function nuevoMarcadorBD(lat,lon,titulo,timeout){
		//quitarMarcadoresBD(marcadores_nuevos);
		window.setTimeout(function() {
			var punto=new google.maps.LatLng(lat,lon);
			var marcador=new google.maps.Marker({
				position:punto,
				title:titulo,
				map:mapa,
				animation:google.maps.Animation.DROP,
				draggable:false
			});
			marcador.setMap(mapa);
			marcadores_bd.push(marcador);
		}, timeout);

	}
	//Borrar marcadores nuevos o bd
	function quitarMarcadores(marcadores){
		for(i in marcadores){
			marcadores[i].setMap(null);
		}
		marcadores=[];
	}	

	//Funcion para traer los puntos insertados en bd
	function getPosiciones(){
		$.ajax({
			type:"GET",			
			url:"http://eontzia.zubirimanteoweb.com/app/getAllPos",
			dataType:"JSON",
			data:"",
			success:function(data){
				console.log(data);
				if(data.estado=="ok"){
					$.each(data.mensaje,function(i,item){
						nuevoMarcadorBD(item.latitud,item.longitud,item.titulo,i*150);
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
      			infoWindow=new google.maps.InfoWindow({map: mapa});
				infoWindow.setPosition(pos);
				infoWindow.setContent('Posición actual (aproximada)');
				mapa.setCenter(pos);
			}, 
			function() {
      			handleLocationError(true, infoWindow, map.getCenter());
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
			zoom:14,
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
		//Evento Click al mapa
		google.maps.event.addListener(mapa,"click",function(event){
			
			var coordenadas=event.latLng.toString();
			var titulo=prompt("Titulo:")
			coordenadas=coordenadas.replace("(", "");
			coordenadas=coordenadas.replace(")", "");
			var lista=coordenadas.split(",");

			//Abrir desplegable 1 y focus al añadir titulo
			if($('#acAgregarP').hasClass && $('#acAgregarP').attr('class')=="collapsed" && $('#acAgregarP').attr('aria-expanded')=="false"){
				$('#acAgregarP').removeClass("collapsed").attr("aria-expanded","true");
				$('#collapseOne').attr("aria-expanded","true").addClass("in");
			}
						
			form.find("input[name=titulo]").val(titulo).focus();
			form.find("input[name=lat]").val(lista[0]);
			form.find("input[name=long]").val(lista[1]);
			nuevoMarcador(lista[0],lista[1],titulo);
			
		});

		//Al hacer click en el boton Guardar enviar y guardar en BBDD
		$("#btnGuardar").click(function(){
			addPosicion();			
		});

		$("#iniRuta").click(function(){
			if(enRuta){
				enRuta=false;
			}else{
				newRuta();
			}
						
		});
	}

	
