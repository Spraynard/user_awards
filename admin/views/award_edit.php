<?php
	/** Get the award id from our url string */
	$award_id = $_GET['award_id'];

	/** @var Get the award object from the database and output it as
		an associative array
	 */
	$award = $this->db->getAward( $award_id, ARRAY_A );
?>

<div class="wrap">
	<form action="">
		<?php
			foreach( $award as $award_item_type => $award_item_value ) {
				?>
				<div class="award-entry">
					<?php
					// Hidden Inputs
					$award_item_id = $award_item_type . "-input";
					if ( $award_item_type === "id" )
					{
						echo <<<HTML
						<input type="hidden" name="{$award_item_type}" value="{$award_item_value}"/>
						HTML;
					}
					// Readonly on the date blocks
					else if ( $award_item_type === "date_created" || $award_item_type === "date_modified" )
					{
						echo <<<HTML
						<label for="{$award_item_id}">{$award_item_type}</label>
						<input id="{$award_item_id}" name="{$award_item_type}" type="text" value="{$award_item_value}" readonly/>
						HTML;
					}
					// Textbox
					else if ( $award_item_type == "description" )
					{
						echo <<<HTML
						<label for="{$award_item_id}">{$award_item_type}</label>
						<textarea id="{$award_item_id}" name="{$award_item_type}">{$award_item_value}</textarea>
						HTML;
					}
					// General Input
					else
					{
						echo <<<HTML
						<label for="{$award_item_id}">{$award_item_type}</label>
						<input id="{$award_item_id}" name="{$award_item_type}" type="text" value="{$award_item_value}"/>
						HTML;
					}
					?>
				</div>
			<?php
			}
			submit_button();
		?>
	</form>
</div>