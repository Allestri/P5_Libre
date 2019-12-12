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


// Test Exif Upload

$('#test-form').submit(function(e){ 
	
	// Reset testdiv classes
	$('.test-wrapper').removeClass('invalid');
	$('.test-wrapper').removeClass('valid');
	
	e.preventDefault();
	$.ajax({
		type: "POST",
		url: "http://projetlibre/testexif",
		data:  new FormData(this),
		dataType: "JSON",
		contentType: false,
		cache: false,
		processData:false,
		success: function(data){
			console.log('Success, test data recovered');
			displayFlash(data);
			console.log(data);
		},
		error: function(result, status, error){
			console.log('Error on data recovery' + error);
		}	
	});
});



// Display test exif components before upload
function displayFlash(data) {

	var flash = '';
		
	if(data.geodata === false && data.thumbnail === false && data.info === false){
		flash = "<div class='alert alert-danger'>Votre image est totalement incompatible avec cette application'</div>";
		$('.test-wrapper').addClass('invalid');
		$("#continue-button").prop("disabled", true);
		
	} else if (data.geodata === false || data.thumbnail === false || data.info === false){
		flash = "<div class='alert alert-warning'>Certaines infos manquent pour une compatiblité complète</div>";
		$("#continue-button").prop("disabled", true);
		
		display(data.thumbnail, "#test-thumbnail");
		display(data.info, "#test-photo");
		display(data.geodata, "#test-geo");

	} else {
		flash = "<div class='alert alert-success'>Votre image est entièrement compatible avec cette application</div>";
		$('.test-wrapper').addClass('valid');
		$("#continue-button").prop("disabled", false);
	}
	$('#flash-wrapper').html(flash);

};

function display(data, selector){
	if(data){
		$(selector).addClass('valid');
	}else {
		$(selector).addClass('invalid');
	}
}




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
	var filename = data.filename;
	
	var image = "uploads/photos/" + filename;
	
	$('#edit-imgId').val(imgId);
	$('#name').val(title);
	$('#author').val(author);
	$('#description').val(description);
	$('#edit-preview').append($('<img id="image-preview" />').attr('src', image));
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