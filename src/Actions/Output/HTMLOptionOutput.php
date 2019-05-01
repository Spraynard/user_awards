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

	private function buildOption( $value, $text, $selected )
	{
		if ( function_exists('esc_attr') ) {
			$value = esc_attr($value);
			$text = esc_html($text);
		}

		$selectedParam = "";

		if ( $selected )
		{
			$selectedParam = " selected";
		}

		return '<option value="' . $value . '"' . $selectedParam . '>' . $text . '</option>';
	}

	private function obtainTextValue( $item, $format_item )
	{
		$value = NULL;

		if ( is_array($item) && isset( $item, $format_item ) )
		{
			$value = $item[$format_item];
		}
		// Class Properties
		elseif ( is_object($item) && property_exists( $item, $format_item ) )
		{
			$value = $item->{$format_item};
		}

		return $value;
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
			if ( is_array($format) )
			{
				if ( ! isset( $format['format'] ) || ! isset( $format['values'] ) )
				{
					throw new Exception("If you want to output a formatted string for each of your options, you must provide a \"format\" string (i.e. 'hello %s') and a \"value\" array (which property to grab from item)");
				}

				$formatArray = [];

				foreach( $format['values'] as $format_item )
				{
					$formatArray[] = $this->obtainTextValue( $item, $format_item );
				}

				array_unshift( $formatArray, $format['format']);

				$value = call_user_func_array('sprintf', $formatArray);
			}
			else
			{
				$value = $this->obtainTextValue( $item, $format );
			}
		}
		else
		{
			$value = $item;
		}


		return $value;
	}

	public function setSelected( $selectedValue ) {
		$this->selected = $selectedValue;
	}

	public function output() {
		$returnHTML = $this->buildOption(
			$this->initialValue,
			$this->initialText,
			( is_null( $this->selected ) ? true : false )
		);

		foreach( $this->list as $item )
		{
			$item_value = $this->obtainProperty( $item, $this->valueFormat );
			$returnHTML .= $this->buildOption(
				$item_value,
				$this->obtainProperty( $item, $this->listFormat ),
				( $this->selected === $item_value ) // Truthy or falsy whether or not this item is actually selected
			);
		}

		return $returnHTML;
	}
} ?>