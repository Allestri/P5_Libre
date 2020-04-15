// General JS code not related to GMaps or Images viewers.



/*
$('#edit-profile-btn').on('click', function(){
	
	$('#tab-profile').toggle();
	$('#tab-profile-settings').toggle();
	
});
*/



// Navbar

/*
$(document).ready(function() {
	
	console.log($('#navbar .navbar-nav a'));
	
	var url = window.location.href;
	var activePage = url;
		 
	$('.navbar-nav a').each(function () {
		var linkPage = this.href;
		console.log(linkPage);
		
		if(activePage == linkPage){
			$(this).closest( 'li' ).addClass( 'active' );
		}
		
	});
	/*
	$( '#navbar .navbar-nav a' ).on( 'click', function () {
		console.log('bonjour');
		$( '#navbar .navbar-nav' ).find( 'li.active' ).removeClass( 'active' );
		$( this ).parent( 'li' ).addClass( 'active' );
	});
	
	
});
*/

const url = "http://projetlibre/";

/* Home page 
 *  Fade-in/out library
 *  Carousel - not using
 */

$('#home-banner').ready(function() {
	
	AOS.init();
	
});

$('.carousel').carousel({
	  interval: 7000
})
	
/* 
 * Flash messages 
 * 
 * Delay 
 * Dismiss 
*/

$( '.alert-success.alert-dismissible' ).delay( 2000 ).fadeOut( 400 );


// Dismiss the flash messages after login fire.
$('#login-form-btn').click(function() {
	
	var flash = document.getElementsByClassName('alert-success');
	
	if(flash.lenght !== 0){
		console.log('Flash message on Dom');
		$('.alert-success').alert('close');
	}
	
});


/* 
 * Popovers
 */

$(document).ready(function() {
    $("body").tooltip({ selector: '[data-toggle=tooltip]' });
});




$('#triggerTest').click(function(e){
	
	e.preventDefault();
	$("#dynamic-test").load("http://projetlibre/map/test #dynamic-test>*", function(response, statusTxt, xhr) {
		if(statusTxt == "success")
			console.log(response);
		if(statusTxt == "error")
		    console.log("Error: " + xhr.status + ": " + xhr.statusText);
	});
	
});



// Memberlist
/*
$('.memberlist-btn').click(function (e) {
	
	e.preventDefault();
	
	var eltBtn = this;
	var attr = $(eltBtn).attr('form');
	console.log(eltBtn);
	console.log(attr);
	
	var userId = attr.split("removefriend");
	console.log(userId[1]);
	
	var formData = $("#comment-form").serialize();
	
});
*/