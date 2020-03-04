function postInfos(data){
	
	
	this.name = data.name;
	this.address = data.address;
	this.description = data.description;
	this.date = data.upload_date;
	this.lng = data.lng;
	this.lat = data.lat;
	this.alt = data.altitude;
	this.type = data.type;
	this.width = data.width;
	this.height = data.height;
	this.size = data.size;
	this.user = data.author;
	this.authorAvatar = data.author_avatar;
	this.likes = data.likes;
	this.privacy = data.privacy;
		
	
	this.togglePanel = function() {
		
		$('#info-btn-ext').click(function() {
			$('#map-wrapper').toggleClass('contract');
			$('#image-info-panel').toggleClass('show');
		});

		$('#info-btn-min').click(function() {
			$('#image-info-panel').toggleClass('show-min');
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
	
	
	// Gets privacy number, returns icon + text to display
	this.getPrivacy = function(privacy) {
		
		let iconPublic = "<i class='fa fa-users' aria-hidden='true'></i>";
		let iconPrivate = "<i class='fas fa-user-secret'></i>";
		
		if(privacy == 0){
			return "Publique";
		} else if (privacy == 1) { 
			return "Amis";
		} else {
			return "Privée";
		}		
	};
	
	
	this.displayInfos = function() {
		
		// Info sidepanel
		$("#main-title").html("<h2> " + this.name + "</h2>");
		
		$("#author").replaceWith("<h3 id='author' title='Auteur de la photo' >" + this.user + "</h3>");
		
		var avatar = this.getAvatar(this.user, this.authorAvatar);
		//var privacy = this.getPrivacy(this.privacy);
		
		$('#author-avatar').attr('src', avatar);
		
		$('#date').replaceWith("<span id='date'>" + this.date + "</span>");
		$('#likes').replaceWith("<span id='likes'>" + this.likes + "</span>");
		$('#comments').replaceWith("<span id='comments'>" + data.comments.length + "</span>");
		$('#privacy').text(this.getPrivacy(this.privacy));
		$("#long").replaceWith("<span id='long'> " + this.lng + "</span>");
		$("#lat").replaceWith("<span id='lat'> " + this.lat + "</span>");
		$("#alt").replaceWith("<span id='alt'> " + this.alt + " m</span>");

		$('#dimension').replaceWith("<span id='dimension'> " + this.width + " x " + this.height + " px</span>");
		$("#width").replaceWith("<span id='width'> " + this.width + " px</span>");
		$("#height").replaceWith("<span id='height'> " + this.height + " px</span>");
		$("#type").replaceWith("<span id='type'> " + this.type + "</span>");
		
		//Overlay
		$('#image-name').text(this.name);
		$('#image-description').text(this.description);
		$('.likes-count').text(this.likes);
		$('.comments-count').text(data.comments.length);		
		
		// Like Button !
		if(data.uliked > 0){
			$('.like').addClass('active');
		} else {
			$('.like').removeClass('active');
		}
	};
	
	this.displayComments = function() {

		console.log(data);
		
		// Comment template components.
	    var comments = "";
	    
	    var optionsStart = "<div class='comment-options'><i class='fas fa-cog'></i><ul class='options-menu'><li><form class='reportCom-form' method='POST' action='map/report-comment' >";
	    var optionsEnd = "<button class='report-comment-btn' type='submit'>Signaler</button></form></li></ul></div>";
	    

	    for( var i = 0; i < data.comments.length; i++){
	    	
	    	let avatar = this.getAvatar(data.comments[i].name, data.comments[i].avatar_file);

	    	
	        comments += "<div class='card comment'><div class='comment-avatar'><img src='" + avatar + "' width='50' alt='avatar'></div><div class='comment-body'><h5 class='comment-title'>" + data.comments[i].name + "</h5>" 
	        		+ optionsStart + "<input type='hidden' name='commentId' value='" + data.comments[i].id + "'/>" + optionsEnd +
	        		"<span class='comment-date' data-toggle='tooltip' data-placement='bottom' title='" + data.comments[i].com_date.absolute + "'>" + data.comments[i].com_date.relative +	"</span><p class='comment-text'>" + data.comments[i].content + "</p></div></div>";

	    }
	    $('#comments-wrapper').html(comments);
	    //deleteComment();
	};
	
}