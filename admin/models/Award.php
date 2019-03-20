<?php
class Award {
	__construct( $id, $title, $description, $date_created, $date_modified ) {
		$this->id = $id;
		$this->title = $title;
		$this->description = $description;
		$this->date_created = $date_created;
		$this->date_modified = $date_modified;
	}
}
?>