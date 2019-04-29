<?php

namespace WPAward\Actions;

class AssignAwardAction extends UserPostBasedAction {
	private $award_give_name;

	function __construct( $name, $post_id, $user_id, $award_give_name ) {
		parent::__construct( $name, $post_id, $user_id );
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