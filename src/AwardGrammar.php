<?php

/**
 * Class that contains the rules for our grammar that will parse out our query and see if there is a need to trigger an award to be given to a user.
 *
 * The base structure for our grammar will be:
 * [ entity ] [ trigger_type ] WHERE [ triggers ]
 *
 * [ entity ]
 * 	- CURRENT_USER_META
 *
 * [ trigger_type ]
 * - UPDATED
 * - CREATED
 * - EXCLUDED
 *
 * [ triggers ] - [ trigger_descriptor ] [ trigger_control_operator ] [ trigger_control ]
 * 	- [ trigger_descriptor ]
 * 		- [ entity_type ] = [ value ]
 * 		ex: key = hours
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
 * CURRENT_USER_META UPDATED WHERE key=total_hours GT 600
 *
 * This example creates a wp action handler that only applies when a user's meta tags are updated.
 * In the handler, we will compare the meta tag being updated to the given comparitors in the [ trigger ].
 * i.e. we will look for a meta tag of the current user that is labeled "total_hours" and check to see if the value is
 * greater than 600. If that's the case then we return true, if not we return negative.
 */

namespace WPAward;

class AwardGrammar extends AwardGrammarType {
	public $entity, $trigger_type, $trigger, $input_string;

	/**
	 * Validation items for our grammar
	 */
	private $valid_entities = [
		'current_user_meta'
	];

	private $valid_trigger_types = [
		'updated',
		'created',
		'excluded'
	];

	/** End validation items. */

	function __construct( $string ) {
		parent::__construct( $string );
	}

	// General function that throws an error if we don't have an item in an array.
	private function throwIfNotValidated( $valid_items, $item, $eMsg ) {
		if ( ! in_array( $item, $valid_items ) )
		{
			throw new \InvalidArgumentException( $eMsg );
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
		return $input;
	}

	private function validate_trigger_control_operator( $input ) {
		if ( $this->throwIfNotValidated( $this->valid_trigger_control_operators, $input, "Trigger Control Operator Not Valid" ) )
		{
			return $input;
		}
	}

	private function validate_trigger_control( $input ) {
		return $input;
	}

	/**
	 * Function use to parse our trigger lang and apply basic information to our object. Very important.
	 * @param  string $grammar_string - Trigger lang applied to our award that specifies how we are awarding.
	 * @return void
	 */
	protected function parse( $grammar_string ) {

		if ( empty( $grammar_string ) )
		{
			throw new \InvalidArgumentException("Award Grammar parse function, empty string");
		}


		$parseCount = 0;
		$parseValue;

		$serialized = explode(" ", strtolower($grammar_string));

		// Parse our string up until the WHERE clause, because that brings in a whole bunch of processing that we need to do.
		while ( $parseValue != "where" )
		{
			$parseValue = array_shift( $serialized );

			switch ( $parseCount ) {
				case 0:
					$this->entity = $this->validate_entity($parseValue);
					break;
				case 1:
					$this->trigger_type = $this->validate_trigger_type($parseValue);
					break;
				default:
					break;
			}

			// Possible malforming of the string wont include our where clause, which we "need" in order to make sure we are
			// getting our trigger statement.
			if ( empty( $serialized ) )
			{
				throw new \InvalidArgumentException("You must include a \"WHERE\" clause in your statement");
			}

			$parseCount++;
		}

		// Re-Stringify the query in order to get the "trigger" portion of it
		$serialized = implode(" ", $serialized);

		$this->trigger = new AwardGrammarTrigger( $serialized );
	}
}
?>