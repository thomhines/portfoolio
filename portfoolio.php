<?php
/*
Plugin Name: Portfoolio
Plugin URI: http://thomhines.com/portfoolio/
Description: Set up a gallery or portfolio using easier than ever
Version: 0.1
Author: Thom Hines
Author URI: http://thomhines.com/
*/




$version = '0.1';




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

add_action( 'wp_enqueue_scripts', 'portfoolio_frontend_scripts' );
function portfoolio_frontend_scripts() {
	global $post;
	if ($_GET['post'] == 'work' || $post->post_type == 'work') {
		wp_register_script('portfoolio-slideshow-js', plugins_url('portfoolio-slideshow.js', __FILE__), array('jquery'), $version);
		wp_enqueue_script('portfoolio-slideshow-js');
		wp_enqueue_style('portfoolio-slideshow-styles', plugins_url('portfoolio-slideshow.css', __FILE__), array(), $version);
	}
}


// CUSTOM POST TYPE
add_action('init', 'register_cpt_work');
function register_cpt_work() {

	$labels = array(
		'name' => _x('Works', 'work'),
		'singular_name' => _x('Work', 'work'),
		'add_new' => _x('Add New', 'work'),
		'add_new_item' => _x('Add New Work', 'work'),
		'edit_item' => _x('Edit Work', 'work'),
		'new_item' => _x('New Work', 'work'),
		'view_item' => _x('View Work', 'work'),
		'search_items' => _x('Search Works', 'work'),
		'not_found' => _x('No works found', 'work'),
		'not_found_in_trash' => _x('No works found in Trash', 'work'),
		'parent_item_colon' => _x('Parent Work:', 'work'),
		'menu_name' => _x('Artwork', 'work'),
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

add_action( 'admin_head', 'wpt_portfolio_icons' );
function wpt_portfolio_icons() {
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

/*
function cd_meta_box_add()
{
	add_meta_box('my-meta-box-id', 'My First Meta Box', 'cd_meta_box_cb', 'work', 'normal', 'high');
}
add_action('add_meta_boxes', 'cd_meta_box_add');
*/


// CUSTOM META DATA
add_action('add_meta_boxes', 'portfoolio_add_custom_fields');
function portfoolio_add_custom_fields() {
	add_meta_box('media_box', 'Images/Video', 'media_box_contents', 'work', 'normal', 'high');
}

function media_box_contents() {
	$type = '';
	global $post;
	$media_items = get_post_meta($post->ID, 'media_items', true); ?>
	
	<table class="widefat portfoolio_media">
		<tr>
			<th></th>
			<th>Thumbnail</th>
			<th><!-- Caption --></th>
		</tr>
	
		<tbody class="media_rows">
	<?php
	$items = explode(', ', $media_items);
	
	foreach($items as $item) {
/*
		$thumbnail = '';
		$embed = '';
		if(strpos($item, 'youtube') !== false) {
			$type = 'youtube';
			$thumbnail = getYouTubeThumbnailUrl(item);
			
		} elseif (strpos($item, 'vimeo') !== false) {
			$type = 'vimeo';
			$thumbnail = getVimeoThumbnailUrl($item); 
			
		} elseif (strpos($item, 'soundcloud') !== false || strpos($item, 'snd.sc') !== false) {
			$type = 'soundcloud';
			$thumbnail = getSoundCloudThumbnailUrl($item); 
			
		} else {
			$type = 'image';
			$thumbnail = wp_get_attachment_thumb_url($item);
			$fullsize = wp_get_attachment_url($item);
		}
*/
		?>
			<tr class="media_row <?php if(!$items[0]) { ?>blank<?php } ?>" data-media-value="<?php echo $item; ?>">
				<td>
					<span class="remove_media_item button">Remove</span>
				</td>
				<td>
					<?php getItemThumbnail($item); ?>
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
			<h4>Add New Image/Video</h4>
			<input id="upload_image_button" class="button" type="button" value="Upload Image" />
			
			<div class="embed_fields">
				<span class="or_spacer">- OR -</span>
				<input type="text" id="video_url" name="video_url" placeholder="http://vimeo.com/12345678">
				<input type="button" id="add_new_video" class="button" value="Embed Media" />
				<span for="video_url">(works with Vimeo, YouTube, and SoundCloud links)</span>
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



add_filter('manage_work_posts_columns', 'works_columns');
function works_columns($defaults){
	$first_column = array_slice($defaults, 0, 1);
	$remaining_columns = array_slice($defaults, 1);
	$new_defaults = $first_column + array('thumbnail_column' => __('Thumbnail')) + $remaining_columns;
	return $new_defaults;
}

add_action('manage_work_posts_custom_column', 'works_custom_column_data', 10, 2);
function works_custom_column_data($column_name, $id) {
	if( $column_name == 'thumbnail_column' ) {
		//$featured = get_post_meta($id, 'featured');
		portfoolioThumbnail($id);
		//if($featured[0]) echo "<span style='font-size: 1.3em; font-weight: bold'>&#10003;</span>";
	}
}


function portfoolio_save_postdata($post_id) {
	// First we need to check if the current user is authorised to do this action. 
	if (!current_user_can('edit_page', $post_id)) return;
	
	//sanitize user input
	if($_POST['media_items']) {
		$media_items = sanitize_text_field($_POST['media_items']);
		update_post_meta($post_id, 'media_items', $media_items);
	}
}
add_action('save_post', 'portfoolio_save_postdata');





// add featured/invisible columns to Posts Admin page
/*
add_filter('manage_posts_columns', 'featured_column');
function featured_column($defaults){
	$defaults['featured_column'] = __('Featured');
	$defaults['invisible_column'] = __('Invisible');
	return $defaults;
	echo "adding columns";
}

add_action('manage_posts_custom_column', 'custom_column_data', 10, 2);
function custom_column_data($column_name, $id) {
	if($column_name == 'featured_column') {
		$featured = get_post_meta($id, 'featured');
		if($featured[0]) echo "<span style='font-size: 1.3em; font-weight: bold'>&#10003;</span>";
	}
	if($column_name == 'invisible_column') {
		$featured = get_post_meta($id, 'invisible');
		if($featured[0]) echo "<span style='font-size: 1.3em; font-weight: bold'>&#10003;</span>";
	}
}
*/


function displayGallery($image_size = 'thumbnail', $category = null) {
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
				<?php portfoolioThumbnail($query->post->ID, $image_size); ?>
			</a>
			</li>
		<?php endwhile;?>
	<?php endif;
}


function portfoolioThumbnail($post_id, $image_size = 'thumbnail') {
	$media_items = get_post_meta($post_id, 'media_items', true);
	$items = explode(', ', $media_items); 
	$image_items = array_filter($items, "imagesOnlyArray");


	// IF FEATURED THUMBNAIL HAS BEEN SELECTED, USE THAT
	if (has_post_thumbnail()) {
		echo get_the_post_thumbnail($post_id, $image_size);

	// OTHERWISE, LOAD THUMBNAIL OF FIRST IMAGE
	} elseif($image_items[0]) {
		echo wp_get_attachment_image($image_items[0], $image_size);

	// OTHERWISE, LOAD THUMBNAIL OF FIRST VIDEO ITEM
	} else {
		echo "<img src='".getItemThumbnailUrl($items[0])."' alt=''>";
/*
		if(strpos($items[0], 'youtube') !== false) {
			parse_str(parse_url($items[0], PHP_URL_QUERY));
			echo "<img src='http://img.youtube.com/vi/".$v."/1.jpg' alt=''>";
			
		} elseif (strpos($items[0], 'vimeo') !== false) {
			$video_id = explode('/', $items[0]);
			$v = $video_id[sizeof($video_id)-1];
			$hash = unserialize(file_get_contents("http://vimeo.com/api/v2/video/".$v.".php"));
			echo $thumbnail = "<img src='".$hash[0]['thumbnail_medium']."' alt=''>"; 
		}
*/
	}
}
function imagesOnlyArray($item) {
	if(strpos($item, 'http') === false) return true;
	else return false;
}


function portfoolioSlideshow($attr = null) {

	if($attr['width']) $width = $attr['width'];
	else $width = 640;
	if($attr['height']) $height = $attr['height'];
	else $height = 480;
	$autoplay = $attr['autoplay'];
	if($attr['delay']) $autoplay_delay = $attr['delay'];
	else $autoplay_delay = 5000;
	//$lightbox = $attr['lightbox'];

	global $post;
	$media_items = get_post_meta($post->ID, 'media_items', true);
	$items = explode(', ', $media_items); ?>
	
	<?php if(count($items) > 1) { ?>
	<div class="portfoolio_slideshow <?php if($autoplay) echo 'autoplay'; ?>" <?php if($autoplay) echo 'data-delay="'.$autoplay_delay.'"'; ?> style="<?php if($width) echo "width: ".$width."px;"; if($width) echo "height: ".$height."px;"; ?>">
		<?php 
		$slide_num = 1;
		foreach($items as $item) {
			?><div class="portfoolio_slide" data-slide-num="<?php echo $slide_num++; ?>"><?php
			getItem($item);
			
			
/*
			$thumbnail = '';
			$embed = '';
			if(strpos($item, 'youtube') !== false) {
				$type = 'youtube';
				parse_str(parse_url($item, PHP_URL_QUERY));
				$thumbnail = "<img src='http://img.youtube.com/vi/".$v."/0.jpg'>";
				//$embed = "http://www.youtube.com/embed/".$v."?autoplay=1";
				?><iframe class="youtube" <?php if($width) echo "width='$width'"; ?> <?php if($height) echo "height='$height'"; ?> src="http://www.youtube.com/embed/<?php echo $v ?>?rel=0&enablejsapi=1" frameborder="0" allowfullscreen></iframe><?php
			} elseif (strpos($item, 'vimeo') !== false) {
				$type = 'vimeo';
				$video_id = explode('/', $item);
				$v = $video_id[sizeof($video_id)-1];
				$hash = unserialize(file_get_contents("http://vimeo.com/api/v2/video/".$v.".php"));
				$thumbnail = "<img src='".$hash[0]['thumbnail_large']."'>";  
				//$embed = 'http://player.vimeo.com/video/?title=0&amp;byline=0&amp;portrait=0';
				?><iframe class="vimeo" src="http://player.vimeo.com/video/<?php echo $v ?>" <?php if($width) echo "width='$width'"; ?> <?php if($height) echo "height='$height'"; ?> frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe><?php
			} else {
				$type = 'image';
				//$thumbnail = 
				echo $thumbnail = wp_get_attachment_image($item, array($width, $height));
				//print_r($thumbnail);
				//$thumbnail = $thumbnail[0];
				$fullsize = wp_get_attachment_url($item);
			}
*/
			?></div><?php
		} // foreach($items as $item) ?>
		<div class="portfoolio_slideshowcontrols"><span class="prev_image">Previous</span><span class="next_image">Next</span></div>
	</div>
	<?php } else {
		getItem($items[0]);
	} ?>
    <p class="imagecaption"><?= $imagecaption ?></p>
    <?php if(count($items) > 1) { ?>
		<p class="imagecount" totalimages="<?= $slide_num-1 ?>">1/<?= $slide_num-1 ?></p>
	<?php } ?>
<?php
}






function getItemThumbnailUrl($item) {
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

function getItemThumbnail($item) {
/*
	if(strpos($item, 'youtube') !== false) {
		parse_str(parse_url($url, PHP_URL_QUERY));
		$thumbnail = "http://img.youtube.com/vi/".$v."/0.jpg";
		
	} elseif (strpos($item, 'vimeo') !== false) {
		$video_id = explode('/', $item);
		$v = $video_id[sizeof($video_id)-1];
		$hash = unserialize(file_get_contents("http://vimeo.com/api/v2/video/".$v.".php"));
		$thumbnail = $hash[0]['thumbnail_medium']; 
		
	} elseif (strpos($item, 'soundcloud') !== false || strpos($item, 'snd.sc') !== false) {
		$hash = json_decode(file_get_contents("http://soundcloud.com/oembed?format=json&url=".$item));
		$thumbnail = $hash->thumbnail_url;  
		
	} else {
*/
	$thumbnail = getItemThumbnailUrl($item);
	
	if(strpos($thumbnail, 'wp-content') !== false) {
		$type = 'image';
		$fullsize = wp_get_attachment_url($item);
	}
	
	if($type == 'image') echo '<a href="'.$fullsize.'" class="thickbox">';
	else echo '<a href="'. $item .'" target="_blank">';
	echo '<img src="'. $thumbnail .'"></a>';
}

function getItem($item, $attr = null) {

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

/*

function getYouTubeThumbnailUrl($url) {
	$type = 'youtube';
	parse_str(parse_url($url, PHP_URL_QUERY));
	return "http://img.youtube.com/vi/".$v."/0.jpg";
}

function getYouTubeThumbnail($url) {
	$thumbnail_url = getYouTubeThumbnailUrl($url);
	echo "<img src='".$thumbnail_url."' alt=''>";
}

function getVimeoThumbnailUrl($url) {
	$video_id = explode('/', $url);
	$v = $video_id[sizeof($video_id)-1];
	$hash = unserialize(file_get_contents("http://vimeo.com/api/v2/video/".$v.".php"));
	return $hash[0]['thumbnail_medium']; 
}

function getVimeoThumbnail($url) {
	$thumbnail_url = getVimeoThumbnailUrl($url)
	echo $thumbnail = "<img src='".$thumbnail_url."' alt=''>"; 
}

function getSoundCloudThumbnailUrl($url) {	
	$hash = json_decode(file_get_contents("http://soundcloud.com/oembed?format=json&url=".$url));
	return $hash->thumbnail_url;  
}

function getSoundCloudThumbnail($url) {	
	$thumbnail_url = getSoundCloudThumbnailUrl($url)
	echo $thumbnail = "<img src='".$thumbnail_url."' alt=''>"; 
}
*/





?>