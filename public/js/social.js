function Social () {
	
	
	var self = this;
	
	this.initialization = function() {
		

		this.toggleLikeButton();
		this.toggleReportButton();
		this.deleteComment();
	}
	
	
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
						showComments();
					},
					error: function(result, status, error){
						console.log('error on comment deletion');
					}
				});
		}));
	};
	
	
	/* Comment an image */
	
	
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
	
	this.toggleReportButton = function() {
		
		$('.report').click(function(e){
			e.preventDefault();
			$('.report').toggleClass('disabled', 1000);
			self.reportPost();
		});
	};
	
	this.refreshLikes = function(postId) {
		
		
		$.ajax({
			type: "GET",
			url: "http://projetlibre/map/getlikes",
			data: postId,
			dataType: "JSON",
			success: function(data){
				console.log(data);
				$('.likes-count').text(data.likes);
			},
			error: function(result, status, error){
				console.log('erreur');
			}	
		});
		
	};
	
	this.likePost = function() {
		
		var formData = $("#likeImg").serialize();
		console.log(formData);
		
		$.ajax({
				type: "POST",
				url: "http://projetlibre/map/like",
				data: formData,
				success: function(data){
					console.log('Success, image liked');
					self.refreshLikes();
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