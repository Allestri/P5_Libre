// JS related to Google map and App overlay //

function displayMap()
{
	var self = this;
	var gmap = null;
	var markers = [];
	var infoWindow = null;
	var url = "http://projetlibre/map";
	
	
	this.initialization = function() {
		this.initMap();
		this.markerCluster = new MarkerClusterer(gmap, markers,{
			imagePath: 'images/assets/m'
		});
	}
	
	// Fetch the points
	this.getPoints = function(refresh = false) {
		$.ajax({
	        type: "GET",
	        url: url + "/api",
	        dataType: "json",
	        success: (data)=> {
	        	console.log(data);
	        	
	            // Info window
                infoWindow = new google.maps.InfoWindow;
                
                
                google.maps.event.addListener(infoWindow, 'domready', ()=> {
                	$('#thumbnail-wrapper').click(()=> {
                		this.getImageFullScreen();
                	})
                	
                });
                
                // Info button style toggle.
                google.maps.event.addListener(infoWindow, 'closeclick', ()=> {
                	
                	$('.info-toggle').removeClass('active');
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
	                	iconImg = 'images/marker-pastel.png';
	                } else {
	                	iconImg = 'images/marker-pastel.png';
	                }
	                
	                //let point = new points(data, i);
	                //let myInfos = new imagesInfos(data);
	                
	                let marker = new google.maps.Marker({
	                    position: latlngset,
	                    map: gmap,
	                    icon: iconImg,
	                    title: 'Marker'
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
		
		if ($('.info-toggle').hasClass("active")) {
			
			$('.info-toggle').removeClass('active');
			
		} else {
			$('.info-toggle').addClass("active");
		}
		
	};
	
	// Get informations on a clicked marker ( marker & image datas and comments)
	this.getInfos = function(markerId) {
		console.log(markerId);
		$.ajax({
			type: "GET",
	        url: url + "/infos",
	        data: "id=" + markerId,
	        dataType: "JSON",
	        success: (data)=> {
	        	console.log('Infos success');
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
			url: url + "/getid",
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
				infoWindow.close();
				self.toggleInfoButton();
				$('#image-midsizenew').attr('src', file);
				
				//self.setMapOverlay();
				self.setNewOverlay();
				// Init social functionalities.
				let social = new Social();
				social.initialization();
				
			},
			error: function(result, status, error){
				console.log('erreur');
			}
		});
		
	};
	
	// Photo overlay
	
	this.setNewOverlay = function() {
		
		$('#modal-grid').modal('toggle');
		
		this.unsetNewOverlay();
	};
	
	this.unsetNewOverlay = function() {
		
		$('.icon-exit').click(function() {
			
			$('#modal-grid').modal('toggle');
			self.removeListeners();
		});
		
	};
	
	
	// Old overlay
	
	this.setMapOverlay = function() {
		
		$('#overlay').show();
		var bodyElt = document.body;
		$(bodyElt).addClass('noscroll');
		
		this.unsetMapOverlay(bodyElt);
	};
	
	this.unsetMapOverlay = function(bodyElt) {
		
		$('.icon-exit').click(function() {
			
			$(bodyElt).removeClass('noscroll');
			$('#overlay').hide();
			self.removeListeners();
		});
	};
	
	this.removeListeners = function() {
		
			
		$("#comment-form").off('submit');
		//$('#image-midsize').unbind('click');
		$('.like').unbind('click');
		$('.report').unbind('click');
		
	};
	
	// Init Google Map
	this.initMap = function () {
	    var brittany = {lat: 47.847963, lng: -1.465993};
	    gmap = new google.maps.Map(document.getElementById('map'), {
	          center: brittany,
	          zoom: 4,
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
		
}

function initApp(){
	var myMap = new displayMap();
	myMap.initialization();
}

