<?php
namespace WPAward;

class AwardListener {
	private $grammar;

	function __construct( $grammar_string ) {
		$this->grammar = new AwardGrammar( $grammar_string );
		$this->add_listeners();
	}

	function add_listeners() {

	}


}
?>