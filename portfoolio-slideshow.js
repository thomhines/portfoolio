jQuery(document).ready(function($){
	var portfoolio_slideshow_hover = false;
	var portfoolio_autoplay_interval;
	
	// HIDE IMAGES
	$('.portfoolio_slideshow .portfoolio_slide:gt(0)').hide();
	
	// CENTER SMALLER IMAGES IN SLIDE
	$('.portfoolio_slide img').each(function() {
		// margin-top = half of box height - half of image height
		var margin_top = $(this).parent().height()/2 - $(this).height()/2;
		$(this).css('margin-top', margin_top);
	});
	
    	
	// PAUSE ON HOVER?
	$('.portfoolio_slideshow.pause_on_hover').hover(function() { portfoolio_slideshow_hover = true; }, function() { portfoolio_slideshow_hover = false; });
	
	// START SLIDESHOW
	if($('.portfoolio_slideshow.autoplay').size() > 0) {
		portfoolio_autoplay_interval = setInterval(function() { if(!portfoolio_slideshow_hover) portfoolio_next_image(); }, $('.portfoolio_slideshow').data('delay'));
	}
		
	
	
	$('.portfoolio_prev_image').click(function() {
		portfoolio_prev_image();
		
		// RESET TIMING ON SLIDESHOW
		clearInterval(portfoolio_autoplay_interval); 
		if(portfoolio_autoplay_interval) portfoolio_autoplay_interval = setInterval(function() { if(!slideshow_hover) portfoolio_next_image(); }, $('.portfoolio_slideshow').data('delay'));
	});
	
	$('.portfoolio_next_image').click(function() {
		portfoolio_next_image();
		
		// RESET TIMING ON SLIDESHOW
		clearInterval(portfoolio_autoplay_interval);
		if(portfoolio_autoplay_interval) portfoolio_autoplay_interval = setInterval(function() { if(!portfoolio_slideshow_hover) portfoolio_next_image(); }, $('.portfoolio_slideshow').data('delay'));
	});

	
	
	
	
	function portfoolio_next_image() {
		console.log('next');
		$('.portfoolio_slideshow .portfoolio_slide:nth-child(2)').fadeIn("slow");
		var curimage = $('.portfoolio_slideshow .portfoolio_slide:nth-child(2)').data('slide-num');
		var caption = $('.portfoolio_slideshow .portfoolio_slide:nth-child(2)').data('caption');
		var iframe = $('.portfoolio_slideshow .portfoolio_slide:first-child').find('iframe');
		if(iframe.size() > 0) iframe.attr('src', iframe.attr('src')); 
		$('.portfoolio_slideshow .portfoolio_slide:first-child').fadeOut("slow").insertBefore('.portfoolio_slideshowcontrols');
		updateProgressIndicator(curimage);
		//$('.portfoolio_imagecaption').html(caption);
	}
	
	function portfoolio_prev_image() {
		$('.portfoolio_slideshow .portfoolio_slide').last().fadeIn("slow").prependTo('.portfoolio_slideshow');
		var curimage = $('.portfoolio_slideshow .portfoolio_slide').data('slide-num');
		// RESET VIDEOS SO THAT THEY DON'T CONTINUE TO PLAY
		var iframe = $('.portfoolio_slideshow .portfoolio_slide:nth-child(2)').find('iframe');
		if(iframe.size() > 0) iframe.attr('src', iframe.attr('src')); 
		$('.portfoolio_slideshow .portfoolio_slide:nth-child(2)').fadeOut("slow");
		updateProgressIndicator(curimage);
	}
	
	function updateProgressIndicator(img_num) {
		if($('.portfoolio_progress_indicator.number').length > 0) {
			$('.portfoolio_progress_indicator.number').html(img_num+"/"+$('.portfoolio_progress_indicator').attr('totalimages'));
		}
		else if($('.portfoolio_progress_indicator.dots').length > 0) {
			$('.portfoolio_progress_indicator .dot').removeClass('current');
			$('.portfoolio_progress_indicator .dot:eq('+(img_num-1)+')').addClass('current');
		}
	}

});