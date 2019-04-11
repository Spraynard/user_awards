<?php
namespace WPAward;

class AwardListener {
	private $award_id;
	private $grammar = null;
	private $grammarFunction = null;
	private $awarder = null;

	/**
	 * Class Constructor
	 * @param string $award_grammar_string - String of our trigger grammar to use that will put a listener up
	 * @param WPAward $awarder       - Awarder that performs award operations on a user, such as checking if the user should have an award or what not.
	 */
	function __construct( $award_id, $award_grammar_string, $awarder ) {
		$this->award_id = $award_id;
		$this->grammar = new AwardGrammar( $award_grammar_string );
		$this->grammarFunction = $this->grammar->entity . '_' . $this->grammar->trigger_type;
		$this->awarder = $awarder;
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

		// Award user if our current user's meta key passes the grammar control
		$descriptor = $this->grammar->trigger->descriptor->value;

		echo "\nCurrent User Meta Getting Updated\n";
		echo "VALUES:\n$meta_id\n, $object_id\n, $meta_key\n, $_meta_value\n";
		echo "Descriptor: $descriptor\n";

		// Check if the updated meta key is the same as the meta key we are listening for
		if ( $meta_key !== $descriptor )
		{
			return;
		}

		// Testing whether we should apply an award to a user.
		if ( ! $this->awarder->shouldApplyAward(
			$_meta_value,
			$this->grammar->trigger->control,
			$this->grammar->trigger->operator
		) )
		{
			return;
		}

		// Finally, assign our award if we make it this far
		$this->awarder->AssignAward( $this->award_id ,$object_id );
	}

	function current_user_meta_excluded() {

	}

	function current_user_meta_created() {

	}
}
?>