/* Javascript Profile Page */

function profileComponents(){
	
	var self = this;
	
	this.initialization = function() {
		
		this.setOverlay();
		this.setClickedPhoto();
		this.closeOverlayNew();
		this.setEditForm();
		this.deletePost();
		this.deleteComment();
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
	
	this.setOverlay = function() {
		
		$('#showAllImages').click(function() {
			
			$('#overlay').show();
			self.fetchMyPhotos();
			
		});
		
	};
	
	// New Overlay
	
	this.setClickedPhoto = function() {
		
		$('.image-profile-wrapper').click(function(){
    		
    		// Get event content
    		var child = ($(this).children());
    		var filename = $(this).find("img").attr( "src" );
    		console.log(filename);
    		console.log('pathfilename :' + filename);
    		self.displayFullScreen(filename);
    	});		
		
	};
	
	this.closeOverlayNew = function() {
		
		$('#exit-large-vw').click(function() {
			$('#modal-grid').modal('hide');
		});
	};
	
	this.fetchMyPhotos = function() {
		
		$.ajax({
			type: "GET",
			url: "http://projetlibre/profile/myimgs",
			dataType: "json",
			success: function(data){
				self.displayMyPhotos(data);
			},
			error: function(result, status, error){
				console.log('erreur');
			}
		});
		
	};
	
	this.deleteMyAccount = function () {
		
		$("#delete-account").on('click', function(e) {
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
		
		
	}
	
	
	//Display full screen via image viewer
	this.displayFullScreen = function (filepath) {
	
		console.log('display fs fired');
		let filename = filepath.split("uploads/thumbnails/");
		
		var dir = "uploads/photos";
		var file = dir + "/" + filename[1];
		
		// Get clicked photo unique IDs
		this.getUniqueIds(filename).done(setValues);
		
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
		
		// Icon exit (?)
		//$('#myphoto-wrapper').append($('<div id="myphoto-panel">' + deleteButton + editButton + '</div>'));
		
		// Show photo overlay.
		$('#modal-grid').modal('show');
			
		
		
	};
	
	// Gets post and image unique ids.
	this.getUniqueIds = function(filename) {

		return $.ajax({
			type:"POST",
			url: "http://projetlibre/getids",
			dataType: "JSON",
			data: {filename : filename[1]}
		});
	};
	
	
	this.displayMyPhotos = function(data) {
		
		var photos = "";
		// 
		var template = "";
		//console.log(data);
		
		for( var i = 0; i < data.length; i++){
			// use template
			photos += "<div class='profile-photos-card card'><div class='profile-photo-wrapper'><img class='profile-photo card-img-top' src='uploads/photos/" + data[i].filename + "' /><div class='card-overlay'>Cliquez pour aggrandir</div></div><div class='card-body'><h5 class='card-title'>" + data[i].name + "</h5><div class='card-social'><i class='fas fa-heart'></i>" + data[i].liked + "</div></div></div>";
	    }
	    $('#profile-images-wrapper').html(photos); // use template
	    
	    
	    $('#profile-images-wrapper').ready(function (){
	    	
	    	$('.profile-photo-wrapper').click(function(){
	    		
	    		// Get event content
	    		var child = ($(this).children());
	    		var filename = $(this).find("img").attr( "src" );
	    		console.log(filename);
	    		self.displayFullScreen(filename);
	    	});
	    	
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
				url: "http://projetlibre/profile/deletecomment",
				data: formData,
				success: function(data){
					console.log('Success, comment deleted');
					console.log(data);
					self.refreshCommentsDom(comment);
				},
				error: function(result, status, error){
					console.log('Error on comment deletion : ' + status);
				}	
			});
			
			
		});
		
	};
	
	this.refreshCommentsDom = function(comment) {

		comment.fadeOut(800, function(){
			comment.remove();
			console.log('Comment succesfully removed on DOM');
		});

	};
	
	/* Deprecated 
	this.refreshComments = function() {
		
		$.ajax({
			type: "GET",
			url: "http://projetlibre/profile/getcomments",
			dataType: "JSON",
			success: function(data){
				console.log('Success, comment refreshed');
				console.log(data);
				self.displayComments(data);
			},
			error: function(result, status, error){
				console.log('Error on comment refresh : ' + status);
			}	
		});
		
	};
	*/
	
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
				url: "http://projetlibre/profile/getpost",
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
		
	this.deletePost = function() {
		
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
	};
	
	// Sets the unique ID value on inputs
	this.setIdPhoto = function() {
		
		var id = data;
		console.log(id);
		
		var elements = $(".imgId");
		elements.val(id);
		console.log(elements);
			
	};
					

	this.setEditInterface = function(){
		$('#modal-grid').modal('toggle');
		$('#myphoto-wrapper').hide();
		$('#tab-edit-img').toggleClass('show-edit');
		
		this.closeEditForm();
	};

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

	
}

$(window).on('load', function() {
	  $('#loading').addClass('loaded');
});

$("#profile-container").ready(function() {
    var profilePage = new profileComponents();
    profilePage.initialization();
});