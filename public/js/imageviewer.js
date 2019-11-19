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

$('.icon-exit').click(function() {
	
	$('#overlay').hide();
	
});


$('.icon-exit-full').click(function() {
	$('#myphoto-wrapper').hide();
});


/* Admin page */

$('.image-reported-wrapper').click(function(){
	
	var file = $(this).find("img").attr( "src" );
	console.log(file);
	$('#overlay').show();
	displayFS(file);
});


//Display full screen via image viewer
function displayFS(filepath) {
	
	let filename = filepath.split("uploads/photos/");
	
	var dir = "uploads/photos";
	var file = dir + "/" + filename[1];
	
	// Get clicked photo unique ID
	getMyPhotoId(filename).done(setValues);	
	
	// Check container, removes any photo before insertion
	if($('#myphoto').length > 0) {
		let photo = $('#admin-image-wrapper').find('img');
		photo.remove();
		console.log('Container cleared !');
	}
	
	$('#admin-image-wrapper').prepend($('<img id="myphoto" />').attr('src', file));	
	
	
};

	

/* Profile page */

$('#showAllImages').click(function() {
	
	$('#overlay').show();
	fetchMyPhotos();
	
});

function getMyPhotoId(filename) {
	
	return $.ajax({
		type:"POST",
		url: "http://projetlibre/getid",
		data: {filename : filename[1]}
	});
	
};

// Gets post and image unique ids.
function getUniqueIds(filename) {

	return $.ajax({
		type:"POST",
		url: "http://projetlibre/getids",
		dataType: "JSON",
		data: {filename : filename[1]}
	});
}


// Sets post & image unique IDs values on form inputs
function setValues(data, status, object){
	
	console.log(data);
	var imgId = data.image_id;
	var postId = data.post_id;
	console.log(postId);
	
	var imgIdElts = $(".imgId");
	var postIdElts = $('.postId');
	imgIdElts.val(imgId);
	postIdElts.val(postId);
	
	console.log(postIdElts);
	
};


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
	
	// Get clicked photo unique IDs
	getUniqueIds(filename).done(setValues);
	
	// Sets filename on form.
	var filenameElt = $(".filename");
	filenameElt.val(filename[1]);
	
	// Check container, removes any photo before insertion
	if($('#myphoto').length > 0) {
		let photo = $('#myphoto-wrapper').find('img');
		photo.remove();
		console.log('Container cleared !');
	}
	
	$('#myphoto-wrapper').prepend($('<img id="myphoto" />').attr('src', file));
	
	// Icon exit (?)
	//$('#myphoto-wrapper').append($('<div id="myphoto-panel">' + deleteButton + editButton + '</div>'));
	
	// Show photo overlay.
	$('#myphoto-wrapper').show();
	
};



/* Social functionalities */
/* Like an image */

$("#likeImg").on('click', (function(e) {
	e.preventDefault();
	
	var formData = $("#likeImg").serialize();
	console.log(formData);
	
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
	console.log(formData);
	$.ajax({
			type: "POST",
			url: "http://projetlibre/map/report",
			data: formData,
			success: function(data){
				console.log('Success, image reported');
			},
			error: function(result, status, error){
				console.log('error on reporting image');
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
	var $postId = $('#postId').attr('value');
	console.log($postId);

	$.ajax({
			type: "GET",
			url:"http://projetlibre/map/showcomment",
			data: "postId=" + postId,
			dataType: "json",
			success: function(data){
				console.log(data);
				displayComments(data);
				console.log('Success, comments loaded');
			},
			error: function(result, status, error){
				console.log('error on displaying comments');
			}
	});
};

function displayComments(data) {

	console.log(data);
    var comments = "";

    for( var i = 0; i < data.length; i++){

        comments += "<div class='card'><div class='card-body'><h5 class='card-title'>" + data[i].name + "</h5><p class='card-text'>" + data[i].content + "</p></div></div>";

    }
    $('#comments-wrapper').html(comments);
};



// CRUD


// Sets the unique ID value on inputs - !!! possible duplication w setValues on map.js
function setIdPhoto(){
	
	var id = data;
	console.log(id);
	
	var elements = $(".imgId");
	elements.val(id);
	console.log(elements);
	
	
};

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

//Delete a photo
	
$("#delete").on('click', function(e) {
	//e.preventDefault();
		if(confirm("Voulez vous supprimer cette photo ?")){
			console.log("Confirmation suppression");
			e.preventDefault();
			
			//var filename = $('#myphoto').attr('src');
			console.log(filename);
			var datas = $("#delete").serialize();

			console.log(datas);
			$.ajax({
				type: "POST",
				url:"http://projetlibre/profile/deleteimg",
				data: datas,
				success: function(data){
					console.log('Success, photo deleted');
				},
				error: function(result, status, error){
					console.log('error on photo deletion');
				}
			});
			
			// Confirmation
			// clear window
			
		} else {
			e.preventDefault();
		}
});



// Home page carousel

$('.carousel').carousel({
	  interval: 7000
	})