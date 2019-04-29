<?php
namespace WPAward\Actions\Output;

class InputActionOutput extends ActionOutput {
	private $input_type;

	function __construct($name, $value, $label_text, $input_type = "text" ) {
		parent::__construct( $name, $value, $label_text );
		$this->input_type = $input_type;
	}


	public function output() {
		$label_for = esc_attr($this->name);
		$label_text = esc_html($this->label_text);
		$input_type = esc_attr($this->input_type);
		$input_value = esc_attr($this->value);
		$input_id = esc_attr($this->name);

		echo <<<HTML
		<label for="{$label_for}">{$label_text}</label>
		<input type="{$input_type}" id="{$input_id}" value="{$value}"/>
HTML;
	}
} ?>