<?php
namespace WPAward;

class WPAwardAwarder {
	function __construct() {

	}

	/**
	 * Function that awards an award to a user by interacting with the WP_User_Awards table of the database.
	 * @param [type] $user_id  [description]
	 * @param [type] $award_id [description]
	 * @param [type] $store    [description]
	 */
	public function AwardToUser( $user_id, $award_id, $store = NULL ) {
		global $wpdb;

		if ( ! isset( $store ) )
		{
			$store = $wpdb;
		}

		if ( ! isset( $store ) )
		{
			throw new \DomainException("There is no ability to store our values");
		}

		if ( true ) {
			return true;
		}

		return false;
	}
}
?>