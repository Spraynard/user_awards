<?php

namespace WPAward\Actions\Output;

class ActionOutput implements \WPAward\Actions\Interfaces\IActionOutput {
	private $name;
	private $value;
	private $label_text;

	function __construct( $name, $value, $label_text ) {
		$this->name = $name;
		$this->value = $value;
		$this->label_text = $label_text;
	}

	public function output() {
		throw new Exception("Not Implemented");
	}
}
?>