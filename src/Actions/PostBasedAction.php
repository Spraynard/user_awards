<?php

namespace WPAward\Actions;

class PostBasedAction extends Action {
	private $post_id;

	function __construct( $name, $post_id ) {
		parent::__construct( $name );
		$this->post_id = $post_id;
	}
}