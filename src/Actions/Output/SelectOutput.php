<?php

namespace WPAward\Actions\Output;

class SelectOutput extends ActionOutput {
	private $option_output;

	function __construct($name, $label_text, HTMLOptionOutput $option_output, $current_value = NULL) {
		parent::__construct( $name, $current_value, $label_text );
		$this->option_output = $option_output;

		if ( ! is_null( $this->current_value ) )
		{
			$this->option_output->setSelected( $this->current_value );
		}
	}

	protected function output_main() {
		$escaped_name = esc_attr($this->name);
		$escaped_label_text = esc_html($this->label_text);

		$returnHTML = <<<HTML
		<label for="{$escaped_name}">{$escaped_label_text}</label>
		<br/>
		<select id="{$escaped_name}" name="{$escaped_name}">
HTML;
		$returnHTML .= $this->option_output->output();
		$returnHTML .= <<<HTML
		</select>
HTML;
		return $returnHTML;
	}
}

?>