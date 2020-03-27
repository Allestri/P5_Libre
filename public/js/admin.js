/* Javascript for Admin page functionalities.
 * 
 * Open / Close Zoom reported image Overlay
 * Gets reported Post data
 * Edit Post
 * Delete Post
 * Clear All Reports
 * 
*/

function admin() {
	
	var self = this;
	var pUrl = "http://projetlibre/admin";
	
	this.initialization = function () {
		
		this.setOverlay();
		this.closeOverlay();
		this.setDeletePost();
		this.getReportedPostDatas();
		
		this.getReportedCommentDatas();
		this.setDeleteComment();
		
		this.expandComLogs();
		this.expandPostLogs();
		
		this.memberPagination();
		this.hideEditComForm();
	}
	
	this.closeOverlay = function() {
		
		$('#cross-admin').click(function() {
			$('#overlay').hide();
		});
	};
	
	// Set overlay open listener and clicked photo
	this.setOverlay = function() {
		
		$('.image-reported-wrapper').click(function(){
			
			var file = $(this).find("img").attr( "src" );
			$('#overlay').show();
			
			self.displayPhoto(file);
		});
		
	};

	//Display reported photo full screen
	this.displayPhoto = function(filepath) {

		let filename = filepath.split("uploads/photos/");
		
		var dir = "uploads/photos";
		var file = dir + "/" + filename[1];
		
		// Get clicked photo unique ID
		getMyPhotoId(filename).done(setValues);	
		
		// Check container, removes any photo before insertion
		if($('#myphoto').length > 0) {
			let photo = $('#admin-image-wrapper').find('img');
			photo.remove();
			console.log('Admin reported Container cleared !');
		}
		
		$('#admin-image-wrapper').prepend($('<img id="myphoto" />').attr('src', file));	
		
		
	};
	
	this.memberPagination = function() {
		
		$('#admin-pagination li').click(function(e){
			
			e.preventDefault();
			var pageNbr = $(this).find("a").data('page');
			
			$("#members-tab").load(pUrl + "/paginate?page=" + pageNbr + " #members-tab>*", function(response, statusTxt, xhr) {
				if(statusTxt == "success")
					// Refresh listeners
					self.memberPagination();
				if(statusTxt == "error")
				    console.log("Error: " + xhr.status + ": " + xhr.statusText);
			});
			
			
		});
		
		
	};
	
	
	this.expandComLogs = function() {
		
		$('.expandCom').click(function() {
			
			$(this).toggleClass('flipCross-flipped');
			$(this).closest('.card').find('.com-logs-hidden').toggle(500);
			
		});
		
		
	};
	
	this.expandPostLogs = function() {
		
		$('.expandPostButton').click(function() {
			
			$(this).toggleClass('flipCross-flipped');
			$(this).closest('.card-body').find('.collapse').toggle(500);
			
		});
		
	};
	
	
	// Admin CRUD		
	
	this.setDeletePost = function() {
		
		$('.delete').click(function(e) {
			
			if(confirm("Voulez vous supprimer ce post ?")){
				console.log("Confirmation suppression");
				e.preventDefault();
				
				//console.log(filename);
				var datas = $("#deleteImg").serialize();
	
				console.log(datas);	
				$.ajax({
					type: "POST",
					url: pUrl + "/delete",
					data: datas,
					success: function(data){
						console.log('Success, photo deleted');
						$("#reports-posts-duo").load(pUrl + " #reports-posts-duo>*");
					},
					error: function(result, status, error){
						console.log('Error on photo deletion');
					}
				});
				
				// Confirmation
				// clear window
				
			} else {
				e.preventDefault();
			}
			
		});
	};
	
	this.refreshDom = function() {
		
		$("#reported-comments-wrapper").load(pUrl + " #reported-comments-wrapper>*");
		
	};
	
	
	this.setDeleteComment = function() {
			
		$('.delete-com').click(function(e) {
						
			if(confirm("Voulez vous supprimer ce commentaire ?")){
				console.log("Confirmation suppression");
				e.preventDefault();
				
				var datas = $(this).parent().serialize();
				
				$.ajax({
					type: "POST",
					url: pUrl + "/delete/comment",
					data: datas,
					success: function(data){
						console.log('Success, comment deleted');
						self.refreshDom();
					},
					error: function(result, status, error){
						console.log('Error on comment deletion');
					}
				});
				
				// Confirmation
				// clear window
				
			} else {
				e.preventDefault();
			}
			
		});
	};
	
	
	// Get values from a report to edit form
	this.getReportedPostDatas = function() {
		
		$('.edit').click(function(e) {
			
			e.preventDefault();
			var imgId = $(this).parent().serialize();
			
			$.ajax({
				type: "POST",
				url: pUrl + "/getreport",
				data: imgId,
				dataType: "JSON",
				success: function(data){
					console.log('Success, data recovered');
					self.setValuesEdit(data);
					$('#form-collapse').collapse('show');
				},
				error: function(result, status, error){
					console.log('Error on data recovery');
				}	
			});
		});
		
	};
	
	// Sets reported post datas on form
	this.setValuesEdit = function(data) {
		
		console.log(data);
		var imgId = data.id;
		var title = data.name;
		var author = data.author;
		var description = data.content;
		var filename = data.filename;
		
		var image = "uploads/photos/" + filename;
		
		$('#edit-imgId').val(imgId);
		$('#name').val(title);
		$('#author').val(author);
		$('#description').val(description);
		$('#edit-preview').html($('<img id="image-preview" />').attr('src', image));
		
	};
	
	this.getReportedCommentDatas = function() {
		
		$('.mod').click(function(e){
			
			e.preventDefault();
			
			// Get event content
			var button = $(this);
    		var comment = button.closest('.card');
    		
    		// Data attributes
    		let commentId = comment.attr("data-id");

    		console.log(comment);
    		console.log(button);
			
			var commentContent = $(this).parent().parent().parent().find('.comment-content').text();
			//var commentId = $(this).parent().parent().parent().find('.comment-id').text();
			
			//var commentContent = $(this).parents('.card-body');
			var test = $('.card-body').find('.comment-content');
			
			console.log(commentContent);
			console.log(commentId);
			
			$('#input-content').val(commentContent);
			$('#input-id').val(commentId);
			$('#edit-comment-collapse').collapse('show');
			
			
		});
		
	};
	
	this.setReportedComment = function() {
		
		
	};
	
	
	this.hideEditComForm = function() {
		
		$('#hide-edit-form').click(function() {
			
			$('#edit-comment-collapse').collapse('hide');
			
		});
	};
			
			
	this.clearAllReports = function() {
		
		
		$('#clearReports').click(function() {
			
			$.ajax({
				type: "GET",
				url: pUrl + "/clear",
				data: formData,
				success: function(data){
					console.log('Success, reports cleared');
				},
				error: function(result, status, error){
					console.log('error reports');
				}
			});
			
		});
		
	};
	
}

$("#admin-panel").ready(function() {
    var adminPage = new admin();
    adminPage.initialization();
});