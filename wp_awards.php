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

/* End Defines */

// Include our scripts
require_once __DIR__ . "/vendor/autoload.php";

use WPAward\PluginLogic\PostType\MetaBox;

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

// Plugin post type name
$post_type = 'wordpress_awards';

// Holds our user awards business logic
$WPAward = new WPAward\BusinessLogic\Core($wpdb);


///////////////////////
// META BOX CREATION //
///////////////////////
$Post_Metaboxes = [];

// User Select Metabox
$UserSelectMetaName = "WPAward_User_Apply";

$UserSelectMetaboxData = [
	'id' => $post_type . '_apply_award',
	'title' => 'Apply/Give Award To User',
	'page' => $post_type,
	'context' => 'side',
	'priority' => 'default',
	'callback_args' => null
];

$UserSelectInput = new WPAward\Actions\AssignAwardAction(
	$UserSelectMetaName,
	'WPAward_User_Give',
	$WPAward
);

$UserSelectOptions = new WPAward\Actions\Output\HTMLOptionOutput(
	get_users(),
	"ID",
	[
		'format' => "%s - %s",
		'value' => ['user_nicename', 'user_email']
	]
);

$UserSelectOutput = new WPAward\Actions\Output\SelectOutput(
	$UserSelectMetaName,
	"Select a user from this dropdown and submit in order to apply this award to the user.",
	$UserSelectOptions
);

$UserOptionsMetabox = new MetaBox(
	$UserSelectMetaboxData,
	$UserSelectInput,
	$UserSelectOutput
);

$Post_Metaboxes[] = $UserOptionsMetabox;

// Auto Give Award Meta Box

$AutoGiveMetaName = 'WPAward_Auto_Give';

$AutoGiveMetaboxData = [
	'id' => $post_type . '_auto_give',
	'title' => 'Auto Give Award',
	'page' => $post_type,
	'context' => 'side',
	'priority' => 'default',
	'callback_args' => null
];

$AutoGiveCheckboxInput = new WPAward\Actions\UpdatePostMetaAction($AutoGiveMetaName);

$AutoGiveCheckboxOutput = new WPAward\Actions\Output\CheckboxInputOutput(
	$AutoGiveMetaName,
	"on",
	"Checking this box will automatically give award to user when they trigger the award"
);

$AutoGiveCheckboxMetabox = new MetaBox(
	$AutoGiveMetaboxData,
	$AutoGiveCheckboxInput,
	$AutoGiveCheckboxOutput
);

$Post_Metaboxes[] = $AutoGiveCheckboxMetabox;


// Grammar Meta Box

$GrammarMetaName = 'WPAward_Grammar';

$GrammarMetaboxData = [
	'id' => $post_type . '_grammar',
	'title' => 'Award Trigger',
	'page' => $post_type,
	'context' => 'advanced',
	'priority' => 'default',
	'callback_args' => null
];

$GrammarMetaInput = new WPAward\Actions\UpdatePostMetaAction($GrammarMetaName);

$GrammarMetaOutput = new WPAward\Actions\Output\DefaultInputOutput(
	$GrammarMetaName,
	"Write out a grammar string to act as a trigger for a user obtaining this award"
);

$GrammarMetaMetabox = new MetaBox(
	$GrammarMetaboxData,
	$GrammarMetaInput,
	$GrammarMetaOutput
);

$Post_Metaboxes = $GrammarMetaMetabox;

// Plugin meta box handling
$WPAwardMetaBoxes = new WPAward\PluginLogic\PostType\MetaBoxes( $post_type, $WPAward );

//////////////////////////
// End Metabox Creation //
//////////////////////////

// Plugin Logic Core Instantiation
$WPAwardPlugin = new WPAward\PluginLogic\Core( $post_type, $WPAward, $WPAwardMetaBoxes );

/**
 * Loop through all the defined awards.
 * This requires us to have a current_user which, if available, will
 * have an award listener applied to that user. This in turn allows us to automatically
 * assign an award to a user based on the actions that they are taking.
 */

function apply_wp_award_listeners() {

	global $post_type;
	global $WPAward;

	$current_user = wp_get_current_user();

	if ( $current_user->ID > 0 )
	{
		$awards = get_posts([ 'post_type' => $post_type ]);
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
	global $post;
	global $post_type;

	// Post Specific page - New Post, Edit Post
	if ( $hook === "post.php" || $hook === "edit.php" || $hook === "post-new.php" )
	{
		wp_enqueue_style( 'general_award_styles', plugins_url( 'wp_awards_styles.css', __FILE__ ), [], false, false );
		wp_enqueue_script( 'WPAwards_Edit_Bulk_Scripts', plugins_url( 'scripts/wp_award_page_edit_scripts.js', __FILE__ ), array('common') );
	}
}
?>