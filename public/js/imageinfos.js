function postInfos(data){
	
	
	this.name = data.name;
	this.address = data.address;
	this.description = data.description;
	this.lng = data.lng;
	this.lat = data.lat;
	this.alt = data.altitude;
	this.type = data.type;
	this.width = data.width;
	this.height = data.height;
	this.size = data.size;
	this.user = data.author;
	this.authorAvatar = data.author_avatar;
		
	
	this.displayInfos = function() {
		
		// Info sidepanel
		$("#main-title").html("<h2> " + this.name + "</h2>");
		
		$("#author").replaceWith("<span id='author'> " + this.user + "</span>");
		$('#author').prepend($('<img id="author-avatar" />').attr('src', 'uploads/avatar/' + this.authorAvatar));
		
		$("#address").replaceWith("<span id='address'> " + this.address + "</span>");
		$("#long").replaceWith("<span id='long'> " + this.lng + "</span>");
		$("#lat").replaceWith("<span id='lat'> " + this.lat + "</span>");
		$("#alt").replaceWith("<span id='alt'> " + this.alt + " m</span>");

		$("#width").replaceWith("<span id='width'> " + this.width + " px</span>");
		$("#height").replaceWith("<span id='height'> " + this.height + " px</span>");
		$("#type").replaceWith("<span id='type'> " + this.type + "</span>");
		
		//Overlay
		$('#image-name').text(this.name);
		$('#image-description').text(this.description);
	};
	
	this.displayComments = function() {

		console.log(data);
	    var comments = "";

	    for( var i = 0; i < data.comments.length; i++){

	        comments += "<div class='card'><div class='card-body'><h5 class='card-title'>" + data.comments[i].name + "</h5><p class='card-text'>" + data.comments[i].content + "</p></div></div>";

	    }
	    $('#comments-wrapper').html(comments);
	};
	
}