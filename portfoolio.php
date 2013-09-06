<?php
/*
Plugin Name: Portfoolio
Plugin URI: http://thomhines.com/portfoolio/
Description: Set up a gallery or portfolio using easier than ever
Version: 1.0
Author: Thom Hines
Author URI: http://thomhines.com/
*/

$version = '1.0';




/*----------------------------------------------------------------------*

	BACK-END FUNCTIONS

*----------------------------------------------------------------------*/

// LOCALIZATION
load_plugin_textdomain('portfoolio', false, basename( dirname( __FILE__ ) ).'/lang' );


// RUN ADMIN ONLY SCRIPTS/STYLES
add_action('admin_enqueue_scripts', 'portfoolio_admin_scripts');
function portfoolio_admin_scripts() {
	global $post;
	if ($_GET['post'] == 'work' || $post->post_type == 'work') {
		wp_enqueue_media();
		wp_register_script('portfoolio-js', plugins_url('portfoolio.js', __FILE__), array('jquery'), $version);
		wp_enqueue_script('portfoolio-js');
		wp_enqueue_style('portfoolio-styles', plugins_url('portfoolio.css', __FILE__), array(), $version);
	}
}

// RUN FRONT-END ONLY SCRIPTS/STYLES
add_action( 'wp_enqueue_scripts', 'portfoolio_frontend_scripts' );
function portfoolio_frontend_scripts() {
	global $post;
	if ($_GET['post'] == 'work' || $post->post_type == 'work') {
		wp_register_script('portfoolio-slideshow-js', plugins_url('portfoolio-slideshow.js', __FILE__), array('jquery'), $version);
		wp_enqueue_script('portfoolio-slideshow-js');
		wp_enqueue_style('portfoolio-slideshow-styles', plugins_url('portfoolio-slideshow.css', __FILE__), array(), $version);
	}
}


// REGISTER 'WORK' CUSTOM POST TYPE
add_action('init', 'portfoolio_register_custom_post_type');
function portfoolio_register_custom_post_type() {

	$labels = array(
		'name' => _x('Works', 'work'),
		'singular_name' => _x('Work', 'work'),
		'add_new' => _x('Add New Work', 'work'),
		'add_new_item' => _x('Add New Work', 'work'),
		'edit_item' => _x('Edit Work', 'work'),
		'new_item' => _x('New Work', 'work'),
		'view_item' => _x('View Work', 'work'),
		'search_items' => _x('Search Works', 'work'),
		'not_found' => _x('No works found', 'work'),
		'not_found_in_trash' => _x('No works found in Trash', 'work'),
		'parent_item_colon' => _x('Parent Work:', 'work'),
		'menu_name' => _x('Gallery', 'work'),
   );

	$args = array(
		'labels' => $labels,
		'hierarchical' => false,
		
		'supports' => array('title', 'editor', 'thumbnail'),
		'taxonomies' => array('category', 'post_tag'),
		'public' => true,
		'show_ui' => true,
		'show_in_menu' => true,
		'menu_position' => 5,
		'show_in_nav_menus' => true,
		'publicly_queryable' => true,
		'exclude_from_search' => false,
		'has_archive' => true,
		'query_var' => true,
		'can_export' => true,
		'rewrite' => true,
		'capability_type' => 'post'
   );
	register_post_type('work', $args);
}

// ADD ICONS TO ADMIN MENUS
add_action( 'admin_head', 'portfolio_icons' );
function portfolio_icons() {
    ?>
    <style type="text/css" media="screen">
        #menu-posts-work .wp-menu-image {
            background: url(<?php echo plugins_url('img/icons.png', __FILE__); ?>) 0px -26px no-repeat !important;
        }
		#menu-posts-work:hover .wp-menu-image, #menu-posts-work.wp-has-current-submenu .wp-menu-image {
            background-position: 0px 2px !important;
        }
		#icon-edit.icon32-posts-work {
			background: url(<?php echo plugins_url('img/bigicon.png', __FILE__); ?>) 4px 4px no-repeat;
		}
		
		@media only screen and (-webkit-min-device-pixel-ratio : 1.5), only screen and (min-device-pixel-ratio : 1.5) {
			#menu-posts-work .wp-menu-image {
	            background: url(<?php echo plugins_url('img/icons-2x.png', __FILE__); ?>) 0px -26px no-repeat !important;
	            background-size: 28px 54px !important;
	        }
			#menu-posts-work:hover .wp-menu-image, #menu-posts-work.wp-has-current-submenu .wp-menu-image {
	            background-position: 0px 2px !important;
	        }
			#icon-edit.icon32-posts-work {
				background: url(<?php echo plugins_url('img/bigicon-2x.png', __FILE__); ?>) no-repeat;
	            background-size: 36px 32px;
			}
		}
    </style>
<?php }


// ADD CUSTOM META FIELDS TO POST TYPE
add_action('add_meta_boxes', 'portfoolio_add_custom_fields');
function portfoolio_add_custom_fields() {
	add_meta_box('media_box', 'Image/Video/Media', 'portfoolio_media_box_contents', 'work', 'normal', 'high');
}
function portfoolio_media_box_contents() {
	$type = '';
	global $post;
	$media_items = get_post_meta($post->ID, 'media_items', true); ?>
	
	<table class="widefat portfoolio_media">
		<tr>
			<th></th>
			<th><?php _e('Thumbnail', 'portfoolio'); ?></th>
			<th><!-- <?php _e('Caption', 'portfoolio'); ?>--></th>
		</tr>
		<tr>
			<td colspan="3"><?php _e('Drag and Drop to Change Order', 'portfoolio'); ?></td>
		</tr>
	
		<tbody class="portfoolio_media_rows">
	<?php
	$items = explode(', ', $media_items);
	
	foreach($items as $item) { ?>
		<tr class="portfoolio_media_row <?php if(!$items[0]) { ?>blank<?php } ?>" data-media-value="<?php echo $item; ?>">
			<td>
				<span class="edit_media_item button"><?php _e('Edit', 'portfoolio'); ?></span>
				<span class="remove_media_item button"><?php _e('Remove', 'portfoolio'); ?></span>
				<?php if(is_numeric($item)) { ?>
				<span class="set_as_thumbnail button"><?php _e('Use as Thumbnail', 'portfoolio'); ?></span>
				<?php } ?>
			</td>
			<td>
				<?php portfoolio_get_item_thumbnail($item); ?>
			</a>
			</td>
			<td></td>
		</tr>
	<?php 
	} // foreach($items as $item) 
	
	if(!$items[0]) {
		echo '<tr class="no_images"><td colspan="3"><h4>There are currently no images or video for this work.</h4></td></tr>';
	}
		
	?>

	</tbody>
	<tr class="add_new_media"><td colspan="3">
		<h4><?php _e('Add New Image/Video', 'portfoolio'); ?></h4>
		<input id="upload_image_button" class="button" type="button" value="<?php _e('Upload Image', 'portfoolio'); ?>" />
		
		<div class="embed_fields">
			<span class="or_spacer"><?php _e('- OR -', 'portfoolio'); ?></span>
			<input type="text" id="video_url" name="video_url" placeholder="http://vimeo.com/12345678">
			<input type="button" id="add_new_video" class="button" value="<?php _e('Embed Media', 'portfoolio'); ?>" />
			<span for="video_url"><?php _e('(works with Vimeo, YouTube, and SoundCloud links)', 'portfoolio'); ?></span>
		</div>
		
<!--
		<br>
		<label for="new_item_caption">Caption</label>
		<input type="text" id="new_item_caption" name="new_item_caption" size="72">
-->
		<input type="hidden" id="media_items" name="media_items" value="<?php echo $media_items ?>" /> 
		
	</td></tr>
</table>
<?php
}

add_action('wp_ajax_portfoolio_set_thumbnail', 'portfoolio_set_thumbnail');
function portfoolio_set_thumbnail() {
	set_post_thumbnail($_POST['post_id'], $_POST['image_id']);
	echo wp_get_attachment_image($_POST['image_id'], 'post-thumbnail');
	die();
}



// SAVE META DATA ON WHEN POST IS PUBLISH/UPDATED
add_action('save_post', 'portfoolio_save_postdata');
function portfoolio_save_postdata($post_id) {
	if (!current_user_can('edit_page', $post_id)) return;
	
	if($_POST['media_items']) {
		//sanitize user input
		$media_items = sanitize_text_field($_POST['media_items']);
		update_post_meta($post_id, 'media_items', $media_items);
	}
}




// ADD CUSTOM COLUMNS TO 'WORKS' ADMIN PAGE
add_filter('manage_work_posts_columns', 'portfoolio_works_columns');
function portfoolio_works_columns($defaults){
	$first_column = array_slice($defaults, 0, 1);
	$remaining_columns = array_slice($defaults, 1);
	$new_defaults = $first_column + array('thumbnail_column' => _e('Thumbnail', 'portfoolio')) + $remaining_columns;
	return $new_defaults;
}
add_action('manage_work_posts_custom_column', 'portfoolio_works_column_data', 10, 2);
function portfoolio_works_column_data($column_name, $id) {
	if( $column_name == 'thumbnail_column' ) {
		?><a href="<?php echo get_edit_post_link($id); ?>"><?php portfoolio_thumbnail($id); ?></a><?php
	}
}





// WHITELIST SETTINGS
add_action('admin_init', 'portfoolio_settings_init' );
function portfoolio_settings_init() {
	register_setting( 'portfoolio_settings', 'portfoolio_settings_options', 'portfoolio_sanitize_settings' );
}

// ADD SETTINGS PAGE
add_action('admin_menu', 'portfoolio_settings_page');
function portfoolio_settings_page() {
	add_options_page('Portfoolio Settings', 'Portfoolio', 'manage_options', 'portfoolio_settings', 'portfoolio_settings_do_page');
}

// Draw the menu page itself
function portfoolio_settings_do_page() {
	?>
	<div class="wrap">
		<div id="icon-options-general" class="icon32"></div>
		<h2><?php _e('Portfoolio Settings', 'portfoolio'); ?></h2>
		<form method="post" action="options.php">
			<?php settings_fields('portfoolio_settings'); ?>
			<?php $options = get_option('portfoolio_settings_options'); ?>
			<table class="form-table">
				
				<tr valign="top">
					<th scope="row"><?php _e('Gallery Item Order', 'portfoolio'); ?><br><span></span></th>
					<td>
						<select name="portfoolio_settings_options[item_order]" value="<?php echo $options['item_order']; ?>">
							<option value="count"><?php _e('List Order', 'portfoolio'); ?></option>
							<option value="date"><?php _e('Date', 'portfoolio'); ?></option>
						</select>
						<p class="description"><?php _e('List Order is the order in which items appear in the Gallery Item list. To change the order, just drag and drop them into the order you would like them to appear.', 'portfoolio'); ?></p>
					</td>
				</tr>
				
				<tr valign="top">
					<th scope="row"><?php _e('Slideshow Size', 'portfoolio'); ?></th>
					<td>
						<?php _e('Width', 'portfoolio'); ?>: <input type="number" step="10" min="0" name="portfoolio_settings_options[slideshow_width]" value="<?php echo $options['slideshow_width']; ?>" class="small-text" placeholder="640" />px<br>
						<?php _e('Height', 'portfoolio'); ?>: <input type="number" step="10" min="0" name="portfoolio_settings_options[slideshow_height]" value="<?php echo $options['slideshow_height']; ?>" class="small-text" placeholder="480"  />px
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><?php _e('Slideshow Navigation', 'portfoolio'); ?></th>
					<td>
						<input type="checkbox" name="portfoolio_settings_options[autoplay_slideshow]" <?php if($options['autoplay_slideshow'] || ! $options['settings_saved']) { ?>checked="checked"<?php } ?> />
						<label><?php _e('Automatically Play Slideshow', 'portfoolio'); ?></label>
						<p class="description"><?php _e('Disabling this will require viewers to manually move between images in a slideshow.', 'portfoolio'); ?></p>
						
						<input type="checkbox" name="portfoolio_settings_options[pause_on_hover]" <?php if($options['pause_on_hover'] || ! $options['settings_saved']) { ?>checked="checked"<?php } ?> />
						<label><?php _e('Pause When Viewer Hovers Over Slideshow', 'portfoolio'); ?></label>
						<p class="description"><?php _e('Slideshow will resume playing if \'Autoplay\' is turned on and the user moves their cursor off the slideshow.', 'portfoolio'); ?></p>
						
						<input type="checkbox" name="portfoolio_settings_options[hide_prev_next_buttons]" value="<?php echo $options['hide_prev_next_buttons']; ?>" />
						<label><?php _e('Hide Previous/Next Image Buttons', 'portfoolio'); ?></label>
						<p class="description"><?php _e('To turn other elements into Previous/next buttons, just add the classes \'portfoolio_prev_image\' or \'portfoolio_next_image\', respectively.', 'portfoolio'); ?></p>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><?php _e('Slideshow Speed', 'portfoolio'); ?><br></th>
					<td>
						<input type="text" name="portfoolio_settings_options[slideshow_speed]" value="<?php echo $options['slideshow_speed']; ?>" class="small-text" placeholder="5"  /> <?php _e('seconds', 'portfoolio'); ?>
						<p class="description"><?php _e('The number of seconds a slide should be shown before transitioning to the next.', 'portfoolio'); ?></p>
					</td>
				</tr>
			</table>
			<p class="submit">
			<input type="hidden" name="portfoolio_settings_options[settings_saved]" value="1">
			<input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
			</p>
		</form>
	</div>
	<?php	
}

// Sanitize and validate input. Accepts an array, return a sanitized array.
function portfoolio_sanitize_settings($input) {
	// Our first value is either 0 or 1
	$input['option1'] = ( $input['option1'] == 1 ? 1 : 0 );
	
	// Say our second option must be safe text with no HTML tags
	$input['sometext'] =  wp_filter_nohtml_kses($input['sometext']);
	
	return $input;
}


/*----------------------------------------------------------------------*

	FRONT-FACING FUNCTIONS

*----------------------------------------------------------------------*/



// DISPLAY LIST OF THUMBNAILS
function portfoolio_gallery($image_size = 'thumbnail', $category = null) {
	if(get_query_var('cat') && $category == null) $category = get_query_var('cat');
	$args=array(
		'post_type' => 'work',
		'orderby' => 'menu_order',
		'order' => 'ASC',
		'cat' => $category
		);
	
	$query = new WP_Query($args);
	
	if ( $query->have_posts() ) : 
		?><ul class='portfoolio_gallery'><?php
		while ( $query->have_posts() ) : $query->the_post(); ?>
			<li class='portfoolio_work'>
			<a href="<?php echo get_permalink($query->post->ID); ?>">
				<?php portfoolio_thumbnail($query->post->ID, $image_size); ?>
			</a>
			</li>
		<?php endwhile;?>
	<?php endif;
}

// DISPLAY 'WORK' THUMBNAIL
function portfoolio_thumbnail($post_id, $image_size = 'thumbnail') {
	$media_items = get_post_meta($post_id, 'media_items', true);
	$items = explode(', ', $media_items); 
	$image_items = array_filter($items, "portfoolio_images_only_array");


	// IF FEATURED THUMBNAIL HAS BEEN SELECTED, USE THAT
	if (has_post_thumbnail()) {
		echo get_the_post_thumbnail($post_id, $image_size);

	// OTHERWISE, LOAD THUMBNAIL OF FIRST IMAGE
	} elseif($image_items[0]) {
		echo wp_get_attachment_image($image_items[0], $image_size);

	// OTHERWISE, LOAD THUMBNAIL OF FIRST VIDEO ITEM
	} else {
		echo "<img src='".portfoolio_get_item_thumbnail_url($items[0])."' alt=''>";
	}
}
function portfoolio_images_only_array($item) {
	if(strpos($item, 'http') === false) return true;
	else return false;
}


// DISPLAY SLIDESHOW OF WORK'S IMAGES
function portfoolio_slideshow($attr = null) {
	$portfoolio_settings = get_option('portfoolio_settings_options');
	if($attr['width']) $width = $attr['width'];
	elseif($portfoolio_settings['slideshow_width']) $width = $portfoolio_settings['slideshow_width'];
	else $width = 640;
	
	if($attr['height']) $height = $attr['height'];
	elseif($portfoolio_settings['slideshow_height']) $height = $portfoolio_settings['slideshow_height'];
	else $height = 480;
	
	if($attr['autoplay']) $autoplay = $attr['autoplay'];
	elseif($portfoolio_settings['autoplay_slideshow'] || !$portfoolio_settings['settings_saved']) $autoplay = true; //if settings_saved == false (meaning the user hasn't used the Portfoolio Settings page), then that means we'll go with the default of turning on autoplay
	else $autoplay = false;
	
	if($attr['pause_on_hover']) $pause_on_hover = $attr['pause_on_hover'];
	elseif($portfoolio_settings['pause_on_hover'] || !$portfoolio_settings['settings_saved']) $pause_on_hover = true; //if settings_saved == false (meaning the user hasn't used the Portfoolio Settings page), then that means we'll go with the default of turning on autoplay
	else $pause_on_hover = false;
	
	if($attr['slideshow_speed']) $autoplay_delay = $attr['slideshow_speed'];
	elseif($portfoolio_settings['slideshow_speed']) $autoplay_delay = $portfoolio_settings['slideshow_speed'] * 1000;
	else $autoplay_delay = 5000;
	
	//$lightbox = $attr['lightbox'];

	global $post;
	$media_items = get_post_meta($post->ID, 'media_items', true);
	$items = explode(', ', $media_items); ?>
	
	<?php if(count($items) > 1) { ?>
	<div class="portfoolio_slideshow <?php if($autoplay) echo 'autoplay'; ?> <?php if($pause_on_hover) echo 'pause_on_hover'; ?>" <?php if($autoplay) echo 'data-delay="'.$autoplay_delay.'"'; ?> style="<?php if($width) echo "width: ".$width."px;"; if($width) echo "height: ".$height."px;"; ?>">
		<?php 
		$slide_num = 1;
		foreach($items as $item) {
			?><div class="portfoolio_slide" data-slide-num="<?php echo $slide_num++; ?>" data-caption=""><?php portfoolio_get_item($item); ?></div><?php
		} ?>
		<div class="portfoolio_slideshowcontrols"><span class="portfoolio_prev_image">Previous</span><span class="portfoolio_next_image">Next</span></div>
	</div>
	<?php } else {
		portfoolio_get_item($items[0]);
	} ?>
    <p class="portfoolio_imagecaption"><?= $imagecaption ?></p>
    <?php if(count($items) > 1) { ?>
		<p class="portfoolio_imagecount" totalimages="<?= $slide_num-1 ?>">1/<?= $slide_num-1 ?></p>
	<?php } ?>
<?php
}





// RETURN THUMBNAIL URL OR A PARTICULAR WORK
function portfoolio_get_item_thumbnail_url($item) {
	if(strpos($item, 'youtube') !== false) {
		parse_str(parse_url($url, PHP_URL_QUERY));
		return "http://img.youtube.com/vi/".$v."/0.jpg";
		
	} elseif (strpos($item, 'vimeo') !== false) {
		$video_id = explode('/', $item);
		$v = $video_id[sizeof($video_id)-1];
		$hash = unserialize(file_get_contents("http://vimeo.com/api/v2/video/".$v.".php"));
		return $hash[0]['thumbnail_medium']; 
		
	} elseif (strpos($item, 'soundcloud') !== false || strpos($item, 'snd.sc') !== false) {
		$hash = json_decode(file_get_contents("http://soundcloud.com/oembed?format=json&url=".$item));
		return $hash->thumbnail_url;  
		
	} else {
		return $thumbnail = wp_get_attachment_thumb_url($item);
	}
}

// DISPLAY THUMBNAIL FOR PARTICULAR WORK
function portfoolio_get_item_thumbnail($item) {
	$thumbnail = portfoolio_get_item_thumbnail_url($item);
	
	if(strpos($thumbnail, 'wp-content') !== false) {
		$type = 'image';
		$fullsize = wp_get_attachment_url($item);
	}
	
	if($type == 'image') echo '<a href="'.$fullsize.'" class="thickbox">';
	else echo '<a href="'. $item .'" target="_blank">';
	echo '<img src="'. $thumbnail .'"></a>';
}

// DISPLAY FULL SIZE IMAGE FOR PARTICULAR WORK
function portfoolio_get_item($item, $attr = null) {
	if($attr['width']) $width = $attr['width'];
	else $width = 640;
	if($attr['height']) $height = $attr['height'];
	else $height = 480;
	
	if(strpos($item, 'youtube') !== false) {
		parse_str(parse_url($item, PHP_URL_QUERY));
		?><iframe class="youtube" <?php if($width) echo "width='$width'"; ?> <?php if($height) echo "height='$height'"; ?> src="http://www.youtube.com/embed/<?php echo $v ?>?rel=0&enablejsapi=1" frameborder="0" allowfullscreen></iframe><?php
	} elseif (strpos($item, 'vimeo') !== false) {
		$video_id = explode('/', $item);
		$v = $video_id[sizeof($video_id)-1];
		?><iframe class="vimeo" src="http://player.vimeo.com/video/<?php echo $v ?>" <?php if($width) echo "width='$width'"; ?> <?php if($height) echo "height='$height'"; ?> frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe><?php
	} elseif (strpos($item, 'soundcloud') !== false || strpos($item, 'snd.sc') !== false) {
		
		$hash = json_decode(file_get_contents("http://soundcloud.com/oembed?format=json&url=".$item));
		echo $hash->html;
	} else {
		echo wp_get_attachment_image($item, array($width, $height));
	}
}
	



?>