	var mapa=null;
	var marcadores_bd=[];
	var ruta=[];
	var form=null;
	var enRuta=null;
	var geoActivado=null;
	var infoWindow = null;

	//
	function nuevoMarcadorBD(id,lat,lon,titulo,tipo,fecha,volumen,bateria,timeout){
		//quitarMarcadores(marcadores_bd);
		var strImg="http://eontzia.zubirimanteoweb.com/app/Templates/img/Container/tipo_"+tipo+".png";		
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
			
			getStreet(lat,lon,id,fecha,volumen,bateria,marcador);
		});
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

/*
	function getStreet(lat,lon){
		$.ajax({
			tipe:"GET",
			url:"https://maps.googleapis.com/maps/api/geocode/json?latlng="+lat+","+lon+"&location_type=GEOMETRIC_CENTER&key=AIzaSyDD3NDLaalLek6GbFmNwipfqxJeuJeUrG4",
			dataType:"JSON",
			data:"",
			success:function(data){
				console.log(data);
				if(data.status=="OK"){
					while($('#contenidoIW').size()==0){}
					$('#contenidoIW').html('Dirección: '+data.results[0].formatted_address);
				}
			}
		});
	}
	*/
function getStreet(lat,lon,id,fecha,volumen,bateria,marcador){
		$.ajax({
			tipe:"GET",
			url:"https://maps.googleapis.com/maps/api/geocode/json?latlng="+lat+","+lon+"&location_type=GEOMETRIC_CENTER&key=AIzaSyDD3NDLaalLek6GbFmNwipfqxJeuJeUrG4",
			dataType:"JSON",
			data:"",
			success:function(data){
				console.log(data);
				if(data.status=="OK"){
					//while($('#contenidoIW').size()==0){}
					//$('#contenidoIW').html('Dirección: '+data.results[0].formatted_address);
					var colores=['red','green','orange'];
					var PBarVolumen='<div class="progress" style="width:150px;height:12px">';
  						PBarVolumen+='<div class="progress-bar" role="progressbar" aria-valuenow="'+volumen+'" aria-valuemin="0" aria-valuemax="100" ';
  						if(volumen>85){
  							PBarVolumen+='style="width:'+volumen+'%;background-color:'+colores[1]+'">';
  						}else if(volumen>=55&&volumen<=85){
  							PBarVolumen+='style="width:'+volumen+'%;background-color:'+colores[2]+'">';
  						}else{
  							PBarVolumen+='style="width:'+volumen+'%;background-color:'+colores[0]+'">';
  						}

    					PBarVolumen+='<span class="sr-only">'+volumen+'% Complete</span>';
  						PBarVolumen+='</div>';
						PBarVolumen+='</div>';

					var PBarBateria='<div class="progress" style="width:150px;height:12px">';
  						PBarBateria+='<div class="progress-bar" role="progressbar" aria-valuenow="'+bateria+'" aria-valuemin="0" aria-valuemax="100" ';
  						if(bateria>=0&&bateria<=15){
  							PBarBateria+='style="width:'+bateria+'%;background-color:'+colores[0]+'">';
  						}else if(bateria>15&&bateria<45){
  							PBarBateria+='style="width:'+bateria+'%;background-color:'+colores[2]+'">';
  						}else{
  							PBarBateria+='style="width:'+bateria+'%;background-color:'+colores[1]+'">';
  						}

    					PBarBateria+='<span class="sr-only">'+bateria+'% Complete</span>';
  						PBarBateria+='</div>';
						PBarBateria+='</div>';

					var content = '<div id="iw_container">' +
		              '<div class="iw_title"><p>Id: '+id+'</p></div>' +
		              '<div class="iw_content"><p id="contenidoIW">'+
		              'Volumen: '+volumen+'% '+PBarVolumen+
		              'Batería: '+bateria+'% '+PBarBateria+
		              'Fecha de medición: '+fecha+'<br>'+
		              'Dirección: '+data.results[0].formatted_address+'</p></div>' +
		              '</div>';
					if(!infoWindow){
		  				infoWindow=new google.maps.InfoWindow({map: mapa});
		  				infoWindow.setContent(content);
						//getStreet(lat,lon);
					}else{
						infoWindow.setContent(content);
						//getStreet(lat,lon);
					}			
					infoWindow.open(mapa, marcador);
					//getStreet(lat,lon);
					$.each(marcadores_bd, function(i,item){
						//if(marcador.id=='2'){
							if(item.id==marcador.id){
							//alert(item.id+";"+item.tipo+";"+item.title);
							mapa.setCenter(marcador.position);
							return false;
							}	
						//}
										
					});

				}
			}
		});
	}

	//Funcion para traer los puntos insertados en bd
	function getPosiciones(){
		$.ajax({
			type:"GET",
			//url:"http://localhost/workspace/eontziApp/app/getAllPos",	
			url:"http://eontzia.zubirimanteoweb.com/app/getAllPos",
			dataType:"JSON",
			data:"",
			success:function(data){
				console.log(data);
				if(data.estado=="OK"){
					$.each(data.mensaje,function(i,item){
						nuevoMarcadorBD(item.Dispositivo_Id,item.Latitud,item.Longitud,"Contenedor "+i,item.Tipo,item.Fecha,item.Volumen,item.Bateria,i*150);
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
		
		$("#iniRuta").click(function(){
			if(enRuta){
				enRuta=false;
			}else{
				newRuta();
			}
						
		});
	}

	
