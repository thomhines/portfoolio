jQuery(document).ready(function($){
	
	// HIDE IMAGES
	$('.portfoolio_slideshow .portfoolio_slide:gt(0)').hide();
	
    	
	// PAUSE ON HOVER?
	$('.portfoolio_slideshow').hover(function() { portfoolio_slideshow_hover = true; }, function() { portfoolio_slideshow_hover = false; });
	
	// START SLIDESHOW
	if($('.portfoolio_slideshow.autoplay').size() > 0) 
		var portfoolio_autoplay_interval = setInterval(function() { if(!slideshow_hover) next_image(); }, $('.portfoolio_slideshow').data('delay'));
	
	
	$('.portfoolio_prev_image').click(function() {
		portfoolio_prev_image();
		
		// RESET TIMING ON SLIDESHOW
		clearInterval(portfoolio_autoplay_interval); 
		if(autoplay_interval) autoplay_interval = setInterval(function() { if(!slideshow_hover) next_image(); }, $('.portfoolio_slideshow').data('delay'));
	});
	
	$('.portfoolio_next_image').click(function() {
		portfoolio_next_image();
		
		// RESET TIMING ON SLIDESHOW
		clearInterval(portfoolio_autoplay_interval);
		if(portfoolio_autoplay_interval) portfoolio_autoplay_interval = setInterval(function() { if(!portfoolio_slideshow_hover) next_image(); }, $('.portfoolio_slideshow').data('delay'));
	});

	
	
	
	
	function portfoolio_next_image() {
		$('.portfoolio_slideshow .portfoolio_slide:nth-child(2)').fadeIn("slow");
		var curimage = $('.portfoolio_slideshow .portfoolio_slide:nth-child(2)').data('slide-num');
		var caption = $('.portfoolio_slideshow .portfoolio_slide:nth-child(2)').attr('caption');
		// RESET VIDEOS SO THAT THEY DON'T CONTINUE TO PLAY
		var iframe = $('.portfoolio_slideshow .portfoolio_slide:first-child').find('iframe');
		if(iframe.size() > 0) iframe.attr('src', iframe.attr('src')); 
		$('.portfoolio_slideshow .portfoolio_slide:first-child').fadeOut("slow").insertBefore('.portfoolio_slideshowcontrols');
		$('.imagecount').html(curimage+"/"+$('.imagecount').attr('totalimages'));
		$('.imagecaption').html(caption);
	}
	
	function portfoolio_prev_image() {
		$('.portfoolio_slideshow .portfoolio_slide').last().fadeIn("slow").prependTo('.portfoolio_slideshow');
		var curimage = $('.portfoolio_slideshow .portfoolio_slide').data('slide-num');
		// RESET VIDEOS SO THAT THEY DON'T CONTINUE TO PLAY
		var iframe = $('.portfoolio_slideshow .portfoolio_slide:nth-child(2)').find('iframe');
		if(iframe.size() > 0) iframe.attr('src', iframe.attr('src')); 
		$('.portfoolio_slideshow .portfoolio_slide:nth-child(2)').fadeOut("slow");
		$('.imagecount').html(curimage+"/"+$('.imagecount').attr('totalimages'));
	}

});