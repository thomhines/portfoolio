jQuery(document).ready(function($){

	// set slideshow height
/*
	var tallest_img = 0;
	$('.portfoolio_slideshow img').each(function() {
		if($(this).height() > tallest_img) tallest_img = $(this).height();
	});

	$('.portfoolio_slideshow').height(tallest_img);
*/

	//$('.portfoolio_slideshow img').hide().wrap('<div class="image_wrapper" />').empty();
	
/*
	$('.portfoolio_slideshow .portfoolio_slide').each(function() {
		var img = $(this).children('img').attr('src');
		if(window.console) console.debug(img);
		$(this).css('background-image', 'url('+img+')');
	});
*/
	
	// hide images
	$('.portfoolio_slideshow .portfoolio_slide:gt(0)').hide();
	
	
    //$('.portfoolio_slideshow').append('<div class="portfoolio_slideshowcontrols"><span class="prev_image">Previous</span><span class="next_image">Next</span></div>');
    
    	
	// pause on hover?
	$('.portfoolio_slideshow').hover(function() { slideshow_hover = true; }, function() { slideshow_hover = false; });
	
	// start slideshow
	if($('.portfoolio_slideshow.autoplay').size() > 0) 
		var autoplay_interval = setInterval(function() { if(!slideshow_hover) next_image(); }, $('.portfoolio_slideshow').data('delay'));
	
	
	
	// add controls 
	// for each img, add button
	// create next/prev buttons
	$('.prev_image').click(function() {
		prev_image();
		clearInterval(autoplay_interval);
		if(autoplay_interval) autoplay_interval = setInterval(function() { if(!slideshow_hover) next_image(); }, $('.portfoolio_slideshow').data('delay'));
	});
	
	$('.next_image').click(function() {
		next_image();
		clearInterval(autoplay_interval);
		if(autoplay_interval) autoplay_interval = setInterval(function() { if(!slideshow_hover) next_image(); }, $('.portfoolio_slideshow').data('delay'));
	});

	
	
	
	
	function next_image() {
		$('.portfoolio_slideshow .portfoolio_slide:nth-child(2)').fadeIn("slow");
		curimage = $('.portfoolio_slideshow .portfoolio_slide:nth-child(2)').data('slide-num');
		caption = $('.portfoolio_slideshow .portfoolio_slide:nth-child(2)').attr('caption');
		// RESET VIDEOS SO THAT THEY DON'T CONTINUE TO PLAY
		var iframe = $('.portfoolio_slideshow .portfoolio_slide:first-child').find('iframe');
		if(iframe.size() > 0) iframe.attr('src', iframe.attr('src')); 
		$('.portfoolio_slideshow .portfoolio_slide:first-child').fadeOut("slow").insertBefore('.portfoolio_slideshowcontrols');
		$('.imagecount').html(curimage+"/"+$('.imagecount').attr('totalimages'));
		$('.imagecaption').html(caption);
	}
	
	function prev_image() {
		$('.portfoolio_slideshow .portfoolio_slide').last().fadeIn("slow").prependTo('.portfoolio_slideshow');
		curimage = $('.portfoolio_slideshow .portfoolio_slide').data('slide-num');
		// RESET VIDEOS SO THAT THEY DON'T CONTINUE TO PLAY
		var iframe = $('.portfoolio_slideshow .portfoolio_slide:nth-child(2)').find('iframe');
		if(iframe.size() > 0) iframe.attr('src', iframe.attr('src')); 
		$('.portfoolio_slideshow .portfoolio_slide:nth-child(2)').fadeOut("slow");
		$('.imagecount').html(curimage+"/"+$('.imagecount').attr('totalimages'));
	}

});