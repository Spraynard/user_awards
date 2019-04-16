<?php
/**
 * Namespace: PluginLogic
 * Name: Core
 * Description: Contains our logic for providing WordPress specific logic for our plugin.
 * This includes entities such as Post Types, Meta Boxes, and the required functionality for each of these entities.
 */
namespace WPAward\PluginLogic;

class Core {
	private $post_type_name;

	function __construct( $post_type_name ) {
		$this->post_type_name = $post_type_name;
		add_action('init', [$this, 'PostType']); // Adds our custom post type
		add_action('add_meta_boxes_' . $this->post_type_name, [$this, 'PostTypeMetaBoxes']); // Adds meta boxes to our custom post
		add_action('save_post', [$this, 'WPAwardsSaveMetaBoxes']); // Adds ability to save our meta values with our post


		// Adding in custom columns to our post type.
		add_filter('manage_' . $this->post_type_name . '_posts_columns', [$this, 'PostTypeAdminColumns']);

		// Adding in data for each of our columns
		add_action('manage_' . $this->post_type_name . '_posts_custom_column', [$this, 'PostTypeAdminColumnsData'] ,10, 2);
	}

	/**
	 * Register post type with wordpress
	 */
	public function PostType() {
		$args = [
			'labels' => [
				'name' => 'Awards', // Name of our custom post type
				'singular_name' => 'Award', // Singular version of the name
				'add_new' => 'Add New Award',
				'add_new_item' => 'Add New Award', // Add New Page Header
				'edit_item' => 'Edit Award', // Edit Page
				'view_item' => 'View Award', // Text for viewing a single entry
				'search_items' => 'Search Awards', // Text displayed for searching
				'not_found' => 'No Awards Found', // Text displayed when no awards were found in a search
				'not_found_in_trash' => 'No Awards Found in Trash', // Test shown when awards found in trash
				'menu_name' => 'Awards'
			],
			'show_ui' => true
		];

		register_post_type($this->post_type_name, $args);
	}

	/**
	 * Adding custom columns to post lists
	 */
	public function PostTypeAdminColumns( $columns )
	{

		$columns = [
			'cb' => $columns['cb'],
			'title' => $columns['title'],
			'trigger' => 'Trigger',
			'auto_give' => 'Auto Give',
			'date' => $columns['date']
		];

		return $columns;
	}

	public function PostTypeAdminColumnsData( $column, $post_id )
	{
		switch( $column ) {
			case 'trigger':
				$value = get_post_meta( $post_id, 'WPAward_Grammar', true);
				$value = empty($value) ? "[No Trigger Currently]" : $value;
				break;
			case 'auto_give':
				$value = empty( get_post_meta( $post_id, 'WPAward_Auto_Give', true) ) ? "No" : "Yes";
				break;
		}

		echo $value;
	}

	/**
	 * Main function to output our post meta box fields
	 */
	public function PostTypeMetaBoxes() {
		$this->_addGrammarMeta();
		$this->_addAutoGiveAwardMeta();
	}

	private function _addGrammarMeta() {
		add_meta_box(
			$this->post_type_name . "_grammar", // CSS ID Attribute
			'Award Trigger', // Title
			[$this, '_grammarMetaHTML'], // Callback
			$this->post_type_name, // Page
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
			$this->post_type_name . "_auto_give", // CSS ID Attribute
			'Auto Give Award', // Title
			[$this, '_autoGiveAwardHTML'], // Callback
			$this->post_type_name, // Page
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
		else if ( get_post_type( $post_id ) === $this->post_type_name )
		{
			delete_post_meta( $post_id, 'WPAward_Auto_Give');
		}
	}
}
?>