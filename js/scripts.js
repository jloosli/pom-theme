jQuery( document ).ready(function( $ ) {
	
	// initialize collapse script
	//$('.collapse').collapse();
	
	// hide collapsable things
	//$('.collapse').collapse('hide');
	
	
	
	//reposition author box
	if( $('.author-box').length && $('.linkwithin_hook').length ) {
		var authorBoxHtml = $('.author-box').html();
		$('.author-box').remove();
		$('.linkwithin_hook').before("<div class='author-box'>" + authorBoxHtml + "</div>");
	}
	
		
	// Image path updating: COMMENT OUT ON LIVE
	//[].forEach.call($('img'),function(img){var src=img.getAttribute('src');if(src.indexOf('/')===0){img.src='http://powerofmoms.com'+src;}})
});