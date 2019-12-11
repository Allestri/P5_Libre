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
	
	this.initialization = function() {
		
		this.closeOverlay();
		this.toggleLikeButton();
		this.toggleReportButton();
		this.commentPost();
		this.deleteComment();
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
					url: "http://projetlibre/map/deletecomment",
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
					url: "http://projetlibre/map/comment",
					data: formData,
					success: function(data){
						console.log('Success, image commented');
						self.showComments();
						$("#comment-form")[0].reset();
						
					},
					error: function(result, status, error){
						console.log('erreur');
					}
				});
			
		}));
		
	};
	
	this.showComments = function () {
		
		// WIP
		//var element = $('#comment').find('input');
		//var $postId = $('#postId').attr('value');
		var postId = $('#postId').attr('value');
		console.log(postId);

		$.ajax({
				type: "GET",
				url:"http://projetlibre/map/showcomment",
				data: "postId=" + postId,
				dataType: "json",
				success: function(data){
					console.log(data);
					self.displayComments(data);
					console.log('Success, comments loaded');
				},
				error: function(result, status, error){
					console.log('error on displaying comments');
				}
		});
	};
	
	this.displayComments = function(data) {

		console.log(data);
		var commentOption = "<div class='comment-options'><i class='fa fa-cog'></i></div>";
	    var comments = "";

	    for( var i = 0; i < data.length; i++){
	    	
	        comments += "<div class='card comment'><div class='comment-avatar'><img src='uploads/avatar/SOku/" + data[i].avatar_file + "' width='50' alt='avatar'></div><div class='card-body'><h5 class='card-title'>" + data[i].name + "</h5><p class='card-text'>" + data[i].content + "</p></div>" +
	        		"<div class='comment-options'><i class='fa fa-cog'></i><ul class='options-menu'><li><form class='deleteComment' method='POST' action='map/deletecomment' ><input type='hidden' name='commentId' value='" + data[i].id + "'/><input type='hidden' name='uid' value='6' /><button class='delete-comment-btn'>Supprimer</button></form></li></ul></div></div>";

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
			
			var child = $(this).children('.like');
			console.log(child);
			
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
			url: "http://projetlibre/map/getlikes",
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
				url: "http://projetlibre/map/like",
				data: formData,
				success: function(data){
					console.log('Success, image liked');
					self.refreshLikes(postId);
					console.log(postId);
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
				url: "http://projetlibre/map/unlike",
				data: formData,
				success: function(data){
					console.log('Success, image unliked');
					self.refreshLikes();
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
				url: "http://projetlibre/map/report",
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