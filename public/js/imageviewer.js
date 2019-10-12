/* Image Viewer */


var self = this;


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
	// 
	var template = "";
	//console.log(data);
	
	for( var i = 0; i < data.length; i++){
		// use template
		photos += "<div class='profile-photos-card card'><div class='profile-photo-wrapper'><img class='profile-photo card-img-top' src='uploads/thumbnails/" + data[i].filename + "' /><div class='card-overlay'>Cliquez pour aggrandir</div></div><div class='card-body'><h5 class='card-title'>" + data[i].name + "</h5><div class='card-social'><i class='fas fa-heart'></i>" + data[i].liked + "</div></div></div>";
    }
    $('#profile-images-wrapper').html(photos); // use template
    
    
    $('#profile-images-wrapper').ready(function (){
    	
    	$('.profile-photo-wrapper').click(function(){
    		
    		// Get event content
    		//console.log('clicked' + event.target);
    		var child = ($(this).children());
    		var filename = $(this).find("img").attr( "src" );
    		console.log(filename);
    		displayFullScreen(filename);
    	});
    	
    });
	
};

//Display full screen via image viewer
function displayFullScreen(filepath) {
	
	let filename = filepath.split("uploads/thumbnails/");
	
	var dir = "uploads/photos";
	var file = dir + "/" + filename[1];

	var deleteButton = "<span><i class='fas fa-trash-alt'></i></span>";
	var editButton = "<span><i class='fas fa-edit'></i></span>";


	/*
	$('#profile-images-wrapper').append($('<div id="myphoto-wrapper"></div>'));
	$('#myphoto-wrapper').append($('<img id="myphoto" />').attr('src', file));
	$('#myphoto-wrapper').append($('<div id="myphoto-panel">' + deleteButton + editButton + '</div>'));
	
	// Show photo overlay.
	$('#myphoto-wrapper').show();
	*/
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
	console.log(formData);
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
		data: id,
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
		
	// WIP
	//var element = $('#comment').find('input');
	var imgId = $('#imgId').attr('value');
	console.log(imgId);

	$.ajax({
			type: "GET",
			url:"http://projetlibre/map/showcomment",
			data: "imgId=" + imgId,
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



// CRUD

// Edit a photo
$('#edit-btn').on('click', function(e) {
	e.preventDefault();
	
	var filename = $('#myphoto').attr('src');
	console.log(filename);
	
	$.ajax({
		type: "GET",
		url:"http://projetlibre/map/showcomment",
		data: "imgId=" + imgId,
		dataType: "json",
		success: function(data){
			displayComments(data);
			console.log('Success, comments loaded');
		},
		error: function(result, status, error){
			console.log('error on displaying comments');
		}
	});
	
});


// Delete a photo
$('#delete-btn').on('click', function(e) {
	e.preventDefault();
	
	var filename = $('#myphoto').attr('src');
	console.log(filename);
	var imgId = $("#delete").serialize();
	console.log(imgId);
	$.ajax({
		type: "POST",
		url:"http://projetlibre/profile/deleteimg",
		data: imgId,
		success: function(data){
			console.log('Success, photo deleted');
		},
		error: function(result, status, error){
			console.log('error on photo deletion');
		}
	});
	
});



// Home page carousel

$('.carousel').carousel({
	  interval: 7000
	})