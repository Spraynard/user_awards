<?php
namespace WPAward;

/**
 * Class used to contain all trigger related functionality. This is a help because of the fact that
 * we're able to abstract all the details of our trigger into specific class variables
 *
 * [ triggers ] - [ trigger_descriptor ] [ trigger_control_operator ] [ trigger_control ]
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
 */
class AwardGrammarTrigger extends AwardGrammarType {
	public $trigger_descriptor, $trigger_control_operator, $trigger_control;

	function __construct( $string ) {
		parent::__construct($string);
	}

	private $valid_trigger_control_operators = [
		'gt',
		'lt',
		'eq',
		'gteq',
		'lteq'
	];

	// General function that throws an error if we don't have an item in an array.
	private function throwIfNotValidated( $valid_items, $item, $eMsg ) {
		if ( ! in_array( $item, $valid_items ) )
		{
			throw new \InvalidArgumentException( $eMsg );
		}

		return true;
	}
	private function validate_trigger_control_operator( $input ) {
		if ( $this->throwIfNotValidated( $this->valid_trigger_control_operators, $input, "Trigger Control Operator Not Valid" ) )
		{
			return $input;
		}
	}

	private function validateTriggerControl( $input ) {
		if ( $this->trigger_control_operator !== "eq" )
		{
			$input = intval( $input );
		}

		if ( empty( $input ) )
		{
			throw new \InvalidArgumentException("Trigger control must be a numeric if you're not testing equality");
		}

		return $input;
	}

	public function parse( $string ) {
		$serialized = explode(" ", $string);

		if ( empty( $serialized) ) {
			throw new \InvalidArgumentException("AwardGrammarTrigger parse string must not be empty");
		}

		$this->trigger_descriptor = new AwardGrammarTriggerDescriptor( $serialized[0] );
		$this->trigger_control_operator = $this->validate_trigger_control_operator($serialized[1]);
		$this->trigger_control = $this->validateTriggerControl($serialized[2]);
	}
}
?>