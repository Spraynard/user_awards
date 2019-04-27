<?php
namespace WPAward\PluginLogic;

// Including our WP_List_Table class if it is not currently available.

if( ! class_exists('WP_List_Table') ) require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );

class UserAwardsTable extends \WP_List_Table {
	private $WPAward;

	function __construct( $WPAward )
	{
		parent::__construct([
			'singular' => 'wp_award',
			'plural' => 'wp_awards',
			'ajax' => false
		]);

		$this->WPAward = $WPAward;
	}

	/**
	 * COLUMN SPECIFIC FUNCTIONS
	 */

	/**
	 * Define the colums that will be used in the table to display items.
	 * @return array - Columns in our table
	 */
	function get_columns() {
		return [
			'cb' => '<input type="checkbox" />',
			'award' => 'Award',
			'user' => 'User',
			'date_assigned' => 'Date Award Assigned',
			'date_given' => 'Date Award Given',
		];
	}

	/**
	 * Want to display the award name instead of the id
	 * @param  array $item - Singular Award
	 * @return string      - Column value in string form
	 */
	function column_award( $item ) {
		$post = get_post( $item->award_id );
		return apply_filters( 'the_title', $post->post_title );
	}

	/**
	 * Want to display the username instead of an id
	 * @param  [type] $item [description]
	 * @return [type]       [description]
	 */
	function column_user( $item ) {
		$user = get_user_by('id', $item->user_id);
		return ucfirst($user->data->user_nicename);
	}

	/**
	 * Displays the date in which the award was first assigned.
	 * @param  array $item - Singular Award
	 * @return string      - Column value in string form
	 */
	function column_date_assigned( $item ) {
		return $item->date_assigned;
	}

	/**
	 * Provide actions to give the user the award from our UI
	 * @param  array $item - Singular Award
	 * @return string      - Column value in string form
	 */
	function column_date_given( $item ) {
		return $item->date_given;
	}

	/**
	 * Provide a checkbox here that is filled with the item's ID I'm guessing
	 */
	function column_cb( $item ) {
		return sprintf(
			'<input type="checkbox" name="%s[%d][]" value="%d"/>',
			$this->_args['plural'], // Singular name is getting used as the name here (award)
			$item->user_id,
			$item->award_id
		);
	}

	/**
	 * Bulk actions available to use on this table.
	 */
	function get_bulk_actions() {
		return array(
			'remove_award' => "Remove Award"
		);
	}

	/**
	 * Bulk action processing
	 */
	function process_bulk_actions() {
		/**
		 * WE SHOULD BE PROCESSING OUR NONCE HERE MY DUDES
		 */

		/**
		 * Process the REMOVE_AWARD bulk action in which remove the awards that have been selected
		 */
		if ( $this->current_action() === "remove_award" )
		{
			# This value should have award_id and user_id separated by a "|" character.
			if ( ! empty($_POST[$this->_args['plural']]) )
			{
				$user_award_array = $_POST[$this->_args['plural']];
				$award_count = 0;

				foreach( $user_award_array as $user_id => $award_ids )
				{
					foreach( $award_ids as $award_id )
					{
						$this->WPAward->RemoveUserAward( $user_id, $award_id );
						$award_count++;
					}
				}
			}
		}
	}

	/**
	 * Handles admin notices for our table currently.
	 */
	static function awards_table_admin_notices() {
		$awards_removed_string_array = NULL;

		/**
		 * Based on the awards removed, indicate how many awards were removed from which person.
		 */
		if ( ! empty($_REQUEST['action'] ) &&  $_REQUEST['action'] === "remove_award" )
		{
			if ( ! empty($_REQUEST['wp_awards']) )
			{
				$awards_removed_string_array = array_map(function( $user_id, $post_ids ) {
					$user = get_user_by("ID", $user_id);
					return sprintf(
						"%d award%s removed from %s",
						count($post_ids),
						(count($post_ids) > 1) ? 's' : '', // Plural / Singular
						ucfirst($user->user_nicename)
					);
				}, array_keys($_REQUEST['wp_awards']), $_REQUEST['wp_awards']);
			}
		}

		if ( ! is_null( $awards_removed_string_array ) )
		{
			$awards_removed_string = "";

			foreach( $awards_removed_string_array as $index => $removed_string )
			{
				if ( $index )
				{
					$awards_removed_string .= " and ";
				}

				$awards_removed_string .= $removed_string;
			}

			$message_string = '<div id="message" class="updated notice is-dismissible"><p>%s</p></div>';

			printf( $message_string, $awards_removed_string );
		}
	}

	/** End Column Specific Functions */

	function prepare_items()
	{
		global $wpdb;

		$this->process_bulk_actions();

		$columns = $this->get_columns();

		$this->_column_headers = [
			$columns, // Visible Columns
			[], // Hidden Columns
			[] // Sortable Columns
		];

		$query = "SELECT * FROM {$wpdb->prefix}awards";

		$this->items = $wpdb->get_results($query);
	}
}
?>