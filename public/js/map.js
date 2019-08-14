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
	        url: "json/markertest.json",
	        dataType: "json",
	        success: (data)=> {
	            console.log(data[0].lng, data[0].lat);
	            	            
	            // Info window
                var infoWindow = new google.maps.InfoWindow;
                
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
	                
	                let point = new points(data, i);
	                //console.log(point);
	                let marker = new google.maps.Marker({
	                    position: latlngset,
	                    map: gmap,
	                    point: point,
	                    title: 'Marker : ' + data[i].name
	                });
	                

	                console.log(i);
	                // Event listener
	                marker.addListener('click', function() {
	                	//console.log(infoWindow);
	                	infoWindow.setContent("Bonjour");
	                	marker.point.displayInfos();
	                	
	                	infoWindow.open(map, marker);
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

