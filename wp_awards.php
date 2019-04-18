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

// Grab our database instance
global $wpdb;

// Global variable acessible throughout WP in order to apply awards to users.
global $WPAward;

// Activation, Deactivation, Uninstall
register_activation_hook( __FILE__, [ 'WPAward\PluginLogic\RegistrationHooks', 'Activate' ] );
register_deactivation_hook( __FILE__, [ 'WPAward\PluginLogic\RegistrationHooks', 'Deactivate' ] );
register_uninstall_hook( __FILE__, [ 'WPAward\PluginLogic\RegistrationHooks', 'Uninstall' ] );

// Enqueuing our plugin assets such as styles and scripts (if needed)
add_action( 'admin_enqueue_scripts', 'enqueue_plugin_assets' );

// Plugin post type name
$custom_post_type_name = 'wordpress_awards';

function enqueue_plugin_assets( $hook ) {
	global $post;
	global $custom_post_type_name;

	if ( $hook === "post.php" && get_post_type( $post ) === $custom_post_type_name )
	{
		wp_enqueue_style( 'general_award_styles', plugins_url( 'wp_awards_styles.css', __FILE__ ) );

	}
}

// Holds our user awards business logic
$WPAward = new WPAward\BusinessLogic\Core($wpdb);

// Holds our plugin logic, which includes Post and Meta type additions
$WPAwardPlugin = new WPAward\PluginLogic\Core( $custom_post_type_name );

// At this point we should be going through the entire amount of awards that are defined.
// We should then apply listeners for them to be applied for the current user instance.

?>