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

// Holds our user awards business logic
$WPAward = new WPAward\BusinessLogic\Core($wpdb);

// Holds our plugin logic, which includes Post and Meta type additions
$WPAwardPlugin = new WPAward\PluginLogic\Core('wordpress_awards');

// Activation, Deactivation, Uninstall
register_activation_hook( __FILE__, [ 'WPAward\PluginLogic\RegistrationHooks', 'Activate' ] );
register_deactivation_hook( __FILE__, [ 'WPAward\PluginLogic\RegistrationHooks', 'Deactivate' ] );
register_uninstall_hook( __FILE__, [ 'WPAward\PluginLogic\RegistrationHooks', 'Uninstall' ] );

// Start Plugin Initialization Scripts
add_action('plugins_loaded', 'wp_awards_plugin_init');

// Initialization function.
function wp_awards_plugin_init() {

}
?>