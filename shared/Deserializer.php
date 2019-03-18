<?php
/**
 * Class used to take data from the DB in order to display it.
 */
class Deserializer {


	private $Database;

	function __construct( $Database )
	{
		$this->Database = $Database;
	}

	function Get ( $id = NULL )
	{
		if ( empty( $id ) )
		{
			return $this->Database->GetAwards();
		}

		return $this->Database->GetAward( $id );
	}
}
?>