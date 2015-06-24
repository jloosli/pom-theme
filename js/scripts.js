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
	
		
	// Image path updating: when running locally
	if(window.location.hostname === 'moms.loc'){
		$('img').each(function(item){
			"use strict";
			var src=this.getAttribute('src');
			if(src.indexOf('/')===0){
				this.src='https://powerofmoms.com'+src;
			}
		});
	}

});
// Add facebook conversion
(function(w) {
    var _fbq = w._fbq || (w._fbq = []);
    if (!_fbq.loaded) {
        var fbds = document.createElement('script');
        fbds.async = true;
        fbds.src = 'https://connect.facebook.net/en_US/fbds.js';
        var s = document.getElementsByTagName('script')[0];
        s.parentNode.insertBefore(fbds, s);
        _fbq.loaded = true;
    }
    _fbq.push(['addPixelId', '402087909938778']);
})(window);
window._fbq = window._fbq || [];
window._fbq.push(['track', 'PixelInitialized', {}]);

/** Facebook app information **/
(function(d, s, id) {
    var js, fjs = d.getElementsByTagName(s)[0];
    if (d.getElementById(id)) return;
    js = d.createElement(s); js.id = id;
    js.async = true;
    js.src = "https://connect.facebook.net/en_US/sdk.js#xfbml=1&appId=279003958786143&version=v2.0";
    fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));
