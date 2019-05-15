<?php
namespace UserAwards\PluginLogic\PostType;

class MetaBoxes {
	private $post_type;
	private $UserAwards;

	function __construct( $post_type, $UserAwards ) {
		$this->post_type = $post_type;
		$this->UserAwards = $UserAwards;
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
		$grammarString = get_post_meta( $post->ID, USER_AWARDS_GRAMMAR_META_TYPE, true);
		$eGrammarString = esc_attr( $grammarString );
		wp_nonce_field( plugin_basename(__FILE__), 'UserAwards_Save_Grammar_Meta');
		echo <<<HTML
		<label for="UserAwards_Grammar">Write out a grammar string to act as a trigger for a user obtaining this award</label><br>
		<input type="text" name="UserAwards_Grammar" id="UserAwards_Grammar" value="{$eGrammarString}"/>
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
		$auto_give_award_value = get_post_meta( $post->ID, 'UserAwards_Auto_Give', true );
		$checked_box = checked( $auto_give_award_value, 'on', false );

		// Outputting Nonce
		wp_nonce_field( plugin_basename(__FILE__), 'UserAwards_Save_Auto_Give_Meta');
		echo <<<HTML
		<input type="checkbox" name="UserAwards_Auto_Give" id="UserAwards_Auto_Give" value="on" {$checked_box}/>
		<label for="UserAwards_Auto_Give">Checking this box will automatically give award to user when they trigger the award</label>
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
		$UserSelectHTML = call_user_func(["UserAwards\Utility", "UserSelectHTML"], "UserAwards_User_Apply");

		// Haha, what the fuck even is PHP?
		wp_nonce_field( plugin_basename(__FILE__), 'UserAwards_Apply_Award_To_User');
		echo <<<HTML
		<label for="UserAwards_User_Apply">Select a user from this dropdown and submit in order to apply this award to the user.</label>
		<br/>
		{$UserSelectHTML}
		<div class="UserAwards_Actions">
			<span class="give-award-checkbox">
				<label for="UserAwards_User_Give">Check box to give award to your user</label>
				<input id="UserAwards_User_Give" name="UserAwards_User_Give" type="checkbox"/>
			</span>
			{$submit_button}
		</div>
HTML;
	}

	/**
	 * Used to save UserAwards specific meta values
	 * @param int $post_id - ID of the post we have saved.
	 */
	function UserAwardsSaveMetaBoxes( $post_id ) {

		$skip_autosave_actions = [
			USER_AWARDS_GRAMMAR_META_TYPE,
			'UserAwards_Auto_Give',
			'UserAwards_User_Apply'
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

		// Are we posting an UserAwards_Grammar?
		if ( isset( $_POST[USER_AWARDS_GRAMMAR_META_TYPE] ) )
		{
			// Check our nonce fields to see if they're good.
			check_admin_referer( plugin_basename(__FILE__), 'UserAwards_Save_Grammar_Meta' );

			// Save the meta box data as post meta
			update_post_meta( $post_id, USER_AWARDS_GRAMMAR_META_TYPE, sanitize_text_field($_POST[USER_AWARDS_GRAMMAR_META_TYPE] ) );
		}

		// Are we posting a UserAwards Auto Give value?
		if ( isset( $_POST['UserAwards_Auto_Give'] ) )
		{
			// Check our nonce fields to see if they're good.
			check_admin_referer( plugin_basename(__FILE__), 'UserAwards_Save_Auto_Give_Meta' );

			// Save the meta box data as post meta
			update_post_meta( $post_id, 'UserAwards_Auto_Give', sanitize_text_field($_POST['UserAwards_Auto_Give'] ) );
		}
		/**
		 * We are not posting a UserAwards Auto Give Value, which means that the user has NOT selected
		 * it in the admin view. This is an innate functionality of <input type="checkbox">'es
		 */
		else if ( get_post_type( $post_id ) === $this->post_type )
		{
			delete_post_meta( $post_id, 'UserAwards_Auto_Give');
		}

		// Are we trying to apply awards to users?
		if ( isset( $_POST['UserAwards_User_Apply'] ) && $_POST['UserAwards_User_Apply'] > 0 )
		{
			check_admin_referer( plugin_basename(__FILE__), 'UserAwards_Apply_Award_To_User' );

			if ( isset( $_POST['UserAwards_User_Give']) )
			{
				$this->UserAwards->GiveAward( sanitize_text_field($_POST['UserAwards_User_Apply']), $post_id );
			}
			else
			{
				// Assign the award to the given user
				$this->UserAwards->AssignAward( sanitize_text_field($_POST['UserAwards_User_Apply']), $post_id );
			}
		}
	}
}
?>
