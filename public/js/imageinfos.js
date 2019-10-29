function imagesInfos(data){
	
	this.name = data.name;
	this.address = data.address;
	this.lng = data.lng;
	this.lat = data.lat;
	this.alt = data.altitude;
	this.type = data.type;
	this.width = data.width;
	this.height = data.height;
	this.size = data.size;
	this.user = data.user_name;
	
	
	this.displayInfos = function() {
		
		$("#name").replaceWith("<span id='name'> " + this.name + "</span>");
		$("#author").replaceWith("<span id='author'> " + this.user + "</span>");
		$("#address").replaceWith("<span id='address'> " + this.address + "</span>");
		$("#long").replaceWith("<span id='long'> " + this.lng + "</span>");
		$("#lat").replaceWith("<span id='lat'> " + this.lat + "</span>");
		$("#alt").replaceWith("<span id='alt'> " + this.alt + " m</span>");

		$("#width").replaceWith("<span id='width'> " + this.width + " px</span>");
		$("#height").replaceWith("<span id='height'> " + this.height + " px</span>");
		$("#type").replaceWith("<span id='type'> " + this.type + "</span>");
	};
}