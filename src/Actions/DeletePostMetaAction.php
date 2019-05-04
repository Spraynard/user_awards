<?php
namespace WPAward\Actions;

class DeletePostMetaAction extends Action {
	function __construct( $name ) {
		parent::__construct( $name );
	}

	function main_execute( $post_id ) {
		delete_post_meta( $post_id, $this->name );
	}
}

?>