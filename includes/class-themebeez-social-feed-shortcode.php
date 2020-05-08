<?php
/**
 * Themebeez Social Feed for Instagram Shortcode for the plugin.
 *
 * @link       https://themebeez.com
 * @since      1.0.0
 *
 * @package    Themebeez_Social_Feed_Shortcode
 * @subpackage Themebeez_Social_Feed_Shortcode/includes
 */

/**
 * Themebeez Social Feed Shortcode for the plugin.
 *
 * @package    Themebeez_Social_Feed_Shortcode
 * @subpackage Themebeez_Social_Feed_Shortcode/includes
 * @author     themebeez <themebeez@gmail.com>
 */
if( ! class_exists( 'Themebeez_Social_Feed_Shortcode_Shortcode' ) ) {

	class Themebeez_Social_Feed_Shortcode_Shortcode {

		/**
		 * Add shortcode for displaying Instagram Images
		 */
		function __construct() {

			add_shortcode( 'themebeez-social-feed', [ $this, 'shortcode_template' ] );
		}

		/**
		 * Function to define shortcode.
		 *
		 * @param  array $atts Widget attributes.
		 */
		public function shortcode_template( $attributes ) {

		    // Default attributes.

		    $attributes = shortcode_atts( array(
		    	'nums' => 6,
		    	'cols' => 6,
		    	'displaycaption' => false,
		    	'displayprofilelink' => false,
		    	'buttontitle' => '',
		    	'cssclass' => ''
			), $attributes );

			// Sanitization of attributes

		    $attributes['no_of_images'] = ( isset( $attributes['nums'] ) && ( $attributes['nums'] <= 30 ) ) ? absint( $attributes['nums'] ) : 6;

		    $attributes['no_of_cols_per_row'] = ( isset( $attributes['cols'] ) && ( $attributes['cols'] <= 30 ) ) ? absint( $attributes['cols'] ) : 6;

		    $attributes['display_caption'] = wp_validate_boolean( $attributes['displaycaption'] );

		    $attributes['link_profile'] = wp_validate_boolean( $attributes['displayprofilelink'] );

		    $attributes['profile_link_button_title'] = sanitize_text_field( $attributes['buttontitle'] );

		    $attributes['custom_css_class_name'] = sanitize_text_field( $attributes['cssclass'] );

			$feed_helper = new Themebeez_Social_Feed_Helper();;

			return $feed_helper->display_feed( $attributes );
		}
	}
}

new Themebeez_Social_Feed_Shortcode_Shortcode();
