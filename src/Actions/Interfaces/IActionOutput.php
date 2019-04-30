<?php
namespace WPAward\Actions\Interfaces;

interface IActionOutput extends IOutput {
	function pre_output();
	function output_main();
	public function output();
}

?>