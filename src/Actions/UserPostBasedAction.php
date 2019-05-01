<?php

namespace WPAward\Actions;

class UserPostBasedAction extends Action {
	private $post_id;
	private $user_id;

	function __construct( $name ) {
		parent::__construct( $name );
		$this->post_id = NULL;
		$this->user_id = NULL;
	}
}

?>