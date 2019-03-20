<?php
/**
 * Class that registers a custom post type of "Award",
 * which will give us the interface of defining an award, as well as setting up
 * the triggering and updating aspect of it.
 */
class Award_Type {
	function __construct() {
		add_action('init', [ $this, 'add_post_type' ]);
	}

	function add_post_type() {
		$args = array(
			'public' => false,
			'label' => __('Awards'),
			'labels' => array(
				'name' => __('Awards'),
				'singular_name' => __('Award'),
				'description' => __('Awards to give to your volunteers'),)
		);
		register_post_type('award', $args);
	}
}
?>