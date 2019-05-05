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

/**
 * DEFINES
 */

if ( ! defined('WP_AWARDS_VERSION_KEY') )
{
	define('WP_AWARDS_VERSION_KEY', 'wp_awards_version');
}

if ( ! defined('WP_AWARDS_VERSION') )
{
	define('WP_AWARDS_VERSION', '0.1');
}

if ( ! defined('WP_AWARDS_POST_TYPE') )
{
	define('WP_AWARDS_POST_TYPE', 'wp_awards_cpt');
}

if ( ! defined('WP_AWARDS_GRAMMAR_META_TYPE') )
{
	define('WP_AWARDS_GRAMMAR_META_TYPE', 'WPAward_Grammar');
}

/* End Defines */

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

// Wait until the plugins loaded action in order to have wp_get_current_user() function
add_action( 'plugins_loaded', 'apply_wp_award_listeners' );

// Holds our user awards business logic
$WPAward = new WPAward\BusinessLogic\Core($wpdb);

// Plugin meta box handling
$WPAwardMetaBoxes = new WPAward\PluginLogic\PostType\MetaBoxes( WP_AWARDS_POST_TYPE, $WPAward );

// Holds our plugin logic, which includes Post and Meta type additions
$WPAwardPlugin = new WPAward\PluginLogic\Core( $WPAward, $WPAwardMetaBoxes );

/**
 * Loop through all the defined awards.
 * This requires us to have a current_user which, if available, will
 * have an award listener applied to that user. This in turn allows us to automatically
 * assign an award to a user based on the actions that they are taking.
 */

function apply_wp_award_listeners() {
	$current_user = wp_get_current_user();

	if ( $current_user->ID > 0 )
	{
		$awards = get_posts([ 'post_type' => WP_AWARDS_POST_TYPE ]);
		$grammar = new WPAward\Grammar\Core();

		foreach( $awards as $award )
		{
			$award_grammar = get_post_meta( $award->ID, 'WPAward_Grammar', true );

			// The parse function can throw errors, so wrap it in a try block
			try
			{
				$grammar->parse( $award_grammar );
			}
			catch( Exception $e )
			{
				continue;
			}

			// Apply our listener. Set it and forget it. Include a parsed grammar and inject the WPAward dependency
			$listener = new WPAward\Listener\Core( $award->ID, $grammar, $WPAward );
		}
	}
}

/**
 * Function used to enqueue any required JS or CSS assets
 */
function enqueue_plugin_assets( $hook ) {
	// Post Specific page - New Post, Edit Post
	if ( $hook === "post.php" || $hook === "edit.php" || $hook === "post-new.php" )
	{
		wp_enqueue_style( 'general_award_styles', plugins_url( 'wp_awards_styles.css', __FILE__ ), [], false, false );
		wp_enqueue_script( 'WPAwards_Edit_Bulk_Scripts', plugins_url( 'scripts/wp_award_page_edit_scripts.js', __FILE__ ), array('common') );
	}

	if ( $hook === "wp_awards_cpt_page_user-awards-admin-view" )
	{
		wp_enqueue_script( 'WPAwards_Admin_View_Scripts', plugins_url( 'scripts/wp_award_admin_view_scripts.js', __FILE__ ), array('common') );
	}
}
?>