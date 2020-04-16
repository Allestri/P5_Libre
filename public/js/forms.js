/* JS for Forms */

function Forms() {
	
	var self = this;
	const url = "http://projetlibre/";
	
	// Work in progress
	this.initialization = function() {
		
		this.loginValidation();
		
		this.checkUpload();
		this.triggerButton();

	};
	
	/* Upload Form *
	 * 	
	 * Metadata Exif check upload
	 * Display valid or invalid depending on image datas
	 */
	
	// First step of an upload : Checks if any image has needed datas available.
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
					self.displayFlash(data);
					console.log(data);
					$('#upload-btn').text('Envoyer');
				},
				error: function(result, status, error){
					console.log('Error on data recovery' + error);
				}	
			});
		});
		
	}
	
	// Display test exif components after upload whether it's valid or not.
	// Then unlock Continue button to the second step.
	this.displayFlash = function(data) {

		var flash = '';
			
		if(data.geodata === false && data.thumbnail === false && data.info === false){
			flash = "<div class='alert alert-danger'>Votre image est totalement incompatible avec cette application'</div>";
			$('.test-wrapper').addClass('invalid');
			$("#continue-button").prop("disabled", true);
			
		} else if (data.geodata === false || data.thumbnail === false || data.info === false){
			flash = "<div class='alert alert-warning'>Certaines infos manquent pour une compatiblité complète</div>";
			$("#continue-button").prop("disabled", true);
			
			this.display(data.thumbnail, "#test-thumbnail");
			this.display(data.info, "#test-photo");
			this.display(data.geodata, "#test-geo");

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
	
	
	// Resets continue button class valid/invalid for navigation
	this.triggerButton = function() {
		
		$('#continue-button').click(function() {
			
			$('#continue-button').removeClass('active');	
			
		});

		$('.post-form-backward').click(function() {
			
			$('.post-form-backward').removeClass('active');
			
		});
		
	}
	
	
	/* Login Form *
	 * 	
	 * Login form validation
	 * Display error messages
	 */
		
	
	// Login validation
	this.loginValidation = function() {
		//(!) Code spaghetti, I'm aware of that !
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
				} else {
					
						event.preventDefault();
						var formData = $("#login-overlay").serialize();
						$.ajax({
							type: "POST",
							url: url + "/login",
							data: formData,
							dataType: "JSON",
							success: function(data){
								console.log(data);
								//self.refreshLikes();
								if(data.type == 'error'){
									self.displayError(data);
								} else {
								
									$.notify('Connecté', 'success');
										
									$('#login-modal').modal('hide');
			
									// Code spaghetti, known issue !
									$("#main-navbar").load(url + " #main-navbar>*");
									
									$("#dynamic-layout").load(url + "/map #dynamic-layout>*", function(response, statusTxt, xhr) {
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
	};

	// Display login error messages.
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

let myForms = new Forms();
myForms.initialization();
