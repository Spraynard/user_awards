<?php
namespace WPAward\Actions\Output;

use WPAward\Actions\Interfaces\IOutput;

class HTMLOptionOutput implements IOutput {
	private $list;
	private $valueFormat;
	private $listFormat;
	private $initialText;
	private $initialValue;
	private $selected;

	function __construct( $list, $valueFormat = NULL, $listFormat = NULL, $selected = NULL , $initialText = "Select...", $initialValue = 0 ) {
		$this->list = $list;
		$this->valueFormat = $valueFormat;
		$this->listFormat = $listFormat;
		$this->initialText = $initialText;
		$this->initialValue = $initialValue;
		$this->selected = $selected;
	}

	private function buildOption( $value, $text ) {
		if ( function_exists('esc_attr') ) {
			$value = esc_attr($value);
			$text = esc_html($text);
		}
		return '<option value="' . $value . '">' . $text . '</option>';
	}

	/**
	 * Doing this in order to specify where I want my item's formatted text to come from
	 * @param  [type] $item   [description]
	 * @param  [type] $format [description]
	 * @return [type]         [description]
	 */
	private function obtainProperty( $item, $format ) {
		$value = NULL;

		if ( ! empty( $format ) )
		{
			if ( is_array($item) && isset( $item, $format ) )
			{
				$value = $item[$format];
			}
			// Class Properties
			elseif ( is_object($item) && property_exists( $item, $format ) )
			{
				$value = $item->{$format};
			}
		}
		else
		{
			$value = $item;
		}


		return $value;
	}

	public function output() {
		$returnHTML = $this->buildOption(
			$this->initialValue, $this->initialText);

		foreach( $this->list as $item )
		{
			$returnHTML .= $this->buildOption(
				$this->obtainProperty( $item, $this->valueFormat ),
				$this->obtainProperty( $item, $this->listFormat )
			);
		}

		return $returnHTML;
	}
} ?>