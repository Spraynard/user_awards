<?php
namespace WPAward\Grammar;

/**
 * Abstract class that's basically a nice placeholder for the basic idea of our grammar parsing functions.
 */

abstract class GrammarType {
	public $input_string;

	function __construct( $input_string ) {
		$this->input_string = $input_string;
		$this->parse( $input_string);
	}

	abstract protected function parse( $input_string );
}