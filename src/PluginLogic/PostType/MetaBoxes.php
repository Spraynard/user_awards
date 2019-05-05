<?php
namespace WPAward\PluginLogic\PostType;

class MetaBoxes {
	private $post_type;
	private $WPAward;

	function __construct( $post_type, $WPAward ) {
		$this->post_type = $post_type;
		$this->WPAward = $WPAward;
	}
/**
 * Main function to output our post meta box fields
 */
	public function PostTypeMetaBoxes() {
		$this->_addGrammarMeta(); // Trigger Meta Box
		$this->_applyAwardToUserMeta();
		$this->_addAutoGiveAwardMeta();
	}

	private function _addGrammarMeta() {
		add_meta_box(
			$this->post_type . "_grammar", // CSS ID Attribute
			'Award Trigger', // Title
			[$this, '_grammarMetaHTML'], // Callback
			$this->post_type, // Page
			'advanced', // Context
			'default', // priority
			null // Callback Args
		);
	}

	/**
	 * HTML for grammar meta text input for awards
	 * @param  WP_Award $post - The full post that is being edited currently
	 * @return void
	 */
	function _grammarMetaHTML( $post ) {
		$grammarString = get_post_meta( $post->ID, WP_AWARDS_GRAMMAR_META_TYPE, true);
		$eGrammarString = esc_attr( $grammarString );
		wp_nonce_field( plugin_basename(__FILE__), 'WPAward_Save_Grammar_Meta');
		echo <<<HTML
		<label for="WPAward_Grammar">Write out a grammar string to act as a trigger for a user obtaining this award</label><br>
		<input type="text" name="WPAward_Grammar" id="WPAward_Grammar" value="{$eGrammarString}"/>
HTML;
	}

	/**
	 * Adds in a meta box for our "Auto Give Award" functionality
	 */
	private function _addAutoGiveAwardMeta() {
		add_meta_box(
			$this->post_type . "_auto_give", // CSS ID Attribute
			'Auto Give Award', // Title
			[$this, '_autoGiveAwardHTML'], // Callback
			$this->post_type, // Page
			'side', // Context
			'default', // priority
			null // Callback Args
		);
	}

	/**
	 * HTML for auto give award checkbox for award
	 * @param  WP_Post $post - The full post that is being edited currently
	 * @return void
	 */
	function _autoGiveAwardHTML( $post ) {
		$auto_give_award_value = get_post_meta( $post->ID, 'WPAward_Auto_Give', true );
		$checked_box = checked( $auto_give_award_value, 'on', false );

		// Outputting Nonce
		wp_nonce_field( plugin_basename(__FILE__), 'WPAward_Save_Auto_Give_Meta');
		echo <<<HTML
		<input type="checkbox" name="WPAward_Auto_Give" id="WPAward_Auto_Give" value="on" {$checked_box}/>
		<label for="WPAward_Auto_Give">Checking this box will automatically give award to user when they trigger the award</label>
HTML;
	}

	function _applyAwardToUserMeta() {
		add_meta_box(
			$this->post_type . "_apply_award", // CSS ID Attribute
			'Apply/Give Award To User', // Title
			[$this, '_applyAwardToUserHTML'], // Callback
			$this->post_type, // Page
			'side', // Context
			'default', // priority
			null // Callback Args
		);
	}

	/**
	 * Meta box will display a list of the the users that are available.
	 * Enabling the ability to select a user out of the bunch, and apply the award to a user.
	 * @param  WP_Post $post - Post we are currently editing
	 * @return void
	 */
	function _applyAwardToUserHTML( $post ) {
		$users = get_users(); // Array of WP_User objects
		$submit_button = get_submit_button( 'Apply', 'primary large', 'submit', false, '' );
		$UserSelectHTML = call_user_func(["WPAward\Utility", "UserSelectHTML"], "WPAward_User_Apply");

		// Haha, what the fuck even is PHP?
		wp_nonce_field( plugin_basename(__FILE__), 'WPAward_Apply_Award_To_User');
		echo <<<HTML
		<label for="WPAward_User_Apply">Select a user from this dropdown and submit in order to apply this award to the user.</label>
		<br/>
		{$UserSelectHTML}
		<div class="WPAward_Actions">
			<span class="give-award-checkbox">
				<label for="WPAward_User_Give">Check box to give award to your user</label>
				<input id="WPAward_User_Give" name="WPAward_User_Give" type="checkbox"/>
			</span>
			{$submit_button}
		</div>
HTML;
	}

	/**
	 * Used to save WPAward specific meta values
	 * @param int $post_id - ID of the post we have saved.
	 */
	function WPAwardsSaveMetaBoxes( $post_id ) {

		$skip_autosave_actions = [
			WP_AWARDS_GRAMMAR_META_TYPE,
			'WPAward_Auto_Give',
			'WPAward_User_Apply'
		];

		// Reduce an array to a truthy/falsy boolean that will indicate whether any of our skip_autosave_actions are occuring.
		$performing_skip_autosave_action = array_reduce( $skip_autosave_actions, function( $acc, $current ) {
			if ( ! $acc )
			{
				return in_array( $current, $_POST );
			}
		}, false);

		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE && $performing_skip_autosave_action )
		{
			return;
		}

		// Are we posting an WPAward_Grammar?
		if ( isset( $_POST[WP_AWARDS_GRAMMAR_META_TYPE] ) )
		{
			// Check our nonce fields to see if they're good.
			check_admin_referer( plugin_basename(__FILE__), 'WPAward_Save_Grammar_Meta' );

			// Save the meta box data as post meta
			update_post_meta( $post_id, WP_AWARDS_GRAMMAR_META_TYPE, sanitize_text_field($_POST[WP_AWARDS_GRAMMAR_META_TYPE] ) );
		}

		// Are we posting a WPAward Auto Give value?
		if ( isset( $_POST['WPAward_Auto_Give'] ) )
		{
			// Check our nonce fields to see if they're good.
			check_admin_referer( plugin_basename(__FILE__), 'WPAward_Save_Auto_Give_Meta' );

			// Save the meta box data as post meta
			update_post_meta( $post_id, 'WPAward_Auto_Give', sanitize_text_field($_POST['WPAward_Auto_Give'] ) );
		}
		/**
		 * We are not posting a WPAward Auto Give Value, which means that the user has NOT selected
		 * it in the admin view. This is an innate functionality of <input type="checkbox">'es
		 */
		else if ( get_post_type( $post_id ) === $this->post_type )
		{
			delete_post_meta( $post_id, 'WPAward_Auto_Give');
		}

		// Are we trying to apply awards to users?
		if ( isset( $_POST['WPAward_User_Apply'] ) && $_POST['WPAward_User_Apply'] > 0 )
		{
			check_admin_referer( plugin_basename(__FILE__), 'WPAward_Apply_Award_To_User' );

			if ( isset( $_POST['WPAward_User_Give']) )
			{
				$this->WPAward->GiveAward( sanitize_text_field($_POST['WPAward_User_Apply']), $post_id );
			}
			else
			{
				// Assign the award to the given user
				$this->WPAward->AssignAward( sanitize_text_field($_POST['WPAward_User_Apply']), $post_id );
			}
		}
	}
}
?>
