<?php

namespace WPAward\Actions;

class UpdatePostMetaAction extends PostBasedAction {
	private $source;

	function __construct( $name, $post_id = NULL, $source = NULL ) {
		parent::__construct( $name, $post_id );
		$this->source = ( empty( $source ) ) ? $_POST : $source;
	}

	function main_execute() {
		if ( is_null( $this->post_id ) )
		{
			throw new Exception("Post ID is NULL");
		}

		update_post_meta( $this->post_id, $this->name, $source[$this->name] );
	}
}

?>