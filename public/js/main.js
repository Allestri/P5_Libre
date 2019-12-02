// General JS code not related to GMaps or Images viewers.

/*
$('#edit-profile-btn').on('click', function(){
	
	$('#tab-profile').toggle();
	$('#tab-profile-settings').toggle();
	
});
*/



// Navbar
$('#navbar > ul.nav li a').click(function(e) {
    var $this = $(this);
    $this.parent().siblings().removeClass('active').end().addClass('active');
    e.preventDefault();
});

//Home page carousel

$('.carousel').carousel({
	  interval: 7000
	})

//Admin Panel //
$('.delete').click(function(e) {
	
	if(confirm("Voulez vous supprimer ce post ?")){
		console.log("Confirmation suppression");
		e.preventDefault();
		
		//console.log(filename);
		var datas = $("#deleteImg").serialize();

		console.log(datas);	
		$.ajax({
			type: "POST",
			url:"http://projetlibre/admin/delete",
			data: datas,
			success: function(data){
				console.log('Success, photo deleted');
			},
			error: function(result, status, error){
				console.log('Error on photo deletion');
			}
		});
		
		// Confirmation
		// clear window
		
	} else {
		e.preventDefault();
	}
	
});


// Get values from a report to edit form
$('.edit').click(function() {
	
	var imgId = $(this).parent().serialize();
	
	$.ajax({
		type: "POST",
		url: "http://projetlibre/admin/getreport",
		data: imgId,
		dataType: "JSON",
		success: function(data){
			console.log('Success, data recovered');
			setValuesEdit(data);
		},
		error: function(result, status, error){
			console.log('Error on data recovery');
		}	
	});
});






// Test Exif upload
$('#test-form-btn').click(function() {
	console.log('fired');
	displayTestResults();
});


function displayTestResults(){
	// If there is any flash success
	if($('.alert-success').length > 0) {
		  
		$('.test-wrapper').addClass('valid');
	} else {
		$('.test-wrapper').addClass('invalid');
	}
};




/* 
$("#testForm").on('submit', (function(e) {
	e.preventDefault();
	$.ajax({
			type: "POST",
			url: "http://projetlibre/testexif",
			data:  new FormData(this),
			contentType: false,
			cache: false,
			processData:false,
			success: function(data){
				console.log(data);
				$("#preview").html(data).fadeIn();
			    $("#testForm")[0].reset(); 
			},
			error: function(result, status, error){
				$(".container").append("<p>Erreur</p>");
			}
		});
}));
*/



// Set values on Edit form - Admin Panel
function setValuesEdit(data){
	
	console.log(data);
	var imgId = data.id;
	var title = data.name;
	var author = data.author;
	var description = data.content;
	
	$('#edit-imgId').val(imgId);
	$('#name').val(title);
	$('#author').val(author);
	$('#description').val(description);
};


// Preview avatar before submitting - Profile Page
$("#avatar-form").change(function(){

	var file = this.files[0];
	console.log(file);
	
	var reader = new FileReader();
	reader.onload = imageIsLoaded;
	reader.readAsDataURL(this.files[0]);
});

function imageIsLoaded(e){
	$('#avatar-preview-ctn').append($('<img id="avatar-preview" />').attr('src', e.target.result));
	//$('#preview-avatar').attr('src', e.target.result);
	$('#avatar-preview').attr('width', '150px');
	//$('#avatar-preview').attr('height', '150px');
};