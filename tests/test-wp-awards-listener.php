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
class Test_WP_Awards_Listener extends WP_UnitTestCase {
	// Post type to test against.
	private $post;
	private $user;
	private $wpdb;
	private $plugin_basename;

	// Set up award posts before we run any tests.
	public function setUp() {
		parent::setUp();

		// Get an instance of the wordpress db
		global $wpdb;
		$this->wpdb = $wpdb;

		// // Activate our plugin
		// $this->plugin_basename = dirname( __DIR__ ) . '/wp_awards.php';
		// echo "Plugin Basename 2: " . $this->plugin_basename . "\n";
		// $plugin_activated = activate_plugin($this->plugin_basename);

		// if ( is_wp_error( $plugin_activated ) )
		// {
		// 	$this->fail($plugin_activated->get_error_message());
		// }

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

		// Create a user
		$this->user = $this->factory->user->create_and_get();

		// Set the named user as the current user
		wp_set_current_user( $this->user->get('ID') );
	}

	// Test whether a user who passes an award's trigger recieves an award.
	public function testSuccessfulAwardOnUpdate() {
		$posts = get_posts(['post_type' => 'wap_award']);
		$user = wp_get_current_user();

		// Update/Create before we trip the successful award update.
		$user_meta_updated = update_user_meta( $user->ID, 'total_hours', 5 );
		$awarder = new WPAward\WPAward( $this->wpdb );

		foreach( $posts as $post )
		{
			$wap_grammar = get_post_meta( $post->ID, 'wap_grammar' )[0];

			// Fail test if we do not listen correctly
			try {
				$listener = new WPAward\AwardListener( $post->ID, $wap_grammar, $awarder );
				$listener->add_listeners( $user );
			} catch ( Exception $e ) {
				$this->fail("Test Failure Occured: " . $e->getMessage() . "\nFile: " . $e->getFile() . "\nLine: " . $e->getLine() );
			}
		}

		// Listeners should be available now. Add meta to our users.
		$user_meta_updated = update_user_meta( $user->ID, 'total_hours', 60 );

		if ( ! $user_meta_updated ) {
			$this->fail("User Meta was not updated correctly");
		}

		// Check to see if our listener added our award
		$award_meta = get_user_meta( $user->ID, 'wap_fifty_hours_worked', true );

		$this->assertTrue( ! empty( $award_meta ), "Assertion that there is an award given to our user after we set up the necessary details\nCurrent award meta: " . $award_meta);

		// $this->assertTrue(true);
	}
}

?>