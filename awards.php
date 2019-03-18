<?php
/**
 * @package Awards
 * @version 0.0.1
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

// Autoload our dependencies
foreach( glob( plugin_dir_path( __FILE__ ) . 'admin/*.php') as $file ) {
	include_once $file;
}

// Load up our deserializer
include_once( plugin_dir_path( __FILE__ ) . 'shared/Deserializer.php');

/** Action applied when we activate the plugin */
register_activation_hook( __FILE__, 'Awards_DB::awards_activate' );

/** Action applied when we deactivate the plugin */
register_deactivation_hook( __FILE__, 'Awards_DB::awards_deactivate' );

/** Action applied when we uninstall the plugin */
register_uninstall_hook( __FILE__, 'Awards_DB::awards_uninstall' );

// Start up the plugin functionality when all other plugins are loaded
add_action('plugins_loaded', 'awards_plugin_admin_interface' );

function awards_plugin_admin_interface() {
	$Database = new Awards_DB();

	$Serializer = new Serializer($Database);
	$Deserializer = new Deserializer($Database);

	// Routes we will use for the dashboard view based on 'section' param in URL.
	$Dashboard_View_Routes = [
		'award-edit' => 'award_edit.php'
	];

	$Dashboard_View = new Awards_View(
		new Awards_Submenu(
			'award_dashboard.php',
			$Dashboard_View_Routes,
			$Serializer,
			$Deserializer
		)
	);

	$Dashboard_View->init();

	// $Options_View = new Awards_View( new Awards_Submenu('award_options.php') );
	// $Options_View->init();
}
?>