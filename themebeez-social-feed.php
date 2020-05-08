<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://themebeez.com
 * @since             1.0.0
 * @package           Themebeez_Social_Feed
 *
 * @wordpress-plugin
 * Plugin Name:       Themebeez Social Feed
 * Plugin URI:        https://themebeez.com/plugins/themebeez-social-feed
 * Description:       Themebeez Social Feed is a simple WordPress plugin that helps you display your Instagram feeds on your website.
 * Version:           1.0.0
 * Author:            themebeez
 * Author URI:        https://themebeez.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       themebeez-social-feed
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'THEMEBEEZ_SOCIAL_FEED_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-themebeez-social-feed-activator.php
 */
function activate_themebeez_social_feed() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-themebeez-social-feed-activator.php';
	Themebeez_Social_Feed_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-themebeez-social-feed-deactivator.php
 */
function deactivate_themebeez_social_feed() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-themebeez-social-feed-deactivator.php';
	Themebeez_Social_Feed_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_themebeez_social_feed' );
register_deactivation_hook( __FILE__, 'deactivate_themebeez_social_feed' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-themebeez-social-feed.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_themebeez_social_feed() {

	$plugin = new Themebeez_Social_Feed();
	$plugin->run();

}
run_themebeez_social_feed();
