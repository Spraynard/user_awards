<?php
namespace WPAward\Actions\Output;

class DefaultInputOutput extends ActionOutput {
	private $input_type;

	function __construct($name, $value, $label_text, $input_type = "text" ) {
		parent::__construct( $name, $value, $label_text );
		$this->input_type = $input_type;
	}

	private function output_main() {
		$escaped_name = esc_attr($this->name);
		$escaped_value = esc_attr($this->value);
		$escaped_input_type = esc_attr($this->input_type);
		$escaped_label_text = esc_html($this->label_text);

		echo <<<HTML
		<label for="{$escaped_name}">{$escaped_label_text}</label>
		<input type="{$escaped_input_type}" id="{$escaped_name}" value="{$escaped_value}"/>
HTML;
	}
}
?>