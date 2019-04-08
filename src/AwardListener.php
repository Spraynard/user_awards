<?php
namespace WPAward;

class AwardListener {
	private $grammar = null;

	function __construct( $grammar_string ) {
		$this->grammar = new AwardGrammar( $grammar_string );
	}

	public function add_listeners( $user ) {

		if ( ! is_a( $user, 'WP_User') ) {
			return false;
		};

		// If we don't have a current user, there's no reason to give someone awards
		if ( ! $current_user->exists() )
		{
			return false;
		}

		if ( $this->grammar->trigger_type == "updated" )
		{
			do_action('updated_user_meta', [
				$this,
				call_user_func(
					$this->grammar->entity . '_' . $this->grammar->trigger_type,
					$current_user
				)
				// example: current_user_meta_updated

			], 10, 4);
		}
	}

	/**
	 * Function that responds to a user metadata update.
	 * Here we will test our grammar conditions
	 */
	function current_user_meta_updated( $user ) {
		// Award user if our current user's meta key passes the grammar control
		$user_meta = get_user_meta( $user );
		$trigger_descriptor = $this->grammar->trigger_descriptor->key;

		// Check to see if our entity has a trigger descriptor associated with it.
		if ( ! is_array($user_meta) || empty( $user_meta[$trigger_descriptor] ) )
		{
			return;
		}

		if ( )
	}

	function current_user_meta_excluded() {

	}

	function current_user_meta_created() {

	}
}
?>