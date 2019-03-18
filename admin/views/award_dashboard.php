<div class="wrap">
	<h1>Awards Dashboard</h1>
	<table class="awards-table">
		<thead>
			<tr>
				<th>Award ID</th>
				<th>Award Title</th>
				<th>Award Description</th>
			</tr>
		</thead>
		<tbody>
	<?php
		foreach( $this->deserializer->get() as $award ) {
			?>
			<tr class="award-row">
				<td class="award-id"><?php echo $award->id; ?></td>
				<td class="award-title">
					<a class="award-title" href="<?php echo admin_url("admin.php?page=award-dashboard&section=award-edit&award_id=$award->id"); ?>"><?php echo $award->title; ?></a>
					<div class="award-actions">
						<span class="award-action award-edit"><a href="<?php echo admin_url("admin.php?page=award-dashboard&section=award-edit&award_id=$award->id"); ?>">Edit</a></span>
						<span class="award-action award-delete"><a href="<?php echo admin_url("admin.php?page=awards&action=delete_award&award_id=$award->id"); ?>">Delete</a></span>
					</div>
				</td>
				<td><?php echo $award->description; ?></td>
			</tr>
			<?php
		}
	?>
		</tbody>
	</table>
</div>