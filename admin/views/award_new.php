<!-- View for when we are creating a new award -->
<div class="wrap">
	<form action="<?php echo admin_url('admin-post.php'); ?>" method="POST">
		<!-- Hidden Input for Action -->
		<input type="hidden" name="action" value="new_award"/>

		<!-- Name Input -->
		<label for="award-title">Award Title</label><br/>
		<input id="award-title" name="award_title" value="" type="text"/><br/>

		<!-- Description Input -->
		<label for="award-description">Award Description</label><br/>
		<textarea id="award-description" name="award_description"></textarea><br/>

		<!-- Submit -->
		<?php
			wp_nonce_field("new_award");
			submit_button(); ?>
	</form>
</div>