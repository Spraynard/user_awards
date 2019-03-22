<?php
/**
 * @package Awards
 * @version 0.0.2
 */
/*
Plugin Name: Awards
Description: Adds the ability to award your registered users!
Let them know you appreciate them by awarding based on site actions,
such as:
	* Sign Up
	*
Author: Kellan Martin
Version: 0.0.1
Author URI: http://kellanmartin.com
*/

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

// // Autoload our dependencies
// foreach( glob( plugin_dir_path( __FILE__ ) . 'src/*.php') as $file ) {
// 	include_once $file;
// }

add_action('plugins_loaded', 'wp_awards_plugin_init');

// Main bootstrapping function for the plugin.
// We will initialize the framework that is needed for the awards plugin.
function wp_awards_plugin_init() {
	add_action('init', 'wp_awards_post_type');
}

/**
 * Initialize plugin specific post type
 */
function wp_awards_post_type() {

	$args = [
		'labels' => [ 'name' => 'Awards', 'singular_name' => 'Award' ],
		'show_ui' => true
	];

	register_post_type('wp_awards_plugin', $args);
}


?>