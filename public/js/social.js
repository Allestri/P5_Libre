/* Javascript for Social functionalities.
 * 
 * Comment Post
 * Display comments
 * Like / Unlike post
 * Report post
 * 
*/

function Social () {
	
	var self = this;
	var url = "http://projetlibre/map";
	
	this.initialization = function() {
		
		this.modalClean();
		//this.closeOverlay();
		this.toggleLikeButton();
		this.toggleReportButton();
		this.commentPost();
		this.deleteComment();
		this.reportComment();
	}
	
	
	this.closeOverlay = function() {
		
		$('#image-midsize').ready(function(){
			console.log('Image ready');
			
			$('#image-midsize').click(function(){
				
				$('#overlay').hide();
				console.log(this);
				self.removeListeners();
			});
		});
	};
	
	// Removes any listeners when a modal is closing.
	this.modalClean = function() {
		
		$('#modal-grid').on('hidden.bs.modal', function(e) {
			console.log('modal cleaned');
			self.removeListeners();
		});
		
	};
	
	this.removeListeners = function() {
		
		$("#comment-form").off('submit');
		$('#image-midsize').unbind('click');
		$('.like').unbind('click');
		$('.report').unbind('click');
	};
	
	
	this.deleteComment = function() {

		$(".delete-comment-btn").on('click', (function(e) {
			e.preventDefault();
			
			var formData = $(this).parent().serialize();
			console.log(formData);
			
			$.ajax({
					type: "POST",
					url: url +"/deletecomment",
					data: formData,
					success: function(data){
						console.log('Success, comment deleted'),
						self.showComments();
					},
					error: function(result, status, error){
						console.log('error on comment deletion');
					}
				});
		}));
	};
	
	
	// Comment a Post
	
	this.commentPost = function() {
		
		$("#comment-form").on('submit', (function(e) {
			e.preventDefault();
			
			var formData = $("#comment-form").serialize();
			console.log(formData);
			$.ajax({
					type: "POST",
					url: url + "/comment",
					data: formData,
					success: function(data){
						console.log('Success, image commented');
						self.removeListeners();
						self.showComments();
						$("#comment-form")[0].reset();
						
					},
					error: function(result, status, error){
						console.log('erreur:' + error);
					}
				});
			
		}));
		
	};
	
	this.reportComment = function() {
		
		$(".reportCom-form").on('submit', (function(e){
			e.preventDefault();
			
			var formData = $(this).serialize();

			$.ajax({
				type: "POST",
				url: url + "/report-comment",
				data: formData,
				success: function(data){
					console.log('Success, comment reported');
					//self.removeListeners();
					// Update report form.
					
				},
				error: function(result, status, error){
					console.log('error ' . status);
				}
			});
			
		}));
	};
	
	this.showComments = function () {
		
		// WIP
		//var element = $('#comment').find('input');
		//var $postId = $('#postId').attr('value');
		var postId = $('#postId').attr('value');
		console.log('Post ID: ' + postId);

		$.ajax({
				type: "GET",
				url: url + "/showcomment",
				data: "postId=" + postId,
				dataType: "json",
				success: function(data){
					console.log(data);
					self.displayComments(data);
					console.log('Success, comments loaded');
				},
				error: function(result, status, error){
					console.log('error on displaying comments : ' + error);
				}
		});
	};
	
	// Checks if a custom avatar is set, returns default avatar file if null.
	this.getAvatar = function(username, avatarFile) {
		
		let avatar;
		if(avatarFile === ""){
    		return avatar = 'uploads/avatar/avatar_default.png';
    	}
    	return avatar = 'uploads/avatar/' + username + '/' + avatarFile;
	};
	
	this.displayComments = function(data) {

		console.log(data);
		var comments = "";
		
		var optionsStart = "<div class='comment-options'><i class='fas fa-cog'></i><ul class='options-menu'><li><form class='reportCom-form' method='POST' action='map/report-comment' >";
	    var optionsEnd = "<button class='report-comment-btn' type='submit'>Signaler</button></form></li></ul></div>";
	    
	    for( var i = 0; i < data.length; i++){
	    	
	    	let avatar = this.getAvatar(data[i].name, data[i].avatar_file);
	    	
	    	comments += "<div class='card comment'><div class='comment-avatar'><img src='" + avatar + "' width='50' alt='avatar'></div><div class='comment-body'><h5 class='comment-title'>" + data[i].name + "</h5>"
	    	+ optionsStart + "<input type='hidden' name='commentId' value='" + data[i].id + "'/>" + optionsEnd +
    		"<span class='comment-date' data-toggle='tooltip' data-placement='bottom' title='" + data[i].com_date.absolute + "'>" + data[i].com_date.relative +	"</span><p class='comment-text'>" + data[i].content + "</p></div></div>";

	    }
	    $('#comments-wrapper').html(comments);
	    // Refresh comment count
	    $('.comments-count').text(data.length);
	   
	    // Init delete button listener.
	    this.initialization();
	};
	
	
	
	// Toggle like unlike button then fire the right method.	
	this.toggleLikeButton = function() {
		
		$('.like').click(function(e){
			e.preventDefault();
			$('.like').toggleClass('active', 1000);
			
			// Fire like or unlike.						
			if($('.like').hasClass('active')) {
				console.log('liked');
				self.likePost();
			} else {
				console.log('unliked');
				self.unlikePost();
			}
			
		});
	};
	
	this.refreshLikes = function(postId) {
		
		console.log(postId);
		$.ajax({
			type: "GET",
			url: url + "/getlikes",
			data: "postId=" + postId,
			dataType: "JSON",
			success: function(data){
				console.log(data);
				$('.likes-count').text(data.likes);
			},
			error: function(result, status, error){
				console.log('error on refreshing likes');
			}	
		});
		
	};
	
	this.likePost = function() {
		
		var formData = $("#likeImg").serialize();
		var postId = $('#postId').attr('value');
		
		$.ajax({
				type: "POST",
				url: url + "/like",
				data: formData,
				success: function(data){
					console.log('Success, image liked');
					self.refreshLikes(postId);
					console.log(postId);
					$.notify('Liked', 'success');
				},
				error: function(result, status, error){
					console.log('erreur');
				}	
			});
	};
	
	this.unlikePost = function() {
		
		var formData = $("#likeImg").serialize();
		console.log(formData);	
		
		$.ajax({
				type: "POST",
				url: url + "/unlike",
				data: formData,
				success: function(data){
					console.log('Success, image unliked');
					self.refreshLikes();
					$.notify('Unliked', 'success');
				},
				error: function(result, status, error){
					console.log('erreur');
				}
			});
	};
	
	// Toggle report unreport button then fire the right method.
	
	this.toggleReportButton = function() {
		
		$('.report').click(function(e){
			e.preventDefault();
			$('.report').toggleClass('disabled', 1000);
			self.reportPost();
		});
	};
	
	this.reportPost = function() {
		
		var formData = $("#reportImg").serialize();
		console.log(formData);
		$.ajax({
				type: "POST",
				url: url + "/report",
				data: formData,
				success: function(data){	
					console.log('Success, image reported');
				},
				error: function(result, status, error){
					console.log('error on reporting image');
				}
			});
	};
	
	

	
}