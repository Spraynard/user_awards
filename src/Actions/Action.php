<?php

namespace WPAward\Actions;

class Action implements IAction {
	public $name;

	function __construct( $name ) {
		$this->name = $name;
	}

	/**
	 * Function used to ensure that our action should be running or not
	 */
	private function validate() {
		throw new Exception("Not Implemented");
	}

	/**
	 * Logic performed before an action execution
	 * @return [type] [description]
	 */
	private function pre_execute() {
		// Always check our admin referrer
		check_admin_referer( plugin_basename( __FILE__ ), $this->name . "_nonce" );

		$this->validate();
	}

	private function main_execute() {
		throw new Exception("Not Implemented");
	}

	/**
	 * Logic performed after an action execution
	 * @return [type] [description]
	 */
	private function post_execute() {
		return;
	}

	/**
	 * [execute description]
	 * @return [type] [description]
	 */
	public function execute() {
		try{
			$this->pre_execute();
			$this->main_execute();
			$this->post_execute();
		} catch( Exception $e ) {
			error_log($e);
		}
	}
}
?>