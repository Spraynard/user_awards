<?php

namespace WPAward\Actions;

class UserPostBasedAction extends Action {
	private $post_id;
	private $user_id;

	function __construct( $name, $post_id, $user_id ) {
		parent::__construct( $name );
		$this->post_id = $post_id;
		$this->user_id = $user_id;
	}
}

?>