<?php

namespace WPAward\Actions;

class UpdatePostMetaAction extends Action {

	function __construct( $name ) {
		parent::__construct( $name );
	}

	function validateValue( $value ) {
		if ( is_null( $value ) )
		{
			// delete the value from our life
			$DeletePostMetaAction = new DeletePostMetaAction( $this->name );
			$DeletePostMetaAction()

			return false;
		}
	}

	function main_execute( $post_id ) {
		// echo "<pre>";
		// echo "Name: " . $this->name;
		// echo "Value: " . $this->value;
		// echo "Post Id: " . $post_id;
		// echo "</pre>";
		// wp_die();
		update_post_meta( $post_id, $this->name, $this->value );
	}
}

?>