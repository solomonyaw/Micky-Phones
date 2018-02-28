<?php
/*
Plugin Name: Facebook Post Embed
Plugin URI: http://wp-plugins.in/facebook-post-embed
Description: One shortcode to embedding facebook posts easily, responsive and custom margin bottom.
Version: 1.0.0
Author: Alobaidi
Author URI: http://wp-plugins.in
License: GPLv2 or later
*/

/*  Copyright 2015 Alobaidi (email: wp-plugins@outlook.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );


function alobaidi_facebook_post_embed_plugin_row_meta( $links, $file ) {

	if ( strpos( $file, 'facebook-post-embed.php' ) !== false ) {
		
		$new_links = array(
						'<a href="http://wp-plugins.in/facebook-post-embed" target="_blank">Explanation of Use</a>',
						'<a href="https://profiles.wordpress.org/alobaidi#content-plugins" target="_blank">More Plugins</a>',
						'<a href="http://j.mp/ET_WPTime_ref_pl" target="_blank">Elegant Themes</a>'
					);
		
		$links = array_merge( $links, $new_links );
		
	}
	
	return $links;
	
}
add_filter( 'plugin_row_meta', 'alobaidi_facebook_post_embed_plugin_row_meta', 10, 2 );


// Add responsive style to facebook posts
function alobaidi_facebook_post_embed_style(){
	?>
    	<style type="text/css">
			div.fb-post{
				width:100% !important;
				max-width:100% !important;
				min-width:100% !important;
				display:block !important;
			}

			div.fb-post *{
				width:100% !important;
				max-width:100% !important;
				min-width:100% !important;
				display:block !important;
			}
		</style>
    <?php
}
add_action('wp_head', 'alobaidi_facebook_post_embed_style');


// Add javascript
function alobaidi_facebook_post_embed_script(){
	?>
		<div id="fb-root"></div>
		<script type="text/javascript">
			(function(d, s, id) {
  				var js, fjs = d.getElementsByTagName(s)[0];
  				if (d.getElementById(id)) return;
  					js = d.createElement(s); js.id = id;
  					js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.2";
  					fjs.parentNode.insertBefore(js, fjs);
				}
			(document, 'script', 'facebook-jssdk'));
    	</script>
    <?php
}
add_action('wp_footer', 'alobaidi_facebook_post_embed_script');


// Add [fb_pe url="" bottom=""] shortcode
function alobaidi_facebook_post_embed_shortcode( $atts, $content = null ){

	extract(
		shortcode_atts(
			array(
				"url"		=>	'',
				"bottom" 	=>	'30'
			),$atts
		)
	);

	if( empty($url) ){
		return '<p>Please enter facebook post url.</p>';
		return false;
	}

	if( empty($bottom) or $bottom == '0' ){
		$style = ' style="margin-bottom:0px;"';
	}

	else{
		$style = ' style="margin-bottom:'.$bottom.'px;"';
	}
	
	return '<div class="fb-post wptime-fb-post-embed" data-href="'.$url.'"'.$style.'></div>';
	
}
add_shortcode("fb_pe", "alobaidi_facebook_post_embed_shortcode");


// Add facebook button to wp editor
function alobaidi_fb_pe_tinymce_button($buttons) {
	array_push($buttons, 'facebook_post_embed');
	return $buttons;
}
add_filter('mce_buttons', 'alobaidi_fb_pe_tinymce_button');


// Register js for facebook button
function alobaidi_fb_pe_register_tinymce_js($plugin_array) {
	$plugin_array['facebook_post_embed'] = plugins_url( '/js/fb_pe_tinymce_button.js', __FILE__);
	return $plugin_array;
}
add_filter('mce_external_plugins', 'alobaidi_fb_pe_register_tinymce_js');


// Add css icon for facebook button
function alobaidi_fb_pe_button_icon(){
	?>
		<style type="text/css">
			.mce-i-facebook-post-embed-icon:before{
				font-family: 'dashicons' !important;
				content: '\f304' !important;
				font-size: 24px !important;
			}
		</style>
	<?php
}
add_action('admin_head','alobaidi_fb_pe_button_icon');

?>