<?php

namespace WPAward\Actions;

class Action implements Interfaces\IAction {
	public $name;
	public $value; // Supply a public "$value" variable that is given a value in the "validate" step of our Action.
	public $sanitization_function; // Function used to sanitize our data

	function __construct( $name ) {
		$this->name = $name;
		$this->value = NULL;
		$this->sanitization_function = 'sanitize_text_field';
	}

	protected function validateValue( $value ) {
		return ( ! is_null($value ) );
	}

	/**
	 * Function used to ensure that our action should be running or not
	 */
	private function getValue( $name ) {
		$httpVars = [
			$_GET,
			$_POST,
			$_REQUEST
		];

		$itercount = 0;

		$value = null;

		// Do our best to look through our request data and get a value in which we can perform an action on.
		do {
			$httpVar = $httpVars[$itercount]; // This variable has our $_GET, $_POST, or $_REQUEST data

			if ( isset( $httpVar[$name] ) )
			{
				$value = call_user_func($this->sanitization_function, $httpVar[$name]);
			}

			$itercount++;
		} while ( is_null($value) && $itercount < count( $httpVars ) );

		return $value;
	}

	/**
	 * Logic performed before an action execution
	 * @return [type] [description]
	 */
	protected function pre_execute() {

		$value = $this->getValue( $this->name );

		if ( ! $this->validateValue( $value ) )
		{
			return false;
		}

		$this->value = $value;

		// wp_die("Value " . $this->value);
		// Check out if this is the correct
		check_admin_referer( $this->name . "nonce_action", $this->name . "_nonce" );

		return true;
	}

	protected function main_execute( $post_id ) {
		throw new \Exception("Not Implemented");
	}

	/**
	 * Logic performed after an action execution
	 * @return [type] [description]
	 */
	protected function post_execute() {
		return;
	}

	/**
	 * [execute description]
	 * @return [type] [description]
	 */
	public function execute( $post_id ) {
		try{
			if( ! $this->pre_execute() ) {
				return;
			}
			$this->main_execute( $post_id );
			$this->post_execute();
		} catch( Exception $e ) {
			wp_die( $e );
			// error_log($e);
		}
	}
}
?>