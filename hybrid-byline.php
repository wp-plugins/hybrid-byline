<?php
/**
 * Plugin Name: Hybrid Byline
 * Plugin URI: http://developdaly.com
 * Description: Customize the byline of Hybrid themes
 * Version: 0.2.1
 * Author: Patrick Daly
 * Author URI: http://developdaly.com 
 * 
 * Copyright 2009  Develop Daly  (http://developdaly.com)
 * 
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

/* Set constant path to the my_snippets plugin directory. */
define( HYBRID_BYLINE_DIR, plugin_dir_path( __FILE__ ) );

/* Plugin settings. */
$hbp_settings = get_option( 'hbp_settings_option' );

/* Launch the plugin. */
add_action( 'plugins_loaded', 'hbp_plugin_init' );

/**
 * Initialize the plugin. This function adds needed functions to the required hooks.
 *
 * @since 0.2
 */
function hbp_plugin_init() {

	/* Load global functions for the byline output options. */
	require_once( HYBRID_BYLINE_DIR . '/options.php' );

	/* Meta box on Hybrid Settings page. */
	add_action( 'admin_menu', 'create_hbp_meta_box' );
	add_action( 'hybrid_child_settings', 'hbp_settings' );
	add_action( 'hybrid_update_settings_page', 'save_hbp_meta_box' );
	
	/* Add filter. */
	add_filter('hybrid_byline', 'hbp', 11);
	
}

/**
* Creates the meta box on the Hybrid Settings page
*
* @since 0.2
*/
function create_hbp_meta_box() {
	add_meta_box( "hbp-meta-box", __('Hybrid Byline', 'hybrid-byline'), 'hbp_callback', 'appearance_page_theme-settings', 'normal', 'low' );
}

/**
* Handles the output of the byline
*
* @since 0.1
*/
function hbp( $out ) {
	global $hbp_settings;
		
	$byline = '<p class="byline">';

	if ( is_page() ){
	
		if ( $hbp_settings['byline_page_show_author'] == 1 ) {
			$byline .= hbp_author();
		}
		if ( $hbp_settings['byline_page_show_date'] == 1 ){
			$byline .= hbp_date();
		}
		if ( $hbp_settings['byline_post_show_categories'] == 1 ){
			$byline .= hbp_categories();
		}	
		if ( $hbp_settings['byline_post_show_comments'] == 1 ){
			$byline .= hbp_comments();
		}
	} else {
	
		if ( $hbp_settings['byline_post_show_author'] == 1 ) {
			$byline .= hbp_author();
		}
		if ( $hbp_settings['byline_post_show_date'] == 1 ){
			$byline .= hbp_date();
		}
		if ( $hbp_settings['byline_post_show_categories'] == 1 ){
			$byline .= hbp_categories();
		}	
		if ( $hbp_settings['byline_post_show_comments'] == 1 ){
			$byline .= hbp_comments();
		}
		if ( current_user_can( 'edit_posts' ) )
			$byline .= ' <span class="byline-sep byline-sep-edit separator">|</span> <span class="edit"><a class="post-edit-link" href="' . get_edit_post_link( $post->ID ) . '" title="' . __('Edit post', 'hybrid') . '">' . __('Edit', 'hybrid') . '</a></span>';
	} 
	
	$byline .= '</p>';

	echo apply_filters( 'hbp', $byline );
}

/**
* Returns an array of the theme settings
* Add all options to a single array
* This makes one entry in the database
*
* @since 0.1
*/
function hbp_settings() {
	$settings_arr = array(
		'byline_post_show_author' => '1',
		'byline_post_show_authorlink' => '1',
		'byline_post_authorlink_nofollow' => false,
		'byline_post_show_date' => '1',
		'byline_post_show_categories' => false,
		'byline_post_show_comments' => false,
		'byline_page_show_author' => false,
		'byline_page_show_date' => false,
	);
	
	return apply_filters('hbp_settings_args', $settings_arr);
}

/**
* Creates the content of the meta box
*
* @since 0.1
*/
function hbp_callback() {
	
	$options = get_option( 'hbp_settings_option' );
	$options_keys = array_keys($options);
	foreach($options_keys as $key) :
		$data[$key] = $key;
	endforeach; ?>
	
	<!-- Security! Very important! -->
	<input type="hidden" name="hbp_meta_box_nonce" value="<?php echo wp_create_nonce( basename( __FILE__ ) ); ?>" />
	<table class="form-table">
	
		<tr>
			<th>
				<label for="<?php echo $options['byline_post']; ?>"><?php _e('Post:', 'hybrid'); ?></label>
			</th>
			<td>
				<input type="checkbox" id="<?php echo $data['byline_post_show_author']; ?>" name="<?php echo $data['byline_post_show_author']; ?>" value="1" <?php if ( $options['byline_post_show_author'] == 1 ) { echo " checked='checked'"; } ?>/> <label for="<?php echo $data['byline_post_show_author']; ?>"><?php _e('Show author name', 'hbp'); ?></label><br />
				<input type="checkbox" style="margin:0 0 0 20px;" id="<?php echo $data['byline_post_show_authorlink']; ?>" name="<?php echo $data['byline_post_show_authorlink']; ?>" value="1" <?php if ( $options['byline_post_show_authorlink'] == 1 ) { echo " checked='checked'"; } ?>/> <label for="<?php echo $data['byline_post_show_authorlink']; ?>"><?php _e('Link author names to archives', 'hbp'); ?></label><br />
				<input type="checkbox" style="margin:0 0 0 20px;" id="<?php echo $data['byline_post_authorlink_nofollow']; ?>" name="<?php echo $data['byline_post_authorlink_nofollow']; ?>" value="1" <?php if ( $options['byline_post_authorlink_nofollow'] == 1 ) { echo " checked='checked'"; } ?>/> <label for="<?php echo $data['byline_post_authorlink_nofollow']; ?>"><?php _e('Add <code>nofollow</code> to author links', 'hbp'); ?></label><br />
				<input type="checkbox" id="<?php echo $data['byline_post_show_date']; ?>" name="<?php echo $data['byline_post_show_date']; ?>" value="1" <?php if ( $options['byline_post_show_date'] == 1 ) { echo " checked='checked'"; } ?>/> <label for="<?php echo $data['byline_post_show_date']; ?>"><?php _e('Show published-on date', 'hbp'); ?></label><br />
				<input type="checkbox" id="<?php echo $data['byline_post_show_categories']; ?>" name="<?php echo $data['byline_post_show_categories']; ?>" value="1" <?php if ( $options['byline_post_show_categories'] == 1 ) { echo " checked='checked'"; } ?>/> <label for="<?php echo $data['byline_post_show_categories']; ?>"><?php _e('Show categories', 'hbp'); ?></label><br />
				<input type="checkbox" id="<?php echo $data['byline_post_show_comments']; ?>" name="<?php echo $data['byline_post_show_comments']; ?>" value="1" <?php if ( $options['byline_post_show_comments'] == 1 ) { echo " checked='checked'"; } ?>/> <label for="<?php echo $data['byline_post_show_comments']; ?>"><?php _e('Show number of comments', 'hbp'); ?></label>
			</td>
		</tr>
		<tr>
			<th>
				<label for="<?php echo $data['byline_page']; ?>"><?php _e('Page:', 'hybrid'); ?></label>
			</th>
			<td>
				<input type="checkbox" id="<?php echo $data['byline_page_show_author']; ?>" name="<?php echo $data['byline_page_show_author']; ?>" value="1" <?php if ( $options['byline_page_show_author'] == 1 ) { echo " checked='checked'"; } ?>/> <label for="<?php echo $data['byline_page_show_author']; ?>"><?php _e('Show author name', 'hbp'); ?></label><br />
				<input type="checkbox" id="<?php echo $data['byline_page_show_date']; ?>" name="<?php echo $data['byline_page_show_date']; ?>" value="1" <?php if ( $options['byline_page_show_date'] == 1 ) { echo " checked='checked'"; } ?>/> <label for="<?php echo $data['byline_page_show_date']; ?>"><?php _e('Show published-on date', 'hbp'); ?></label>
			</td>
		</tr>
		
	</table><?php
}

/**
* Saves the settings
*
* @since 0.1
*/
function save_hbp_meta_box() {
	
	/* Verify the nonce, so we know this is secure. */
	if ( !wp_verify_nonce( $_POST['hbp_meta_box_nonce'], basename( __FILE__ ) ) )
		return false;
	
	/* Get the current plugin settings. */
	$options = get_option( 'hbp_settings_option' );
	
	/* Loop through each of the default settings and match them with the posted settings. */
	foreach ( hbp_settings() as $key => $value )
		$options[$key] = $_POST[$key];	
	
	/* Update the plugin settings. */
	$updated = update_option( 'hbp_settings_option', $options );
		
}
?>