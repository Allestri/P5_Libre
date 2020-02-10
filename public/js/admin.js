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
	
	this.initialization = function () {
		
		this.setOverlay();
		this.closeOverlay();
		this.setDeletePost();
		this.getReportedPostDatas();
		
		this.getReportedCommentDatas();
		this.setDeleteComment();
		
		this.flipCard();
		this.expandComLogs();
		
		this.memberPagination();
		this.hideEditComForm();
		this.toggleNavbarStyle();
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
			//console.log($('#admin-pagination li'));
			
			e.preventDefault();
			var pageNbr = $(this).find("a").data('page');
			
			$("#members-tab").load("http://projetlibre/admin/paginate?page=" + pageNbr + " #members-tab>*", function(response, statusTxt, xhr) {
				if(statusTxt == "success")
					console.log(response);
					self.memberPagination();
				if(statusTxt == "error")
				    console.log("Error: " + xhr.status + ": " + xhr.statusText);
			});
			
			/*
			$.ajax({
				type:"GET",
				url: "http://projetlibre/admin",
				data: "page=" + pageNbr,
				success: function(data){
					console.log('Success, pagination');
					console.log(data);
					$("#members-card-wrapper").load("http://projetlibre/admin?page=2 #members-card-wrapper>*");
				},
				error: function(result, status, error){
					console.log('Error on data recovery');
				}
				
			});
			*/
			
		});
		
		
	};
	
	this.expandComLogs = function() {
		
		$('.expandComButton').click(function() {
			
			$(this).toggleClass('flipCross');
			$(this).closest('.card-body').find('.com-logs-hidden').toggle(500);
			
		});
		
		
	};
	
	
	// Admin CRUD
	
	
	this.flipCard = function() {
		
		$('.clickFlip').click(function() {
			
			console.log($(this).parents(".card").find('.flipper'));
			
			$(this).parents(".card").find('.flipper').toggleClass('flipped');
			
			
		});
	};
	
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
					url:"http://projetlibre/admin/delete",
					data: datas,
					success: function(data){
						console.log('Success, photo deleted');
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
	
	
	this.setDeleteComment = function() {
			
		$('.delete-com').click(function(e) {
						
			if(confirm("Voulez vous supprimer ce commentaire ?")){
				console.log("Confirmation suppression");
				e.preventDefault();
				
				var datas = $(this).parent().serialize();
				
				$.ajax({
					type: "POST",
					url:"http://projetlibre/admin/delete/comment",
					data: datas,
					success: function(data){
						console.log('Success, comment deleted');
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
		
		$('.edit').click(function() {
			
			var imgId = $(this).parent().serialize();
			
			$.ajax({
				type: "POST",
				url: "http://projetlibre/admin/getreport",
				data: imgId,
				dataType: "JSON",
				success: function(data){
					console.log('Success, data recovered');
					self.setValuesEdit(data);
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
		$('#edit-preview').append($('<img id="image-preview" />').attr('src', image));
		
	};
	
	this.getReportedCommentDatas = function() {
		
		$('.mod').click(function(e){
			
			e.preventDefault();
			
			var commentContent = $(this).parent().parent().parent().find('.comment-content').text();
			var commentId = $(this).parent().parent().parent().find('.comment-id').text();
			
			//var commentContent = $(this).parents('.card-body');
			var test = $('.card-body').find('.comment-content');
			
			console.log(commentContent);
			console.log(commentId);
			
			$('#input-content').val(commentContent);
			$('#input-id').val(commentId);
			
			
		});
		
	};
	
	this.setReportedComment = function() {
		
		
	};
	
	
	this.hideEditComForm = function() {
		
		$('#hide-edit-form').click(function() {
			
			$('#editComment').toggle(600);
			
		});
		
	};
			
			
	this.clearAllReports = function() {
		
		
		$('#clearReports').click(function() {
			
			$.ajax({
				type: "GET",
				url: "http://projetlibre/admin/clear",
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
	
	this.toggleNavbarStyle = function() {
		
		$('.back-dashboard').click(function(){
			
			$('#admin-nav li a').removeClass('active');
			$('#admin-nav li a').first().addClass('active');
			
		});
	};
}

$("#admin-panel").ready(function() {
    var adminPage = new admin();
    adminPage.initialization();
});