<?php
namespace WPAward\PluginLogic;

// Including our WP_List_Table class if it is not currently available.

if(!class_exists('WP_List_Table')) require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );

class UserAwardsTable extends \WP_List_Table {
	function __construct()
	{
		parent::__construct([
			'singular' => 'wp_award',
			'plural' => 'wp_awards',
			'ajax' => false
		]);
	}

	/**
	 *
	 *
	 * Column Specific Functions
	 *
	 *
	 */

	/**
	 * Define the colums that will be used in the table to display items.
	 * @return array - Columns in our table
	 */
	function get_columns() {
		return [
			'award' => 'Award',
			'user' => 'User',
			'date_assigned' => 'Date Award Assigned',
			'date_given' => 'Date Award Given'
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
		return $user->data->user_nicename;
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

	/** End Column Specific Functions */

	function prepare_items()
	{
		global $wpdb;

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