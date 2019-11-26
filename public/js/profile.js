/* Javascript Profile Page */

function profileComponents(){
	
	var self = this;
	
	this.initialization = function() {
		
		this.setEditForm();
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
	
	this.setEditForm = function() {
		
		$('#edit-post-btn').click(function(e) {
			
			e.preventDefault();
			var postId = $(this).parent().serialize();
			
			$.ajax({
				type: "POST",
				url: "http://projetlibre/newprofile/getpost",
				data: postId,
				dataType: "JSON",
				success: function(data){
					console.log('Success, data recovered');
					self.setValuesProfileEdit(data);
					// Hides overlay and toggle form tab
				    self.setEditInterface();
				},
				error: function(result, status, error){
					console.log('Error on data recovery');
				}	
			});
		});
		
	};
	

	this.setEditInterface = function(){
		$('#overlay').hide();
		$('#myphoto-wrapper').hide();
		$('#tab-edit-img').toggleClass('show-edit');
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

	
}