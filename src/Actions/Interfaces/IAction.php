<?php
namespace WPAward\Actions\Interfaces;

interface IAction {
	function validate();
	function pre_execute();
	function main_execute();
	function post_execute();
	public function execute();
}

?>