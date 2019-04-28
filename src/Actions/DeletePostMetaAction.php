<?php
namespace WPAward\Actions;

class DeletePostMetaAction extends PostBasedAction {
	function __construct( $name, $post_id ) {
		parent::__construct( $name, $post_id );
	}

	function validate_for_execution
	function main_execute() {
		delete_post_meta( $this->post_id, $this->name );
	}
}

?>