function Social () {
	
	
	this.initialization = function() {
		
		this.deleteComment();
	}
	
	
	this.deleteComment = function() {
		console.log('delete comment?');
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
	
	
	
}