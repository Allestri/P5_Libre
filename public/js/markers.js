function points(data, i){
	
	this.name = data[i].name;
	this.address = data[i].address;
	this.lng = data[i].lng;
	this.lat = data[i].lat;
	this.alt = data[i].altitude;
	this.type = data[i].type;
	this.width = data[i].width;
	this.height = data[i].height;
	this.size = data[i].size;
	this.user = data[i].user_name;
	
	
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