// Special thanks to 
// http://www.webmaster-source.com/2013/02/06/using-the-wordpress-3-5-media-uploader-in-your-plugin-or-theme/

jQuery(document).ready(function($){
	$('.portfoolio_media_rows').sortable({
		refreshPositions: true,
		opacity: 0.6,
		scroll: true,
		containment: 'parent',
		placeholder: 'ui-placeholder',
		tolerance: 'pointer',
		start: function (event, ui) {
		},
		update: function() {
			updateMediaList();
		}
	}).disableSelection(); 
 
 
 	var custom_uploader;
 	$('#upload_image_button').click(function(e) {
 
		e.preventDefault();
 
		//If the uploader object has already been created, reopen the dialog
		if (custom_uploader) {
			custom_uploader.open();
			return;
		}
 
		//Extend the wp.media object
		custom_uploader = wp.media.frames.file_frame = wp.media({
			title: 'Choose Image',
			button: {
				text: 'Choose Image'
			},
			multiple: false
		});
 
		//When a file is selected, grab the URL and set it as the text field's value
		custom_uploader.on('select', function() {
			attachment = custom_uploader.state().get('selection').first().toJSON();
			addMediaItem(attachment.id, attachment.sizes.thumbnail.url, attachment.url);
		});
 
		//Open the uploader dialog
		custom_uploader.open();
 
	});
 
 
	$('#add_new_video').click(function() {
		video_url = $('#video_url').val();
		if(video_url.indexOf('://') == -1) video_url = 'http://'+video_url;
		console.log(video_url);
		var video_type;
		// CHECK FOR VIMEO ID
		video_id = video_url.match(/vimeo.com\/(.*\/)?([^#\?]*)/);
		if(video_id) {
			console.log(video_url);
			
			video_type = 'vimeo';
			video_id = video_id[video_id.length-1];
		}
		
		// CHECK FOR YOUTUBE ID
		if(!video_id) {
			video_id = video_url.split('v=')[1];
			if(video_id && video_url.toLowerCase().indexOf('youtube') != -1) {
				var ampersandPosition = video_id.indexOf('&');
				if(ampersandPosition != -1) {
				  video_id = video_id.substring(0, ampersandPosition);
				}
				video_type = 'youtube';
			} else video_id = "";
		}
		// CHECK FOR SOUNDCLOUD ID
		if(!video_id) {
			video_id = video_url.match(/^soundcloud.com\/(.*\/)(.*)/);
			if(!video_id) video_id = video_url.match(/^snd.sc\/(.*)/);
			video_type = 'soundcloud';
			//video_id = video_id[video_id.length-1];
		}
		
		// NOT A VALID VIDEO
		if(!video_id) {
			alert('The video URL is not supported by Portfoolio.');
		}
		
		if(video_type == 'vimeo') {
			$.getJSON('http://www.vimeo.com/api/v2/video/' + video_id + '.json?callback=?', {format: "json"}, function(data) {
				var thumbnail = data[0].thumbnail_medium;
				addMediaItem($('#video_url').val(), thumbnail, video_url);
			});
		} else if(video_type == 'youtube') {
			var thumbnail = "http://img.youtube.com/vi/" + video_id + "/0.jpg";
			addMediaItem($('#video_url').val(), thumbnail, video_url);
		} else if(video_type == 'soundcloud') {
			$.getJSON('http://soundcloud.com/oembed', {format: "json", url: video_url}, function(data) {
				var thumbnail = data['thumbnail_url'];
				addMediaItem($('#video_url').val(), thumbnail, video_url);
			});
		} 
	});
	
	
	$('.remove_media_item').on('click', function() {
		if(confirm('Are you sure you want to remove this item?')) {
			var row = $(this).parent().parent();
			row.fadeOut(500, function() {
				$(this).remove();
				updateMediaList();
			});
		}
	});
	
	
	$('.set_as_thumbnail').on('click', function() {
		var post_id = $('#post_ID').val();
		var image_id = $(this).parent().parent().data('media-value');
		var data = {
			action: 'portfoolio_set_thumbnail',
			post_id: post_id,
			image_id: image_id
		};
		$.post(ajaxurl, data, function(msg) {
			$('#set-post-thumbnail').empty().html(msg)
				.find('img').attr('width', 266).attr('height', 118);
		});
	});
	
	function updateMediaList() {
		var new_items;
		$('.portfoolio_media_row').each(function() {
			if(!new_items) new_items = $(this).data('media-value');
			else new_items += ', ' + $(this).data('media-value');
		});
		$('#media_items').val(new_items);
	}
	

	function addMediaItem(item, thumbnail, link) {
		var media_items = $('#media_items');
		var current_items = $('#media_items').val();
		if(current_items) media_items.val(current_items+", "+item);
		else media_items.val(item);
		
		// ADD NEW ROW AND THUMBNAIL
		var new_row = $(".portfoolio_media_row").first().clone(true, true);
		new_row.attr('data-media-value', item).css('display', 'none').appendTo($(".portfoolio_media .portfoolio_media_rows"));
		new_row.find('img').attr('src', thumbnail);
		new_row.find('a').attr('href', link);
		if(link.indexOf('vimeo') == -1 && link.indexOf('youtube') == -1) {
			new_row.find('a').removeAttr('target').addClass('thickbox');
		} else {
			new_row.find('a').attr('target', '_blank').removeClass('thickbox');;
		}
			
		// CLEAR ADD_MEDIA FIELDS
		$('#video_url').val('');
		new_row.fadeIn(500);
		
		// REMOVE 'NO IMAGES' ROW
		$('.no_images').fadeOut(100);
	}
 
});