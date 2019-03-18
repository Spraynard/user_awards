<?php
/**
 * Class that handles interaction with the "saving" and "Updating" portion of the database, while also providing all of the needed checks to see if we have the right permissions.
 */
class Serializer {
	private $Database;

	function __construct( $Database ) {
		$this->Database = $Database;
	}

	/**
	 * Function that inserts or updates into our
	 * database, based on if we already have
	 * an ID of that name or not.
	 * @param  [type] $id [description]
	 * @return [type]     [description]
	 */
	function save( $id ) {

	}

	// Deletes an award if and only if the ID is avalid and the person has the correct permissions
	function delete( $id ) {

	}
}
?>