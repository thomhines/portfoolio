=== Portfoolio Media Gallery ===
Contributors: Thom Hines
Tags: gallery, portfolio, slideshow, multimedia, media, video, image, custom, vimeo, youtube, soundcloud, upload
Requires at least: 3.5.0
Tested up to: 3.6.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

One of the easiest ways to turn your WordPress site into a portfolio or gallery.




== Description ==

Easily add galleries and slideshows to your site, with drag and drop ordering, automatic embedding of videos from YouTube/Vimeo/etc., and more, all using the familiar WordPress interface.


= Features =

* Clean and simple slideshow built-in works right out of the box, but allows fine-tuned control for power users, such as controlling autoplay, slideshow width and height, slideshow speed, etc.

* Drag-and-drop image ordering lets you quickly set in what order your media appears on your pages.

* YouTube, Vimeo and SoundCloud integration allows users to embed video and audio, all of which will appear right alongsize static images making slideshows truly multi-media.

* Portfoolio can create thumbnails for any image or video added automatically, including links from other sites like YouTube or Vimeo.


= Developer Features =

*Check out the Installation tab to see more detailed documentation on how to implement Portfoolio on your site.*

* Works with standard standard WordPress conventions. Functions use robust arrays as arguments to give developers control over queries and settings, galleries will automatically detect which category the user is viewing, they accept all the same parameters as WP_Query objects, etc.

* Portfoolio "Works" are build as a standard custom post type, which means that they can be sorted and controlled like any other WordPress content, including adding custom meta data, featured thumbnails, categories and tags, archives, and more.

* Images are managed through WordPress' media library, which means that users can easily drag images into the browser to upload, used in multiple pages and posts, and have a unique ID that allows developers access to all sorts of information and control over media items.

* Galleries and slideshows can be added directly to template files via `portfoolio_gallery()` and `portfoolio_slideshow()` functions, as well as directly into post content by using the `[portfoolio_gallery]` and  `[portfoolio_slideshow]` shortcodes.





== Installation ==

1. Upload 'portfoolio' to the '/wp-content/plugins/' directory

2. Activate the plugin through the 'Plugins' menu in WordPress

3. Click on the new "Works" menu item and add a new work.



= Usage = 

**Displaying a Gallery**

To add a gallery to a template file in your theme, just add this code:

`<?php portfoolio_gallery(); ?>`

No arguments are required and the function will run with the following defaults if not set. To change any or all of the default settings, add the settings you want to adjust to an array and pass the array as an argument to the function, like this:

	<?php
	$args = array(
		'thumbnail_size'	=> 'thumbnail',
		'orderby' 			=> 'menu_order',
		'order' 			=> 'ASC'
	);
	portfoolio_gallery(args);
	?>

The `$args` array will also accept any and all values that work with the WP_Query() function such as filtering by catgory ('cat'), setting the number of posts to show ('posts_per_page'), etc.

Alternately, you can insert a shortcode into a post or page to display a gallery anywhere:

`[portfoolio_gallery]`

In order to adjust it's appearance or which Works it will load, simply add any of the attributes listed above or that work as arguments for WP_Query to the shortcode. For instance, to display a gallery that uses 'medium' sized thumbnails and pulls from category 2, you can insert:

`[portfoolio_gallery thumbnail_size='medium' cat='2']`



**Displaying a Item's Slideshow**

`<?php portfoolio_slideshow(); ?>`

Again, the `$args` variable is not required, and the function will run with the following defaults if it's not set. To change any or all of the default settings, use the following code:

	<?php
	$args = array(
		'width'						=> 640,
		'height' 					=> 480,
		'autoplay' 					=> true,
		'slideshow_speed'			=> 5, // number of seconds a slide is shown when autoplay is enabled
		'pause_on_hover' 			=> true,
		'hide_prev_next_buttons'	=> false
	);
	portfoolio_slideshow(args);
	?>

To insert a slideshow into the content of any other post or page, you can use this shortcode:

`[portfoolio_slideshow work='6']`

The only required attribute of the shortcode is the 'work' attribute, which must be given the ID from a specific Work in the gallery. Again, any of the settings mentioned above can be used in a shortcode by listing them in the shortcode. For example:

`[portfoolio_slideshow work='6' width='500' autoplay='false']`





== Frequently Asked Questions ==

= Can I add more than one image or video to a specific Work? =

If your work is part of a series or if you want to show several images in a slideshow, just add more images to the Media List in the admin page for that Work. If you have more than one image, you can drag and drop the images in the Media List into whatever order you want them to appear on the site, and Portfoolio will automatically create a slideshow turn those images into a slideshow.


= How can I choose which thumbnail to use in my site's gallery for a particular work? =

You can either 'Use as Thumbnail' button next to each item in the Media List or the 'Featured Image' box on the right-hand side of the admin page to choose which image you want to be that Work's thumbnail. The image can be any item in your media library, or even something you upload from your computer. If no image is chosen, Portfoolio will use the first image in the Media List, and if there are no images, it will use the first video thumbnail.


= How can I change the order of the images in my gallery? =
The easiest way to change the order is by adjusting the 'Gallery Item Order' option in Settings > Portfoolio. You can choose between three options: List Order, Date, and Random. If you select List Order, your images will appear in the order they are in the Works list (Gallery) page. In general, this will be the same order as Date, but you can alter the order of posts manually or (even easier) by using a plugin like Intuitive Custom Post Order, which allows you to the the order of posts by dragging and dropping. NOTE: I hope to include this functionality in a future version of Portfoolio.


= Can I customize how the gallery or slideshow looks or functions? =
Absolutely. To change which images the gallery shows or how the slideshow functions, you can add a few parameters to either the template or the shortcode to control their base settings (See the Installation page for more information). If you are just wanting to adjust the how they appear on the screen, simply add new CSS rules to your theme that target the elements you want to affect. All of Portfoolio's CSS rules are simple, easy to work with, and standards-based.




== Screenshots ==

1. The Portfoolio settings page.

2. List of works in the gallery. Thumbnail view makes it easy to spot the image your looking for without having to go to the Edit page.

3. An example of what a work looks like with multiple images. Users can also add videos and even audio to their list of media.

4. Galleries can be easily styled and customized.

5. The clean, simple, and intuitive slideshow works automatically and has a number of settings to control how it appears on your site.






== Changelog ==

= 1.0.0 =
* Initial Release!





== Upgrade Notice ==

= 1.0 =
No upgrades so far!




== Other Notes ==


= Translations = 
I'm new to the WordPress plugin community and I don't speak any languages well, so if you have any interest in translating Portfoolio into your language, I would really appreciate your help!