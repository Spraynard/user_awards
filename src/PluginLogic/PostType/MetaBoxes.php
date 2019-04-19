<?php
namespace WPAward\PluginLogic\PostType;

class MetaBoxes {
	private $post_type;

	function __construct( $post_type ) {
		$this->post_type = $post_type;

		// Add meta boxes to post type and provide option to edit those values
		add_action('add_meta_boxes_' . $this->post_type, [$this, 'PostTypeMetaBoxes']);
		add_action('save_post', [$this, 'WPAwardsSaveMetaBoxes']); // Adds ability to save our meta values with our post

	}
/**
 * Main function to output our post meta box fields
 */
	public function PostTypeMetaBoxes() {
		$this->_addGrammarMeta(); // Trigger Meta Box
		$this->_addAutoGiveAwardMeta();
		$this->_applyAwardToUserMeta();
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
		$grammarString = get_post_meta( $post->ID, 'WPAward_Grammar', true);
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

	/**
	 * Used to save WPAward specific meta values
	 * @param int $post_id - ID of the post we have saved.
	 */
	function WPAwardsSaveMetaBoxes( $post_id ) {

		if (  isset( $_POST['WPAward_Grammar'] ) || isset( $_POST['WPAward_Auto_Give'] ) )
		{
			// Don't save anything on autosave
			if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
			{
				return;
			}
		}

		// Are we posting an WPAward_Grammar?
		if ( isset( $_POST['WPAward_Grammar'] ) )
		{
			// Check our nonce fields to see if they're good.
			check_admin_referer( plugin_basename(__FILE__), 'WPAward_Save_Grammar_Meta' );

			// Save the meta box data as post meta
			update_post_meta( $post_id, 'WPAward_Grammar', $_POST['WPAward_Grammar'] );
		}

		// Are we posting a WPAward Auto Give value?
		if ( isset( $_POST['WPAward_Auto_Give'] ) )
		{
			// Check our nonce fields to see if they're good.
			check_admin_referer( plugin_basename(__FILE__), 'WPAward_Save_Auto_Give_Meta' );

			// Save the meta box data as post meta
			update_post_meta( $post_id, 'WPAward_Auto_Give', $_POST['WPAward_Auto_Give'] );
		}
		/**
		 * We are not posting a WPAward Auto Give Value, which means that the user has NOT selected
		 * it in the admin view. This is an innate functionality of <input type="checkbox">'es
		 */
		else if ( get_post_type( $post_id ) === $this->post_type )
		{
			delete_post_meta( $post_id, 'WPAward_Auto_Give');
		}
	}
}
?>
