<?php
namespace WPAward;

abstract class AwardGrammarType {
	public $input_string;

	function __construct( $input_string ) {
		$this->input_string = $input_string;
		$this->parse( $input_string);
	}

	abstract protected function parse( $input_string );
}