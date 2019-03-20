<?php
	/** Get the award id from our url string */
	$award_id = $_GET['award_id'];

	/** @var Get the award object from the database and output it as
		an associative array
	 */
	$award = $this->deserializer->get( $award_id ); // ARRAY_A
?>

<div class="wrap">
	<form action="<?php echo admin_url('admin-post.php'); ?>" method="POST">
		<!-- Hidden Input For ID -->
		<input type="hidden" name="award_id" value="<?php echo $award->id; ?>"/>

		<!-- Action Field -->
		<input type="hidden" name="action" value="update_award"/>

		<!-- Name Input -->
		<label for="award-name">Award Title</label>
		<input id="award-name" name="award_title" value="<?php echo $award->title; ?>" type="text"/><br/>

		<!-- Description Input -->
		<label for="award-description">Award Description</label>
		<textarea id="award-description" name="award_description"><?php echo $award->description; ?></textarea><br/>

		<!-- Date Created -->
		<label for="award-date-created"></label>
		<input id="award-date-created" name="date_created" value="<?php echo $award->date_created; ?>" type="text" readonly /><br/>

		<!-- Date Modified -->
		<label for="award-date-modified"></label>
		<input id="award-date-created" name="date_modified" value="<?php echo $award->date_modified; ?>" type="text" readonly /><br/>

		<!-- Submit -->
		<?php
			wp_nonce_field("update_award_{$award->id}");
			submit_button();
		?>
	</form>
</div>