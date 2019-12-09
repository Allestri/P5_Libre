// ------ Google Map ------ //
// Test function refresh & fetch ajax.

// Test info Windows - not finished.

function displayMap()
{
	var self = this;
	var gmap = null;
	var markers = [];
	var infoWindow = null;
	
	
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
                infoWindow = new google.maps.InfoWindow;
                
                
                google.maps.event.addListener(infoWindow, 'domready', ()=> {
                	$('#thumbnail-wrapper').click(()=> {
                		console.log('image-clicked');
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
	                
	                //let point = new points(data, i);
	                //let myInfos = new imagesInfos(data);
	                
	                let marker = new google.maps.Marker({
	                    position: latlngset,
	                    map: gmap,
	                    icon: iconImg,
	                    title: 'Marker : ' + data[i].name
	                });
	                
	                
	               
	                
	                let markerId = data[i].id;

	                let windowContent = "<div id='thumbnail-wrapper'></div>";
	                let filename = data[i].filename;
	                
	                marker.filename = filename;
	                
	                //console.log(marker);	                
	                
	                // Event listener
	                marker.addListener('click', ()=> {
	                	//console.log(infoWindow);
	                	infoWindow.setContent(windowContent);
	                		                	
	                	// info button
	                	this.toggleInfoButton();
	                	// Get infos
	                	this.getInfos(markerId);
	                	
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
		
		if ($('#info-toggle').hasClass("active")) {
			
			$('#info-toggle').removeClass('active');
			
		} else {
			$('#info-toggle').addClass("active");
		}
		
	};
	
	// Get informations on a clicked marker ( marker & image datas and comments)
	this.getInfos = function(markerId) {
		console.log(markerId);
		$.ajax({
			type: "GET",
	        url: "http://projetlibre/map/infos",
	        data: "id=" + markerId,
	        dataType: "JSON",
	        success: (data)=> {
	        	console.log('Infos success');
	        	console.log(data);
	        	let infos = new postInfos(data);
	        	infos.displayInfos();
	        	infos.togglePanel();
	        	infos.displayComments();	
	        	
	        },
	    	error : function(result, status, error){	
	    		console.log('erreur fetch infos' + error);
	    	}
	    });
	};
	
	
	this.getThumbnail = function (filename) {
				
		var dir = "uploads/thumbnails";
		var file = dir + "/" + filename;
		
	    $.ajax({
	    	type: "GET",
	        url: file,
	        success: function(result) {
	        	$('#thumbnail-wrapper').append($('<img id="thumbnail" />').attr('src', file));
	        },
	    	error : function(result, status, error){
	    		console.log('erreur');
	    	}
	    });
		
	};
	
	this.getId = function(filename) {
		console.log(filename);
		return $.ajax({
			type:"POST",
			url: "http://projetlibre/map/getid",
			data: {filename : filename[1]}
		});
		
		//showInfos(id);
		//showComments(id);
		
	};

	// debugging
	function displayId(data, status, object) {
		console.log(data);
	};
	
	// Sets the unique Post ID value on inputs
	function setValues(data, status, object){
		
		var id = data;
		
		var elements = $(".postId");
		elements.val(id);

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
				
				// Display / Hide ( note : refer to imageviewer.js for the hiding method - WIP )
				//$('#img-wrapper').prepend($('<img id="image-midsize" />').attr('src', file));
				//showComments();
				infoWindow.close();
				self.toggleInfoButton();
				$('#image-midsize').attr('src', file);
				$('#overlay').show();
				// Init social functionalities.
				let social = new Social();
				social.initialization();
				
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
	          zoom: 7,
	          styles: [
	        	    {
	        	        "featureType": "all",
	        	        "elementType": "all",
	        	        "stylers": [
	        	            {
	        	                "saturation": "32"
	        	            },
	        	            {
	        	                "lightness": "-3"
	        	            },
	        	            {
	        	                "visibility": "on"
	        	            },
	        	            {
	        	                "weight": "1.18"
	        	            }
	        	        ]
	        	    },
	        	    {
	        	        "featureType": "administrative",
	        	        "elementType": "labels",
	        	        "stylers": [
	        	            {
	        	                "visibility": "off"
	        	            }
	        	        ]
	        	    },
	        	    {
	        	        "featureType": "landscape",
	        	        "elementType": "labels",
	        	        "stylers": [
	        	            {
	        	                "visibility": "off"
	        	            }
	        	        ]
	        	    },
	        	    {
	        	        "featureType": "landscape.man_made",
	        	        "elementType": "all",
	        	        "stylers": [
	        	            {
	        	                "saturation": "-70"
	        	            },
	        	            {
	        	                "lightness": "14"
	        	            }
	        	        ]
	        	    },
	        	    {
	        	        "featureType": "poi",
	        	        "elementType": "labels",
	        	        "stylers": [
	        	            {
	        	                "visibility": "off"
	        	            }
	        	        ]
	        	    },
	        	    {
	        	        "featureType": "road",
	        	        "elementType": "labels",
	        	        "stylers": [
	        	            {
	        	                "visibility": "off"
	        	            }
	        	        ]
	        	    },
	        	    {
	        	        "featureType": "transit",
	        	        "elementType": "labels",
	        	        "stylers": [
	        	            {
	        	                "visibility": "off"
	        	            }
	        	        ]
	        	    },
	        	    {
	        	        "featureType": "water",
	        	        "elementType": "all",
	        	        "stylers": [
	        	            {
	        	                "saturation": "100"
	        	            },
	        	            {
	        	                "lightness": "-14"
	        	            }
	        	        ]
	        	    },
	        	    {
	        	        "featureType": "water",
	        	        "elementType": "labels",
	        	        "stylers": [
	        	            {
	        	                "visibility": "off"
	        	            },
	        	            {
	        	                "lightness": "12"
	        	            }
	        	        ]
	        	    }
	        	]
	    
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

