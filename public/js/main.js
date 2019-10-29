// General JS code not related to GMaps or Images viewers.

/*
$('#edit-profile-btn').on('click', function(){
	
	$('#tab-profile').toggle();
	$('#tab-profile-settings').toggle();
	
});
*/


// Profile Settings //


// Preview avatar before submitting.
$("#avatar-form").change(function(){

	var file = this.files[0];
	console.log(file);
	
	var reader = new FileReader();
	reader.onload = imageIsLoaded;
	reader.readAsDataURL(this.files[0]);
});

function imageIsLoaded(e){
	$('#avatar-preview-ctn').append($('<img id="avatar-preview" />').attr('src', e.target.result));
	//$('#preview-avatar').attr('src', e.target.result);
	$('#avatar-preview').attr('width', '150px');
	//$('#avatar-preview').attr('height', '150px');
};