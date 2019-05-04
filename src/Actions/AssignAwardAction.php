<?php

namespace WPAward\Actions;

class AssignAwardAction extends Action {
	private $award_give_name;
	private $WPAward;

	function __construct( $name, $award_give_name, \WPAward\BusinessLogic\Core $WPAward ) {
		parent::__construct( $name );
		$this->WPAward = $WPAward;
		$this->award_give_name = $award_give_name;
	}

	// Check if we have a user_id and that it is valid
	protected function validateValue( $value ) {
		return ( ! is_null( $value ) && $value > 0 );
	}

	// Want to give or assigne the award to users based on what kind of HTTP Param we're obtaining.
	private function execute_main( $post_id ) {
		$this->WPAward->AssignAward( $this->value, $post_id );
	}
}
?>