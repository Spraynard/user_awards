<?php
namespace WPAward;

/**
 * API for our plugin
 * Will be given as a global variable
 * to wordpress in order to easily add
 * own functionality and interact with our abstraction.
 */
class WPAward {
	function __construct() {

	}

	/** Function to handle plugin activation */
	function Activate( $dbVersion ) {
		$charset_collate = $this->wpdb->get_charset_collate();

		$table_name = $this->wpdb->prefix . "awards";

		$sql = "CREATE TABLE IF NOT EXISTS {$table_name} (
				id mediumint(9) NOT NULL AUTO_INCREMENT,
				user_id mediumint(9) NOT NULL,
				award_id mediumint(9) NOT NULL,
				date_assigned datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
				date_given datetime DEFAULT '0000-00-00 00:00:00',
				PRIMARY KEY  (id)
			) $charset_collate;";

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' ); // for dbDelta
		dbDelta( $sql );

		add_option( 'awards_db_version', $dbVersion );
	}

	/** Give out awards to users */
	function GiveAward( $award_id, $user_id ) {
		// Marks the award as given to the user, instead of just assigned to the user
	}

	function AssignAward( $award_id, $user_id ) {
		// Check if award is available
		// Check if user already has award
		// Assign Award
	}

	function RemoveAward( $award_id, $user_id ) {
		// Check if award is available
		// Check if user has award
		// Remove Award
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