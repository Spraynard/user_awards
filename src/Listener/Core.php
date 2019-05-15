<?php
namespace UserAwards\Listener;

class Core {
	private $award_id;
	private $grammar = null;
	private $grammarFunction = null;
	private $UserAwards = null;

	/**
	 * Class Constructor
	 * @param string $award_grammar_string - String of our trigger grammar to use that will put a listener up
	 * @param WPAward $UserAwards       - WPAward that performs award operations on a user, such as checking if the user should have an award or what not.
	 */
	function __construct( $award_id, $grammar, $UserAwards ) {
		$this->award_id = $award_id;
		$this->grammar = $grammar;
		$this->grammarFunction = $this->grammar->entity . '_' . $this->grammar->trigger_type;
		$this->UserAwards = $UserAwards;
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

		// Pre-set our trigger type based on whether the user has a user meta field or not.
		if ( $this->grammar->trigger_type == "assigned" )
		{
			if ( empty( get_user_meta($user->ID, $this->grammar->trigger->descriptor->value, true) ) )
			{
				$this->grammar->change_trigger_type("created");
			}
			else
			{
				$this->grammar->change_trigger_type("updated");
			}

			$this->grammarFunction = $this->grammar->entity . '_' . $this->grammar->trigger_type;
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

	function assignAward( $meta_id, $object_id, $meta_key, $_meta_value )
	{

		// Award user if our current user's meta key passes the grammar control
		$descriptor = $this->grammar->trigger->descriptor->value;

		// Check if the updated meta key is the same as the meta key we are listening for
		if ( $meta_key !== $descriptor )
		{
			return;
		}

		// Testing whether we should apply an award to a user.
		if ( ! $this->UserAwards->shouldApplyAward(
			$_meta_value,
			$this->grammar->trigger->control,
			$this->grammar->trigger->operator
		) )
		{
			return;
		}

		// Finally, assign our award if we make it this far
		$award_assigned = $this->UserAwards->AssignAward( $object_id, $this->award_id );

		if ( ! $award_assigned )
		{
			throw new \RuntimeExeption("Award was not assigned on user meta update when it should have been");
		}
	}

	/**
	 * Function that responds to a user metadata update. Will assign an award to a user if and only if they pass the
	 * award's trigger conditions.
	 */
	function current_user_meta_updated( $meta_id, $object_id, $meta_key, $_meta_value ) {
		$this->assignAward($meta_id, $object_id, $meta_key, $_meta_value);
	}

	function current_user_meta_excluded( $meta_id, $object_id, $meta_key, $_meta_value )
	{
		// Maybe v0.0.2
	}

	function current_user_meta_created( $meta_id, $object_id, $meta_key, $_meta_value )
	{
		$this->assignAward($meta_id, $object_id, $meta_key, $_meta_value);
	}

	function current_user_meta_assigned( $meta_id, $object_id, $meta_key, $_meta_value )
	{
		$this->assignAward($meta_id, $object_id, $meta_key, $_meta_value);
	}
}
?>