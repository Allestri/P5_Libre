/* JS for Forms */

function Forms() {
	
	var self = this;
	const url = "http://projetlibre/";
	
	// Work in progress
	this.initialization = function() {
		
		this.checkUpload();
		this.triggerButton();

	};
	
	this.checkUpload = function() {

		$('#test-form').submit(function(e){ 
			
			e.preventDefault();
			$('#upload-btn').text('Chargement...');
			
			// Reset testdiv classes
			$('.test-wrapper').removeClass('invalid');
			$('.test-wrapper').removeClass('valid');
			
			
			$.ajax({
				type: "POST",
				url: url + "/testexif",
				data:  new FormData(this),
				dataType: "JSON",
				contentType: false,
				cache: false,
				processData:false,
				success: function(data){
					console.log('Success, test data recovered');
					displayFlash(data);
					console.log(data);
					$('#upload-btn').text('Envoyer');
				},
				error: function(result, status, error){
					console.log('Error on data recovery' + error);
				}	
			});
		});
		
	}
	
	// Display test exif components before upload
	this.displayFlash = function(data) {

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

	this.display = function(data, selector){
		if(data){
			$(selector).addClass('valid');
		}else {
			$(selector).addClass('invalid');
		}
	}
	
	this.triggerButton = function() {
		
		$('#continue-button').click(function() {
			
			$('#continue-button').removeClass('active');	
			
		});

		$('.post-form-backward').click(function() {
			
			$('.post-form-backward').removeClass('active');
			
		});
		
	}
	
	
	
	
	// Login Form validation
	/*
	(function() {
		'use strict';
		window.addEventListener('load', function() {
			// Fetch all the forms we want to apply custom Bootstrap validation styles to
			var forms = document.getElementsByClassName('needs-validation');
			console.log(forms);
			// Loop over them and prevent submission
			var validation = Array.prototype.filter.call(forms, function(form) {
				form.addEventListener('submit', function(event) {
				if (form.checkValidity() === false) {
					event.preventDefault();
					event.stopPropagation();
				} else {
					
					event.preventDefault();
					var formData = $("#login-overlay").serialize();
					$.ajax({
						type: "POST",
						url: "http://projetlibre/login",
						data: formData,
						dataType: "JSON",
						success: function(data){
							console.log(data);
							//self.refreshLikes();
							if(data.type == 'error'){
								displayError(data);
							} else {
							
								$.notify('Connecté', 'success');
									
								$('#login-modal').modal('hide');
		
								// Code spaghetti, I'll fix that I promise !
								$("#main-navbar").load("http://projetlibre/ #main-navbar>*");
								
								$("#dynamic-layout").load("http://projetlibre/map #dynamic-layout>*", function(response, statusTxt, xhr) {
									if(statusTxt == "success")
										console.log(response);
									if(statusTxt == "error")
									    console.log("Error: " + xhr.status + ": " + xhr.statusText);
								});
							}
							
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

				}
				form.classList.add('was-validated');
				}, false);
			});
		}, false);
	})();
	*/


	this.displayError = function(data){
		
		$("#login-overlay").removeClass('was-validated');
		
		var error = data.message;
		var input = data.input;
		
		if(data.input == 'username'){
			$('#modal-input-uname').addClass('is-invalid');
		}
		
		$('#form-error-wrapper').html(error);
		
		
	};
	
	
	
	
	
	
}