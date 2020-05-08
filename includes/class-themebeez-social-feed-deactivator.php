<?php

/**
 * Fired during plugin deactivation
 *
 * @link       https://themebeez.com
 * @since      1.0.0
 *
 * @package    Themebeez_Social_Feed
 * @subpackage Themebeez_Social_Feed/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    Themebeez_Social_Feed
 * @subpackage Themebeez_Social_Feed/includes
 * @author     themebeez <themebeez@gmail.com>
 */
class Themebeez_Social_Feed_Deactivator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function deactivate() {

		$timestamp = wp_next_scheduled( 'themebeez_social_feed_access_token_refresh' );
		wp_unschedule_event( $timestamp, 'themebeez_social_feed_access_token_refresh' );
	}
}
