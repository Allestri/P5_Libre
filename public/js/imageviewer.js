/* Image Viewer */

// dbugging purposes



// Hiding overlay 
$('.fullImg').click(function(){
	
	$('#overlay').hide();
	
	
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
	
	var formData = $("#likeImg").serialize();
	
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


// Home page carousel

$('.carousel').carousel({
	  interval: 7000
	})