<?php
namespace WPAward\Actions\Output;

class CheckboxInputOutput extends DefaultInputOutput {
	private $checked;

	function __construct($name, $value, $label_text, $checked) {
		parent::__construct( $name, $value, $label_text, "checkbox" );
		$this->checked = $checked;
	}

	private function output_main() {
		$escaped_name = esc_attr($this->name);
		$escaped_value = esc_attr($this->value);
		$escaped_input_type = esc_attr($this->input_type);
		$escaped_label_text = esc_html($this->label_text);

		echo <<<HTML
		<label for="{$escaped_name}">{$escaped_label_text}</label>
		<input type="{$escaped_input_type}" id="{$escaped_name}" value="{$escaped_value}" {$checked}/>
HTML;
	}
}
?>