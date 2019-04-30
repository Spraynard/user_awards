<?php
namespace WPAward\Actions\Output;

use \WPAward\Actions\Interfaces\IActionOutput;

class ActionOutput implements IActionOutput {
	private $name;
	private $value;
	private $label_text;

	function __construct( $name, $value, $label_text ) {
		$this->name = $name;
		$this->value = $value;
		$this->label_text = $label_text;
	}

	// Before we output anything we also want to output our nonce field!!!
	private function pre_output() {
		wp_nonce_field( plugin_basename(__FILE__), $this->name . "_nonce" );
	}

	// Should be implemented by subclasses
	private function output_main() {
		throw new Exception("Not Implemented");
	}

	// Main output function to use
	public function output() {
		$this->pre_output();
		$this->output_main();
	}

}
?>