<?php

abstract class Action implements IAction {
	public $name;

	function __construct( $name ) {
		$this->name = $name;
	}

	function validate_for_execution() {
		throw new Exception("Not Implemented");
	}

	// I want to perform any validation checks before we get to the main execution context.
	function pre_execute() {

		// Always check our admin referrer
		check_admin_referer( plugin_basename( __FILE__ ), $this->name . "_nonce" );

		$this->validate_for_execution();
	}

	function main_execute() {
		throw new Exception("Not Implemented");
	}

	function post_execute() {
		return;
	}

	function execute() {
		$this->pre_execute();
		$this->main_execute();
		$this->post_execute();
	}
}
?>