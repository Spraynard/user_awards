<?php
namespace WPAward;

/**
 * API for our plugin
 * Will be given as a global variable
 * to wordpress in order to easily add
 * own functionality and interact with our abstraction.
 */
class WPAward {
	private $db;
	private $db_table;
	private $db_collation;
	private $db_version = "0.1"; // Change this when you change the version of your DB.

	function __construct( $db ) {
		$this->db = $db;
		$this->db_table = $this->db->prefix . "awards";
		$this->db_collation = $this->db->get_charset_collate();
	}

	private function __auto_give_award( $award_id ) {
		return get_post_meta( $award_id, 'wap_auto_give', true )
	}

	/** Function to handle plugin activation */
	function Activate() {
		$sql = "CREATE TABLE IF NOT EXISTS {$this->db_table} (
				id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
				user_id bigint(20) unsigned NOT NULL,
				award_id bigint(20) unsigned NOT NULL,
				date_assigned datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
				date_given datetime DEFAULT NULL,
				PRIMARY KEY  (id)
			) {$this->db_collation};";

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' ); // for dbDelta
		dbDelta( $sql );

		add_option( 'awards_db_version', $this->db_version );
	}

	function Deactivate() {
		// Don't really know what I'd do for deactivation.
	}

	/** Function to handle plugin uninstallation */
	function Uninstall() {
		$sql = "DROP TABLE IF EXISTS {$this->db_table}";
		$this->db->query( $sql );
		delete_option('awards_db_version');
	}

	/**
	 * Function that will mark an award as given to a user,
	 * which essentially means that we mark the "date_given" time with
	 * an actual date.
	 *
	 * Returns the return value of a `db->update` call
	 *
	 * @param int $user_id  - ID of the user that we are "awarding" the award to
	 * @param int $award_id - ID of the award that we are "awarding"
	 *
	 */
	function GiveAward( $user_id, $award_id ) {

		// Marks the award as given to the user, instead of just assigned to the user
		$award_given = $this->db->update(
			$this->db_table,
			[
				'date_given' => date('Y-m-d G:i:s')
			],
			[
				'user_id' => $user_id,
				'award_id' => $award_id
			],
			NULL,
			[
				'user_id' => '%d',
				'award_id' => '%d'
			]
		);

		return $award_given;
	}

	/**
	 * Function that marks an award as assigned to a user.
	 * We insert a new record into our awards table that relates the award to the user.
	 *
	 * We do check to see if there is an auto-assignment of the award before we finish up our function though.
	 *
	 * @param int $user_id  - ID of the user that we are "awarding" the award to
	 * @param int $award_id - ID of the award that we are "awarding"
	 */
	function AssignAward( $user_id, $award_id ) {

		$award_assigned = $this->db->insert(
			$this->db_table,
			[
				'user_id' => $user_id,
				'award_id' => $award_id,
			],
			[
				'user_id' => '%d',
				'award_id' => '%d'
			]
		)

		if ( ! $award_assigned )
		{
			return false;
		}

		if ( $this->__auto_give_award( $award_id ) )
		{
			$award_given = $this->GiveAward( $award_id, $user_id );
		}

		return true;
	}

	/**
	 * Removes awards from our database.
	 * If "$award_id" is null, then we are going to delete everything in the database with the specific "$user_id"
	 *
	 * @param int $user_id  - ID of the user that we are "awarding" the award to
	 * @param int $award_id - ID of the award that we are "awarding"
	 */
	function RemoveAward( $user_id, $award_id = NULL ) {
		// Check if award is available
		// Check if user has award
		// Remove Award
		//

	}

	/**
	 * [HasAward description]
	 * @param int $user_id  - ID of the user that we are "awarding" the award to
	 * @param int $award_id - ID of the award that we are "awarding"
	 */
	function HasAward( $user_id, $award_id ) {
		if ( )
		// Check if award is available
		// Check if user has award
		// Return based on this check
	}

	/**
	 * Compares $val_1 to $val_2 based on the operator given.
	 * @param mixed $val_1
	 * @param mixed $val_2
	 * @param string $op    - Operator.
	 */
	function ShouldApplyAward( $val_1, $val_2, $op ) {
		switch ( $op ) {
			case 'gt':
				return $val_1 > $val_2;
			case 'lt':
				return $val_1 < $val_2;
			case 'eq':
				return $val_1 === $val_2;
			case 'gteq':
				return $val_1 >= $val_2;
			case 'lteq':
				return $val_1 <= $val_2;
		}
	}
}
?>