<?php
namespace WPAward;

class AwardListener {
	private $grammar = null;
	private $grammarFunction = null;
	function __construct( $grammar_string ) {
		$this->grammar = new AwardGrammar( $grammar_string );
		$this->grammarFunction = $this->grammar->entity . '_' . $this->grammar->trigger_type;
	}

	public function add_listeners( $user ) {

		$action = "";

		if ( ! is_a( $user, 'WP_User') ) {
			throw new \InvalidArgumentException("User is not a WP_User");
		};

		// If we don't have a current user, there's no reason to give someone awards
		if ( ! $user->exists() )
		{
			throw new \UnexpectedValueException("User does not exist...");
		}

		// Pre-set our trigger tye based on whether the user has a user meta field or not.
		if ( $this->grammar->trigger_type == "assigned" )
		{
			if ( empty( get_user_meta($user->ID, $this->grammar->trigger->descriptor->key) ) )
			{
				$this->grammar->change_trigger_type("added_user_meta");
			}
			else
			{
				$this->grammar->change_trigger_type("updated_user_meta");
			}
		}

		// Explicit update
		if ( $this->grammar->trigger_type == "updated" )
		{
			$action = "updated_user_meta";
		}
		// Explicit Create
		else if ( $this->grammar->trigger_type == "created" )
		{
			$action = "added_user_meta";
		}

		add_action($action, [ $this, $this->grammarFunction ], 10, 4); // example: current_user_meta_updated
	}

	/**
	 * Function that responds to a user metadata update.
	 * Here we will test our grammar conditions
	 */
	function current_user_meta_updated( $meta_id, $object_id, $meta_key, $_meta_value ) {
		echo "Current User Meta Getting Updated";
		echo "VALUES:\n$meta_id\n, $object_id\n, $meta_key\n, $_meta_value\n";
		// Award user if our current user's meta key passes the grammar control
		$user_meta = get_user_meta( $object_id );
		$descriptor = $this->grammar->trigger->descriptor->key;

		// Check to see if our entity has a trigger descriptor associated with it.
		if ( ! is_array($user_meta) || empty( $user_meta[$descriptor] ) )
		{
			return;
		}

		echo "User Meta";
		print_r( $user_meta );
		// if ( )
	}

	function current_user_meta_excluded() {

	}

	function current_user_meta_created() {

	}
}
?>