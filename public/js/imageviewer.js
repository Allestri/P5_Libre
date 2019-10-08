/* Image Viewer */


// Hiding overlay
$('#image-midsize').ready(function(){
	console.log('Image ready');
	
	$('#image-midsize').click(function(){
		
		$('#overlay').hide();
				
	});
});


/* Exit image viewer */

$('#icon-exit').click(function() {
	
	$('#overlay').hide();
	
});


/* Profile page */

$('#showAllImages').click(function() {
	
	$('#overlay').show();
	fetchMyPhotos();
	
});


function fetchMyPhotos() {
	
	$.ajax({
		type: "GET",
		url: "http://projetlibre/profile/myimgs",
		dataType: "json",
		success: function(data){
			displayMyPhotos(data);
		},
		error: function(result, status, error){
			console.log('erreur');
		}
	});
	
};

function displayMyPhotos(data) {
	
	var photos = "";
	console.log(data);
	
	for( var i = 0; i < data.length; i++){
		photos += "<div class='profile-photos-card card'><div class='profile-photo-wrapper'><img class='profile-photo card-img-top' src='uploads/thumbnails/" + data[i].filename + "' /><div class='card-overlay'>Cliquez pour aggrandir</div></div><div class='card-body'><h5 class='card-title'>" + data[i].name + "</h5></div></div>";
    }
    $('#profile-images-wrapper').html(photos);
    
	
};

function displayMyPhotoFullscreen() {
	
	// Get event content
	// Display full screen via image viewer
};



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

$("#comment-btn").on('click', (function(e) {
	e.preventDefault();
	
	var formData = $("#comment").serialize();
	
	$.ajax({
			type: "POST",
			url: "http://projetlibre/map/comment",
			data: formData,
			success: function(data){
				console.log('Success, image commented');
				showComments();
				$("#comment")[0].reset();
				
			},
			error: function(result, status, error){
				console.log('erreur');
			}
		});
	
}));

// Show photo informations such as comments.
// WIP

function showInfos(filename){
	
	console.log(filename);
	
	$.ajax({
		type: "GET",
		url:"http://projetlibre/map/showinfo",
		dataType: "JSON",
		success: function(data){
			displayComments(data);
			console.log('Success, informations loaded');
		},
		error: function(result, status, error){
			console.log('error on displaying informations');
		}
	});
};


// Deprecated.

function showComments() {
	$.ajax({
			type: "GET",
			url:"http://projetlibre/map/showcomment",
			dataType: "json",
			success: function(data){
				displayComments(data);
				console.log('Success, comments loaded');
			},
			error: function(result, status, error){
				console.log('error on displaying comments');
			}
	});
};

function displayComments(data) {

    var comments = "";

    for( var i = 0; i < data.length; i++){

        comments += "<div class='card'><div class='card-body'><h5 class='card-title'>" + data[i].name + "</h5><p class='card-text'>" + data[i].content + "</p></div></div>";

    }
    $('#comments-wrapper').html(comments);
};


// Home page carousel

$('.carousel').carousel({
	  interval: 7000
	})