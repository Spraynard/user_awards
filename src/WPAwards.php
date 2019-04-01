<?php
namespace WPAward;

/**
 * API for our plugin
 * Will be given as a global variable
 * to wordpress in order to easily add
 * own functionality and interact with our abstraction.
 */
class WPAwards {
	function __construct() {

	}

	/** Give out awards to users */
	function GiveAward( $award_id, $user_id ) {
		// Check if award is available
		// Check if user already has award
		// Assign Award
	}

	function RemoveAward( $award_id, $user_id ) {
		// Check if award is available
		// Check if user has award
		// Remove Award
	}
}
?>