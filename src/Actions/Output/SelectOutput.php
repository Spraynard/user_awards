<?php

namespace WPAward\Actions\Output;

class SelectOutput extends ActionOutput {
	private $items;

	function __construct($name, $value, $label_text, ListOutput $items = [ "list" => NULL, "list_val" => NULL, "list_format" => NULL, "initial_text" => NULL, "initial_value" => NULL ] ) {
		parent::__construct( $name, $value, $label_text );
		$this->items = $items;

		if ( ! $this->items["items_initial_text"] )
		{
			$this->items["initial_text"] = "Select...";
			$this->items["initial_value"] = 0;
		}
	}

	private function output_main() {
		$escaped_name = esc_attr($this->name);
		$escaped_input_type = esc_attr($this->input_type);
		$escaped_label_text = esc_html($this->label_text);
		$initial_value = esc_attr($this->items["initial_value"]);
		$initial_text = esc_html($this->items["initial_text"]);

		echo <<<HTML
		<label for="{$escaped_name}">{$escaped_label_text}</label>
		<br/>
		<select id="{$escaped_name}" name="{$escaped_name}">
			<option value="{$initial_value}">{$initial_text}</option>
HTML;
		foreach( $this->items['list'] as $item )
		{
			$escaped_value = esc_attr($item[$this->items['list_val']]);
			$escaped_format = esc_html($item[$this->items['list_format']]);

			echo <<<HTML
			<option
HTML;
		}

		echo <<<HTML
		</select>
HTML;
	}
}

?>