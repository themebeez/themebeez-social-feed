<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://themebeez.com
 * @since      1.0.0
 *
 * @package    Themebeez_Social_Feed
 * @subpackage Themebeez_Social_Feed/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Themebeez_Social_Feed
 * @subpackage Themebeez_Social_Feed/includes
 * @author     themebeez <themebeez@gmail.com>
 */
class Themebeez_Social_Feed_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'themebeez-social-feed',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
