//***** MAPA MODAL *****
var mapa=null;  
var geoActivado=false;
var marcadores=[];
var infoWindow = null;
var input=null;
var searchBox=null;

	function initMap() {
		var punto=new google.maps.LatLng(43.308615, -1.893189);
		var config={
			zoom:12,
			center:punto,
			mapTypeId:google.maps.MapTypeId.ROADMAP
		};
		/*if(mapa){
			return;
		}*/
		if(!mapa){			
			mapa = new google.maps.Map($('#map-canvas')[0],config);
			input = $('#pac-input')[0];
			searchBox = new google.maps.places.SearchBox(input);		
			mapa.controls[google.maps.ControlPosition.TOP_LEFT].push(input);
		}		
		
		//SearchBox
		// Create the search box and link it to the UI element.
		/*var input = $('#pac-input')[0];
		var searchBox = new google.maps.places.SearchBox(input);		
		mapa.controls[google.maps.ControlPosition.TOP_LEFT].push(input);*/

		// Bias the SearchBox results towards current map's viewport.
		mapa.addListener('bounds_changed', function() {
			searchBox.setBounds(mapa.getBounds());
		});
		
		// [START region_getplaces]
		// Listen for the event fired when the user selects a prediction and retrieve
		// more details for that place.
		searchBox.addListener('places_changed', function() {
			var places = searchBox.getPlaces();

			if (places.length == 0) {
				return;
			}

			// Clear out the old markers.
			marcadores.forEach(function(marker) {
				marker.setMap(null);
			});
			marcadores = [];

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
				marcadores.push(new google.maps.Marker({
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

		google.maps.event.addListener(mapa,"click",function(event){
			
			var coordenadas=event.latLng.toString();
			coordenadas=coordenadas.replace("(", "");
			coordenadas=coordenadas.replace(")", "");
			var lista=coordenadas.split(",");

			$('#inputLatitude').val(lista[0].trim());
			$('#inputLongitude').val(lista[1].trim());
			nuevoMarcador(lista[0],lista[1]);			
		});

	}

	function nuevoMarcador(lat,lon){
		quitarMarcadores(marcadores);
		var punto=new google.maps.LatLng(lat,lon);
		var titulo="Latitud:"+lat+" Longitud: "+lon;
		var marcador=new google.maps.Marker({
			position:punto,
			title:titulo,
			map:mapa,
			animation:google.maps.Animation.DROP,
			draggable:false
		});
		marcador.setMap(mapa);
		marcadores.push(marcador);

	}
	//Borrar marcadores nuevos o bd
	function quitarMarcadores(marcadores){
		for(i in marcadores){
			marcadores[i].setMap(null);
		}
		marcadores=[];
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