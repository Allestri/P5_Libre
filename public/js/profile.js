/* Javascript Profile Page */

function profileComponents(){
	
	var self = this;
	var projectUrl = "http://projetlibre/profile";
	
	this.initialization = function() {
			
		this.modalClean();
		this.setOverlay();
		this.setClickedPhoto();
		this.closeOverlayNew();
		//this.setEditForm();
		this.deleteComment();
		
		this.previewAvatar();
	};
	
			
	// Removes any listeners when a modal is closing.
	this.modalClean = function() {
		
		$('#modal-grid').on('hidden.bs.modal', function(e) {
			console.log('modal cleaned');
			self.removeListeners();
		});
		
	};
	
	
	this.removeListeners = function() {
		
		$('#edit-post-btn').unbind('click');
		$("#delete").unbind('click');
	};
	
	this.setOverlay = function() {
		
		$('#showAllImages').click(function() {
			
			$('#overlay').show();
			self.fetchMyPhotos();
			
		});
		
	};
	
	// New Overlay
	
	this.setClickedPhoto = function() {
		
		$('.image-profile-wrapper').click(function(){
    		
			// Sets clicked Dom
			var imageDom = this;
						
    		// Get event content
    		var child = this.childNodes[1];
    		
    		// Data attributes
    		let privacy = child.getAttribute("data-privacy");
    		let likes = child.getAttribute("data-likes");
    		let commentsNbr = child.getAttribute("data-comments");
    		
    		self.setPrivacy(privacy);
    		self.setSocialCount(likes, commentsNbr);
    		
    		var filename = $(this).find("img").attr( "src" );
    		console.log('pathfilename :' + filename);
    		self.displayFullScreen(filename, imageDom);
    	});		
		
	};
	
	// Sets data attribute
	this.setSocialCount = function(likes, commentsNbr) {
		
		$('#liked-count').html(likes);
		$('#comments-count').html(commentsNbr);
	};
	
	this.setPrivacy = function(privacy) {
		
		if (privacy == 1) {
			$('.privacy').html("<p class='text-muted'>Mes amis seulement</p>");
		} else if (privacy == 2) {
			$('.privacy').html("<p class='text-muted'>Photo Privée</p>");
		} else {
			$('.privacy').html("<p class='text-muted'>Photo publique</p>");
		}
		
		
	};
	
	this.closeOverlayNew = function() {
		
		$('#exit-large-vw').click(function() {
			$('#modal-grid').modal('hide');
		});
	};	
	
	//Display full screen via image viewer
	this.displayFullScreen = function (filepath, imageDom) {
	
		let filename = filepath.split("uploads/thumbnails/");
		
		var dir = "uploads/photos";
		var file = dir + "/" + filename[1];
		
		// Get clicked photo unique IDs
		this.getUniqueIds(filename).done(self.setValues);
		// Sets filename on form.
		/*
		var filenameElt = $(".filename");
		filenameElt.val(filename[1]);
		
		/*
		// Check container, removes any photo before insertion
		if($('#myphoto').length > 0) {
			let photo = $('#myphoto-wrapper').find('img');
			photo.remove();
			console.log('Container cleared !');
		}
		*/
		
		$('#image-profile-midsize').attr('src', file);		
		
		// Setup form listeners
		self.deletePost(imageDom, filename[1]);
		self.setEditForm();
		
		
		// Show photo overlay.
		$('#modal-grid').modal('show');
							
	};
	
	// To be changed to Data attribute usage !
	// Gets post and image unique ids.
	this.getUniqueIds = function(filename) {

		return $.ajax({
			type:"POST",
			url: "http://projetlibre/profile/getids",
			dataType: "JSON",
			data: {filename : filename[1]}
		});
	};
		
	this.closeOverlay = function() {
		
		$('#exit-large-vw').click(function() {
			$('#overlay').hide();
		});
	};
	
	// CRUD
	
	this.deleteComment = function() {
		
		$('.delete-comment-btn').click(function(e) {
			e.preventDefault();
			
			var formData = $(this).parent().serialize();
			//var commentId = $(this).parent()[0].childNodes[1].defaultValue;
			var comment = $(this).parents("div.comment");
			
			$.ajax({
				type: "POST",
				url: projectUrl + "/deletecomment",
				data: formData,
				success: function(data){
					console.log('Success, comment deleted');
					self.refreshCommentsDom(comment);
				},
				error: function(result, status, error){
					console.log('Error on comment deletion : ' + status);
				}	
			});
			
			
		});
		
	};
	
	// Removes the deleted comment and decrement comment count
	this.refreshCommentsDom = function(comment) {

		var commentsNbr = document.getElementById('profile-comment-nbr').textContent;
		if(commentsNbr != 0){
			--commentsNbr;
		}
		document.getElementById('profile-comment-nbr').innerHTML = commentsNbr;
		
		comment.fadeOut(600, function(){
			comment.remove();
			console.log('Comment succesfully removed on DOM');
		});

	};	
	
	
	// Deprecated
	this.displayComments = function(data) {

		var commentOptionStart = "<div class='comment-options'><i class='fa fa-cog'></i><ul class='options-menu'><li><form class='deleteComment' method='POST' action='profile/deletecomment' >";
        var commentOptionEnd = "<button class='delete-comment-btn'>Supprimer</button></form></li></ul></div>";	        									
        	        									
    	        									
        										
	    var comments = "";

	    for( var i = 0; i < data.length; i++){
	    	
	    	//let avatar = this.getAvatar(data[i].name, data[i].avatar_file);
	    	
	    	comments += "<div class='card comment'><div class='card-body'><p class='card-text'>" + data[i].content + "</p></div>" + commentOptionStart + 
	    	"<input type='hidden' name='commentId' value='" + data[i].id + "'/><input type='hidden' name='uid' value='" + data[i].author_id + "'/>" + commentOptionEnd + "</div>";
			

	    }
	    $('#profile-comments-wrapper').html(comments);
	   
	    // Init delete button listener.
	    this.deleteComment();
	};
	
	
	this.setEditForm = function() {
		
		$('#edit-post-btn').click(function(e) {
			
			e.preventDefault();
			var postId = $(this).parent().serialize();
			
			$.ajax({
				type: "POST",
				url: projectUrl + "/getpost",
				data: postId,
				dataType: "JSON",
				success: function(data){
					console.log('Success, data recovered');
					self.setValuesProfileEdit(data);
					// Hides overlay and toggle form tab
					console.log(data);
				    self.setEditInterface();
				},
				error: function(result, status, error){
					console.log('Error on data recovery');
				}	
			});
		});
		
	};
	
	
	// Profile Page CRUD
	/*
	$('#edit-post-form').submit(function(e) {
		e.preventDefault();
		
		var formData = $("#edit-post-form").serialize();
		console.log(formData);
		
		$.ajax({
			type: "POST",
			url: "http://projetlibre/newprofile/editpost",
			data: formData,
			success: function(data){
				console.log('Success, post edited');
			},
			error: function(result, status, error){
				console.log('Error post edition');
			}	
		});
	});
	*/
	
	
	// Refreshes DOM after any deletion.
	this.refreshPostDom = function(){
		
		var photosNbr = document.getElementById('profile-img-nbr').textContent;
		if(photosNbr != 0){
			--photosNbr;
		}
		document.getElementById('profile-img-nbr').innerHTML = photosNbr;
		
		$("#np-photos-tab").load(projectUrl + "#np-photos-tab>*");
	};	
	
		
	this.deletePost = function(imageDom, filename) {
		
		$("#delete").on('click', function(e) {
			//e.preventDefault();
				if(confirm("Voulez vous supprimer cette photo ?")){
					console.log("Confirmation suppression");
					e.preventDefault();
					
					var datas = $("#delete").serialize();
					//console.log(datas);
					//var photoDom = $('#np-photos-tab').find(filename);
					//console.log(photoDom);
					
					$.ajax({
						type: "POST",
						url: projectUrl + "/deleteimg",
						data: datas + "&filename=" + filename,
						success: function(data){
							console.log('Success, photo deleted');
							$('#modal-grid').modal('hide');
							self.refreshPostDom();
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
	};
	
	// Deprecated // Sets the unique ID value on inputs
	this.setIdPhoto = function() {
		
		var id = data;
		console.log(id);
		
		var elements = $(".imgId");
		elements.val(id);
		console.log(elements);
			
	};
	
	// Sets post & image unique IDs values on form inputs
	this.setValues = function(data, status, object){
		
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
	
	
	
	// Preview avatar before submitting
	this.previewAvatar = function() {
		
		$("#avatar-form").change(function(){

			var file = this.files[0];
			console.log(file);
			console.log(this);
			var reader = new FileReader();
			reader.onload = self.imageIsLoaded;
			reader.readAsDataURL(this.files[0]);
		});
		
		
	};
	
	this.imageIsLoaded = function(e){
		
		$('#avatar-preview-ctn').html($('<img id="avatar-preview" />').attr('src', e.target.result));
		$('#avatar-preview').attr('width', '150px');
		//$('#avatar-preview').attr('height', '150px');
	};
					

	this.setEditInterface = function(){
		$('#modal-grid').modal('toggle');
		//$('#myphoto-wrapper').hide();
		$('#tab-edit-img').addClass('show-edit');
		
		// Set close event listener
		this.closeEditForm();
	};

	
	// Sets data on Edit Form
	this.setValuesProfileEdit = function(data){
		
		var postId = data.id;
		var title = data.name;
		var description = data.content;
		var privacy = data.privacy;
		
		$('#edit-imgId').val(imgId);
		$('#name').val(title);
		$('#description').val(description);
		$('#privacy').val(privacy);
		
	};
	
	
	this.closeEditForm = function() {
		
		$('#exit-edit').on('click', function() {
			
			$('#tab-edit-img').removeClass('show-edit');
			
		});
		
	};	
		
	
	
	
	
	
	/* Not using
	 * 
	this.deleteMyAccount = function () {
		
		$("#delete-account").on('click', function(e) {
			//e.preventDefault();
				if(confirm("Voulez vous vraiment supprimer votre compte ?")){
					console.log("Confirmation suppression");
					e.preventDefault();
					
	
					console.log(datas);
					$.ajax({
						type: "POST",
						url:"http://projetlibre/profile/deleteaccount",
						data: datas,
						success: function(data){
							console.log('Success, account deleted');
						},
						error: function(result, status, error){
							console.log('error on deletion');
						}
					});
					
					// Confirmation
					// clear window
					
				} else {
					e.preventDefault();
				}
		});
		
		
	}
	*/
	

	
}

$(window).on('load', function() {
	  $('#loading').addClass('loaded');
});

$("#profile-container").ready(function() {
    var profilePage = new profileComponents();
    profilePage.initialization();
});