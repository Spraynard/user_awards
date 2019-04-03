<?php
/**
 * Class WPAwardsTest
 *
 * @package  Wp_awards
 */

/**
 * Test the base functionality of our plugin.
 * We should be making sure all of the basic
 * functionality needed for this plugin
 * is supported.
 */
class WPAwardsTest extends WP_UnitTestCase {
	// Post type to test against.
	private $post;

	// Set up award posts before we run any tests.
	public function setUp() {
		parent::setUp();

		// Testing that we have our custom post type
		$this->assertTrue(post_type_exists('wap_award'));

		// Assigning to our post variable.
		$this->post = $this->factory->post->create_and_get(array(
			'post_type' => 'wap_award',
			'post_title' => 'Fifty Hours Worked',
			'post_content' => 'Awarded to users if they have more than 50 hours worked for us. They are really nice people',
			'post_author' => 1,
			'meta_input' => array(
				'wap_grammar' => "CURRENT_USER_META UPDATED WHERE key=total_hours GTEQ 50"
			)
		));
	}

	public function testNumberOne() {

		print_r( $this->post );
		$this->assertTrue(true);
	}
}

?>