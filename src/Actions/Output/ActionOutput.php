<?php
namespace WPAward\Actions\Output;

use \WPAward\Actions\Interfaces\IOutput;

class ActionOutput implements IOutput {
	protected $name;
	protected $current_value;
	protected $label_text;
	private $obtain_fun;

	function __construct( $name, $current_value, $label_text, $obtain_fun = 'get_post_meta') {
		$this->name = $name;
		$this->current_value = $current_value;
		$this->label_text = $label_text;
		$this->obtain_fun = $obtain_fun;
	}

	// Before we output anything we also want to output our nonce field!!!
	private function pre_output( $post ) {
		if ( function_exists($this->obtain_fun) )
		{
			$this->current_value = call_user_func( $this->obtain_fun, $post->ID, $this->name, true );
		}

		wp_nonce_field( $this->name . "nonce_action", $this->name . "_nonce" );
	}

	// Should be implemented by subclasses
	protected function output_main() {
		throw new \Exception("Not Implemented");
	}

	// Main output function to use
	public function output( $post ) {
		$this->pre_output( $post );
		$this->output_main();
	}

}
?>