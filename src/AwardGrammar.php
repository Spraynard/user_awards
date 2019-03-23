<?php

/**
 * Class that contains the rules for our grammar that will parse out our query and see if there is a need to trigger an award to be given to a user.
 *
 * The base structure for our grammar will be:
 * [ entity ] [ trigger_type ] WHERE [ trigger ]
 *
 * [ entity ]
 * 	- CURRENT_USER_META
 *
 * [ trigger_type ]
 * - UPDATED
 * - CREATED
 * - EXCLUDED
 *
 * [ trigger ] - [ trigger_descriptor ] [ trigger_control_operator ] [ trigger_control ]
 * 	- [ trigger_descriptor ]
 * 		- [ entity_type ] = [ value ]
 * 		ex: name = hours
 *
 * 	- [ trigger_control_operator ]
 * 		- GT - greater than
 * 		- LT - less than
 * 		- EQ - equal to
 * 	 	- GTEQ - greater than equal to
 * 	 	- LTEQ - less than equal to
 *
 *  - [ trigger_control ]
 *  	- Value used to compare against. e.g. 2
 *
 * EXAMPLE:
 * CURRENT_USER_META UPDATED WHERE name=total_hours GT 600
 *
 * This example creates a wp action handler that only applies when a user's meta tags are updated.
 * In the handler, we will compare the meta tag being updated to the given comparitors in the [ trigger ].
 * i.e. we will look for a meta tag of the current user that is labeled "total_hours" and check to see if the value is
 * greater than 600. If that's the case then we return true, if not we return negative.
 */

namespace WPAward;

class AwardGrammar {
	public $entity, $trigger_type, $trigger_descriptor, $trigger_control_operator, $trigger_control;
	public $input_string;

	private $valid_entities = [
		'current_user_meta'
	];

	private $valid_trigger_types = [
		'updated',
		'created',
		'excluded'
	];

	private $valid_trigger_control_operators = [
		'gt',
		'lt',
		'eq',
		'gteq',
		'lteq'
	];

	function __construct( $string ) {
		$this->input_string = $string;
	}

	private function throwIfNotValidated( $valid_items, $item, $eMsg ) {
		if ( ! in_array( $valid_items, $item ) )
		{
			throw new Exception( $eMsg );
		}

		return true;
	}

	private function validate_entity( $input ) {
		if ( $this->throwIfNotValidated( $this->valid_entities, $input, "Entity Not Valid" ) )
		{
			return $input;
		}
	}

	private function validate_trigger_type( $input ) {
		if ( $this->throwIfNotValidated( $this->valid_trigger_types, $input, "Trigger Type Not Valid" ) )
		{
			return $input;
		}
	}

	private function validate_trigger_descriptor( $input ) {

	}

	private function validate_trigger_control_operator( $input ) {
		if ( $this->throwIfNotValidated( $this->valid_trigger_control_operators, $input, "Trigger Control Operator Not Valid" ) )
		{
			return $input;
		}
	}

	private function validate_trigger_control( $input ) {

	}

	public function parse( $grammar_string ) {

		if ( empty( $grammar_string ) )
		{
			throw new Exception("Award Grammar parse function, empty string");
		}

		$serialized = explode(" ", $grammar_string);

		$this->entity = $serialized[0];
		$this->trigger_type = $serialized[1];
		$this->trigger_descriptor = $serialized[2];
		$this->trigger_control_operator = $serialized[3];
		$this->trigger_control = $serialized[4];
	}
}
?>