/* Image Viewer */


// Hiding overlay
$('#image-midsize').ready(function(){
	console.log('Image ready');
	
	$('#image-midsize').click(function(){
		
		$('#overlay').hide();
				
	});
});



/* Social functionalities */
/* Like an image */

$("#likeImg").on('click', (function(e) {
	e.preventDefault();
	
	var formData = $("#likeImg").serialize();
	
	$.ajax({
			type: "POST",
			url: "http://projetlibre/map",
			data: formData,
			success: function(data){
				console.log('Success, image liked');
			},
			error: function(result, status, error){
				console.log('erreur');
			}
		});
}));

/* Report an image */

$("#reportImg").on('click', (function(e) {
	e.preventDefault();
	
	var formData = $("#reportImg").serialize();
	
	$.ajax({
			type: "POST",
			url: "http://projetlibre/map/report",
			data: formData,
			success: function(data){
				console.log('Success, image reported');
			},
			error: function(result, status, error){
				console.log('erreur');
			}
		});
}));

/* Comment an image */

$("#comment").on('click', (function(e) {
	e.preventDefault();
	
	var formData = $("#comment").serialize();
	
	$.ajax({
			type: "POST",
			url: "http://projetlibre/map/comment",
			data: formData,
			success: function(data){
				console.log('Success, image commented');
			},
			error: function(result, status, error){
				console.log('erreur');
			}
		});
}));

// Home page carousel

$('.carousel').carousel({
	  interval: 7000
	})