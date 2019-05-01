<?php
namespace WPAward\Actions\Output;

/**
 * Provides an HTML template for outputting a checkbox
 */
class CheckboxInputOutput extends DefaultInputOutput {
	private $checked_value;

	function __construct($name, $checked_value, $label_text, $current_value = NULL ) {
		parent::__construct( $name, $label_text, $current_value, "checkbox" );
		$this->checked_value = $checked_value;
	}

	protected function output_main() {
		$escaped_name = esc_attr($this->name);
		$escaped_value = esc_attr($this->current_value);
		$escaped_label_text = esc_html($this->label_text);
		$checked = checked( $this->current_value, $this->checked_value, false );

		echo <<<HTML
		<label for="{$escaped_name}">{$escaped_label_text}</label>
		<input type="checkbox" id="{$escaped_name}" value="{$escaped_value}" {$checked}/>
HTML;
	}
}
?>