<?php
	/**
	 * This is made as an example page for wordpress only. That's why we're pretty encapsulated on to this one page script.
	 * We are also not doing any security stuff. This page should never make it to production.
	 */
	global $wp;
	$user = wp_get_current_user();

	if ( isset( $_POST['fish_in_barrel'] ) )
	{
		echo "Fish in barrel is set\n";
		update_user_meta($user->ID, 'fish_in_barrel', $_POST['fish_in_barrel']);
	}
?>
<form method="POST" action="<?php echo home_url( $wp->request ); ?>">
	<input type="hidden" name="fish_in_barrel" value="30"/>
	<span>Click this button to add a user meta value of 30 "fish_in_barrel" to your current wordpress user.</span>
	<button type="submit">Click Me</button>
</form>

<form method="POST" action="<?php echo home_url( $wp->request ); ?>">
	<input type="hidden" name="fish_in_barrel" value="0"/>
	<span>Click this button to add a user meta value of 0 "fish_in_barrel" to your current wordpress user.</span>
	<button type="submit">Click Me</button>
</form>