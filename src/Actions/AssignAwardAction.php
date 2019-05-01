<?php

namespace WPAward\Actions;

class AssignAwardAction extends UserPostBasedAction {
	private $award_give_name;
	private $WPAward;

	function __construct( $name, $award_give_name, \WPAward\BusinessLogic\Core $WPAward ) {
		parent::__construct( $name );
		$this->award_give_name = $award_give_name;
	}

	// Check if we have a user_id and that it is valid
	private function validate() {
		return
			isset( $_POST[$this->name] ) &&
			$_POST[$this->name] > 0;
	}

	// Want to give or assigne the award to users based on what kind of HTTP Param we're obtaining.
	private function execute_main() {
		if ( is_null( $this->user_id ) )
		{
			throw new Exception("User ID is NULL");
		}

		if ( is_null( $this->post_id ) )
		{
			throw new Exception("Post ID is NULL");
		}

		if ( isset( $_POST[$this->award_give_name] ) )
		{
			$this->WPAward->GiveAward( $_POST[$this->name], $this->post_id );
		}
		else
		{
			$this->WPAward->AssignAward( $_POST[$this->name], $this->post_id );
		}
	}
}
?>