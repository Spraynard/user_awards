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