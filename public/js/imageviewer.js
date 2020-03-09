/* Image Viewer */


var self = this;


// Hiding overlay



/* Exit image viewer */




$('.icon-exit-full').click(function() {
	$('#myphoto-wrapper').hide();
});




/* Profile page */

function getMyPhotoId(filename) {
	
	console.log('id fired');
	return $.ajax({
		type:"POST",
		url: "http://projetlibre/getid",
		data: {filename : filename[1]}
	});
	
};



/*
// Sets post & image unique IDs values on form inputs
function setValues(data, status, object){
	
	console.log(data);
	var imgId = data.image_id;
	var postId = data.post_id;
	console.log(postId);
	
	var imgIdElts = $(".imgId");
	var postIdElts = $('.postId');
	var postIdEltsTwo = $('#postId');
	
	imgIdElts.val(imgId);
	postIdElts.val(postId);
	postIdEltsTwo.val(postId);
	
	console.log(postIdEltsTwo);
	
};
*/



// Show photo informations such as comments.
// WIP
/*
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
*/


// CRUD


// Sets the unique ID value on inputs - !!! possible duplication w setValues on map.js
function setIdPhoto(){
	
	var id = data;
	console.log(id);
	
	var elements = $(".imgId");
	elements.val(id);
	console.log(elements);
	
	
};


