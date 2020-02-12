// General JS code not related to GMaps or Images viewers.



/*
$('#edit-profile-btn').on('click', function(){
	
	$('#tab-profile').toggle();
	$('#tab-profile-settings').toggle();
	
});
*/



// Navbar

/*
$(document).ready(function() {
	
	console.log($('#navbar .navbar-nav a'));
	
	var url = window.location.href;
	var activePage = url;
		 
	$('.navbar-nav a').each(function () {
		var linkPage = this.href;
		console.log(linkPage);
		
		if(activePage == linkPage){
			$(this).closest( 'li' ).addClass( 'active' );
		}
		
	});
	/*
	$( '#navbar .navbar-nav a' ).on( 'click', function () {
		console.log('bonjour');
		$( '#navbar .navbar-nav' ).find( 'li.active' ).removeClass( 'active' );
		$( this ).parent( 'li' ).addClass( 'active' );
	});
	
	
});
*/





AOS.init();


//Home page carousel

$('.carousel').carousel({
	  interval: 7000
	})
	
// Flash messages

$( '.alert-dismissible' ).delay( 1500 ).fadeOut( 400 );

// Popovers

$(document).ready(function() {
    $("body").tooltip({ selector: '[data-toggle=tooltip]' });
});



// Form validation

(function() {
	'use strict';
	window.addEventListener('load', function() {
	// Fetch all the forms we want to apply custom Bootstrap validation styles to
	var forms = document.getElementsByClassName('needs-validation');
	// Loop over them and prevent submission
	var validation = Array.prototype.filter.call(forms, function(form) {
	form.addEventListener('submit', function(event) {
	if (form.checkValidity() === false) {
	event.preventDefault();
	event.stopPropagation();
	}
	form.classList.add('was-validated');
	}, false);
	});
	}, false);
	})();


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



$('#dummy').click(function(){
	
	var selector = $("#dummy-text");
	console.log(selector);
	selector.fadeOut(1000);
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


$('#triggerTest').click(function(e){
	
	e.preventDefault();
	$("#dynamic-test").load("http://projetlibre/map/test #dynamic-test>*", function(response, statusTxt, xhr) {
		if(statusTxt == "success")
			console.log(response);
		if(statusTxt == "error")
		    console.log("Error: " + xhr.status + ": " + xhr.statusText);
	});
	
});

$('#login-btn').click(function(e){
	
	e.preventDefault();
	var formData = $("#login-overlay").serialize();
	
	$.ajax({
			type: "POST",
			url: "http://projetlibre/login",
			data: formData,
			dataType: "JSON",
			success: function(data){
				console.log('Success, member successfully connected:' + status);
				console.log(data);
				//self.refreshLikes();
				
				//$(".modal-footer").append("<p>" + data['message'] + "</p>");
				$('#login-modal').modal('hide');

				// Code spaghetti, I'll fix that I promise !
				$("#main-navbar").load("http://projetlibre/ #main-navbar>*");
				
				$("#dynamic-layout").load("http://projetlibre/map #dynamic-layout>*", function(response, statusTxt, xhr) {
					if(statusTxt == "success")
						console.log(response);
					if(statusTxt == "error")
					    console.log("Error: " + xhr.status + ": " + xhr.statusText);
				});
				
				//$("#dynamic-layout").load("http://projetlibre/map #dynamic-layout>*");


				//$( "#dynamic-layout" ).load( "http://projetlibre/map #dynamic-layout" );
			},
			complete: function(data){
				console.log('Callback: ' + data);
				
			},
			error: function(result, status, error){
				console.log('erreur : ' + error + ' status: ' + status);
			}
		});	
	
});


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


// Memberlist
/*
$('.memberlist-btn').click(function (e) {
	
	e.preventDefault();
	
	var eltBtn = this;
	var attr = $(eltBtn).attr('form');
	console.log(eltBtn);
	console.log(attr);
	
	var userId = attr.split("removefriend");
	console.log(userId[1]);
	
	var formData = $("#comment-form").serialize();
	
});
*/

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