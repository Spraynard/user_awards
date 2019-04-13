<?php
namespace WPAward\Grammar;

/**
 * Maybe a little too much abstraction....
 * [ trigger_descriptor ]
 * 		- [ entity_type ] = [ value ]
 * 		ex: name = hours
 */
class TriggerDescriptor extends GrammarType {
	public $input_string;
	public $key, $value;
	function __construct( $input_string ) {
		parent::__construct( $input_string );
	}

	protected function parse( $input_string ) {
		$serialized = explode("=", $input_string);

		$this->key = $serialized[0];
		$this->value = $serialized[1];
	}
}
?>