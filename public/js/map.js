// ------ Google Map & Stations API ------ //


function initMap() {
	//Location of Crozon
	var crozon = {lat: 48.243982, lng: -4.432997};
	var map = new google.maps.Map(document.getElementById('map'), {
	  center: crozon,
	  zoom: 7
	});
};
