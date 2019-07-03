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
	}
	
	// Fetch the points
	this.getPoints = function() {
		$.ajax({
	        type: "GET",
	        url: "json/markers.json",
	        dataType: "json",
	        success: (data)=> {
	            console.log(data[2].lat, data[2].lng);
	            console.log(data.length);
	            
	            // Info window
                var infoWindow = new google.maps.InfoWindow;
                
	            // Loop through each locations.
	            for(var i = 0; i < data.length; i++){
	            	
	            	// Position
	                var latlngset = new google.maps.LatLng(data[i].lat, data[i].lng);
	            	
	                marker = new google.maps.Marker({
	                    position: latlngset,
	                    map: gmap,
	                    title: 'Click me ' + i
	                });
	                markers.push(marker);
	                // Event listener
	                marker.addListener('click', function() {
	                	console.log('open');
	                	console.log(infoWindow);
	                	infoWindow.setContent("Bonjour");
	                	
	                	
	                	infoWindow.open(map, marker);
	                });
	            }
	    		// Clusterer
	    		var markerCluster = new MarkerClusterer(gmap, markers,{
	    			imagePath: 'images/assets/m'
	    		});
	        },
	        error : function(result, status, error){
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
	
	// Refresh the map (test)
	this.refreshMap = function () {
		$("button").click(function () {
			self.getPoints();
		});
	};	
	
}

function initApp(){
	var myMap = new displayMap();
	myMap.initialization();
}