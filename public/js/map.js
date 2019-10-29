// ------ Google Map ------ //
// Test function refresh & fetch ajax.

// Test info Windows - not finished.

function displayMap()
{
	var self = this;
	var gmap = null;
	var markers = [];
	
	
	
	this.initialization = function() {
		this.initMap();
		this.refreshMap();
		this.markerCluster = new MarkerClusterer(gmap, markers,{
			imagePath: 'images/assets/m'
		});
	}
	
	// Fetch the points
	this.getPoints = function(refresh = false) {
		$.ajax({
	        type: "GET",
	        url: "http://projetlibre/map/api",
	        dataType: "json",
	        success: (data)=> {
	           //console.log(data[0].lng, data[0].lat);
	        	
	           //console.log(data);
	            // Info window
                var infoWindow = new google.maps.InfoWindow;
                
                
                google.maps.event.addListener(infoWindow, 'domready', ()=> {
                	
                	$('#thumbnail').click(()=> {
                		this.getImageFullScreen();
                	})
                	
                });
                
                // Info button style toggle.
                google.maps.event.addListener(infoWindow, 'closeclick', ()=> {
                	
                	$('#info-toggle').removeClass('active');
                	//$('#image-info-panel').hide();
                	
                });

                
                if(refresh === true){
                	// Unset all markers
                    var i = 0, l = markers.length;
                    for (i; i<l; i++) {
                        markers[i].setMap(null)
                    }
                    markers = [];

                    // Clears all clusters and markers from the clusterer.
                   this.markerCluster.clearMarkers();
                }
                
                
	            // Loop through each locations.
	            for(var i = 0; i < data.length; i++){
	            	
	            	// Position
	                var latlngset = new google.maps.LatLng(data[i].lat, data[i].lng);
	                
	                // Customizing marker
	                let iconImg;
	                if( (data[i].groupimg_id) > 1){
	                	iconImg = 'images/camera-pin-min.png';
	                } else {
	                	iconImg = 'images/drone-pin-min.png';
	                }
	                
	                let point = new points(data, i);
	                //let infos = new imagesInfos(data, i);
	                
	                let marker = new google.maps.Marker({
	                    position: latlngset,
	                    map: gmap,
	                    point: point,
	                    icon: iconImg,
	                    title: 'Marker : ' + data[i].name
	                });
	                
	                //console.log(marker.point);
	                
	                let markerId = data[i].id;

	                var windowContent = "<div id='thumbnail-wrapper'></div>";
	                let filename = data[i].filename;
	                
	                marker.filename = filename;


	                
	                // Event listener
	                marker.addListener('click', ()=> {
	                	//console.log(infoWindow);
	                	infoWindow.setContent(windowContent);
	                	
	                	
	                	// info button
	                	this.toggleInfoButton();
	                	
	                	// Get infos
	                	//this.getInfos(markerId);
	                	marker.point.displayInfos();
	                	
	                	// Test marker manual position ( draggable )
	                	var position = marker.getPosition();
	                	var latitude = position.lat();
	                	console.log(latitude);
	                	
	                	infoWindow.open(map, marker);
	                	

	                	
	                	this.getThumbnail(filename);
	                		

	                	
	                	
	                });
	                marker.setMap(gmap);
	                markers.push(marker);
	            }
	    		// Clusterer
	    		//this.markerCluster.setMap(null);
	    		this.markerCluster = new MarkerClusterer(gmap, markers,{
	    			imagePath: 'images/assets/m'
	    		});
	        },
	        error : function(result, status, error){
	            console.log('erreur');
	        }
	    });
	}
	
	this.toggleInfoButton = function() {
		
		$('#info-toggle').addClass("active");
	};
	
	// NOTE WORK IN PROGRESS
	
	this.getInfos = function(markerId) {
				
		$.ajax({
			type: "GET",
	        url: "http://projetlibre/map/infos",
	        data: "id=" + markerId,
	        dataType: "JSON",
	        success: (data)=> {
	        	console.log('Infos success');
	        	console.log(data);
	        	let infos = new imagesInfos(data);
	        	infos.displayInfos();
	        },
	    	error : function(result, status, error){
	    		console.log('erreur fetch infos');
	    	}
	    });
	};
	
	
	this.getThumbnail = function (filename) {
		
		//this.getImageFullScreen(filename);

		
		//console.log(filename);
		var dir = "uploads/thumbnails";
		var file = dir + "/" + filename;
		
	    $.ajax({
	    	type: "GET",
	        url: file,
	        success: function(result) {
	        	$('#thumbnail-wrapper').append($('<img id="thumbnail" />').attr('src', file));
	        	//$('#thumbnail-wrapper').append('<img class="myThumbnail" src=' + file + ' />');
	        },
	    	error : function(result, status, error){
	    		console.log('erreur');
	    	}
	    });
		
	};
	
	this.getId = function(filename) {
		
		return $.ajax({
			type:"POST",
			url: "http://projetlibre/map/getid",
			data: {filename : filename[1]}
		});
		
		//showInfos(id);
		showComments(id);
		
	};

	// debugging
	function displayId(data, status, object) {
		console.log(data);
	};
	
	// Sets the unique ID value on inputs
	function setValues(data, status, object){
		
		var id = data;
		console.log(id);
		
		var elements = $(".imgId");
		elements.val(id);
		console.log(elements);
		
		
	};
	
	this.getImageFullScreen = function (){
			
			let src = $('#thumbnail').attr('src');
			let filename = src.split("uploads/thumbnails/");
			
			var dir = "uploads/photos";
			var file = dir + "/" + filename[1];
			
			// Requête recupérer ID image via filename
			this.getId(filename).done(setValues);
			
			$.ajax({
				type:"GET",
				url: file,
				success: function(result){
					//$('#overlay').append('<div class="img-wrapper"></div>');
					//$('.img-wrapper').append($('<img />').attr({ src: file, class: 'image-midsize' } ));
					
					// WIP
					// Display / Hide ( note : refer to imageviewer.js for the hiding method - WIP )
					//$('#img-wrapper').prepend($('<img id="image-midsize" />').attr('src', file));
					showComments();
					$('#image-midsize').attr('src', file);
					$('#overlay').show();
					
				},
				error: function(result, status, error){
					console.log('erreur');
				}
			});
			
		
	};
	
	// Init Google Map
	this.initMap = function () {
	    var brittany = {lat: 47.847963, lng: -1.465993};
	    gmap = new google.maps.Map(document.getElementById('map'), {
	          center: brittany,
	          zoom: 7
	    });
	    
	    this.getPoints();
	
	};	
	
	// Refreshes the map.
	this.refreshMap = function () {
		$("#refresh").click(function () {
			self.getPoints(true);
			console.log("Map Refreshed !");
		});
	};	
	
}

function initApp(){
	var myMap = new displayMap();
	myMap.initialization();
}

