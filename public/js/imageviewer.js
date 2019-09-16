/* Image Viewer */

// dbugging purposes

$('.fullImg').click(function(){
	
	$('#overlay').hide();
	
});


/* Like an image */

$("#likeImg").on('click', (function(e) {
	e.preventDefault();
	
	var formData = $("#likeImg").serialize();
	
	$.ajax({
			type: "POST",
			url: "http://projetlibre/map",
			data: formData,
			success: function(data){
				console.log('success');
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