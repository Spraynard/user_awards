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
	* User Meta Applications
Author: Kellan Martin
Version: 0.0.1
Author URI: http://kellanmartin.com
*/

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

// Include our scripts
require_once __DIR__ . "/vendor/autoload.php";

//-- Activation, Deactivation, Uninstall
register_activation_hook( __FILE__, [ 'WPAward\WPAwardPluginHooks', 'Activate' ] );
register_deactivation_hook( __FILE__, [ 'WPAward\WPAwardPluginHooks', 'Deactivate' ] );
register_uninstall_hook( __FILE__, [ 'WPAward\WPAwardPluginHooks', 'Uninstall' ] );

// Initialize WPAward Instance
global $wpdb;
$WPAward = new WPAward\WPAward($wpdb);

//-- User deletion should trigger a mass delete of all of the awards for the user that we're deleting.
add_action('delete_user', [ $WPAward, 'RemoveAward'] );

//-- Start Plugin Initialization Scripts
add_action('plugins_loaded', 'wp_awards_plugin_init');
add_action('wp_loaded', 'apply_award_type_listeners');


function wp_awards_plugin_init() {
	add_action('init', 'wp_awards_post_type');

	// Apply listeners to specific entities based on our award
	apply_award_type_listeners();
}

function apply_award_type_listeners() {
	// $post_type_args = array(
	// 	'posts_per_page' => '-1',
	// 	'post_type' => 'wap_awards'
	// );

	// $wap_awards_posts = get_posts( $post_type_args );

	// echo "These are our posts\n";
	// print_r( $wap_awards_posts );

}

//-- New Post Type Created: wp_awards_plugin
function wp_awards_post_type() {

	$args = [
		'labels' => [ 'name' => 'Awards', 'singular_name' => 'Award' ],
		'show_ui' => true
	];

	register_post_type('wap_award', $args);
}
?>