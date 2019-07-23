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
class Test_Listener_Assigned_Meta extends WP_UnitTestCase {
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

		if ( ! defined('USER_AWARDS_POST_TYPE') )
		{
			$this->fail("USER_AWARDS_POST_TYPE CONSTANT IS NOT AVAILABLE");
		}

		$this->post = $this->factory->post->create_and_get(array(
			'post_type' => USER_AWARDS_POST_TYPE,
			'post_title' => '20 Hours Worked',
			'post_content' => 'Awarded to users if they have more than 20 hours worked for us. They are really nice people',
			'post_author' => 1,
			'meta_input' => array(
				USER_AWARDS_GRAMMAR_META_TYPE => "CURRENT_USER_META ASSIGNED WHERE total_hours GTEQ 20"
			)
		));

		$this->post_2 = $this->factory->post->create_and_get(array(
			'post_type' => USER_AWARDS_POST_TYPE,
			'post_title' => 'Zero Fish Available',
			'post_content' => 'Awarded to users if they have no fish in their bucket',
			'post_author' => 1,
			'meta_input' => array(
				USER_AWARDS_GRAMMAR_META_TYPE => "CURRENT_USER_META ASSIGNED WHERE fish_in_bucket eq 0"
			)
		));

		$this->post_3 = $this->factory->post->create_and_get(array(
			'post_type' => USER_AWARDS_POST_TYPE,
			'post_title' => 'Zero Fish Available',
			'post_content' => 'Awarded to users if they have no fish in their bucket',
			'post_author' => 1,
			'meta_input' => array(
				USER_AWARDS_GRAMMAR_META_TYPE => "CURRENT_USER_META ASSIGNED WHERE fish_in_bucket eq 10"
			)
		));


		// Create a user
		$this->user = $this->factory->user->create_and_get();

		// Set the named user as the current user
		wp_set_current_user( $this->user->get('ID') );
	}

	// Creating meta on a user that satisfies award requirements should award people.
	public function testSuccessfulAwardOnCreateAssignedTriggerType() {
		$post = $this->post;
		$user = wp_get_current_user();

		$UserAwards = new UserAwards\BusinessLogic\Core( $this->wpdb );
		$Grammar = new UserAwards\Grammar\Core();

		$UserAwards_Grammar = get_post_meta( $post->ID, USER_AWARDS_GRAMMAR_META_TYPE, true);
		$Grammar->parse($UserAwards_Grammar);

		// Fail test if we do not listen correctly
		try {
			$listener = new UserAwards\Listener\Core( $post->ID, $Grammar, $UserAwards );
			$listener->add_listeners( $user );
		} catch ( Exception $e ) {
			$this->fail("Test Failure Occured: " . $e->getMessage() . "\nFile: " . $e->getFile() . "\nLine: " . $e->getLine() );
		}

		// Listeners should be available now. Add meta to our users.
		$user_meta_updated = add_user_meta( $user->ID, $Grammar->trigger->descriptor, 20 );

		if ( ! $user_meta_updated ) {
			$this->fail("User Meta was not updated correctly");
		}

		// Check to see if our listener assigned an award to this user
		$award_data = $UserAwards->GetUserAward( $user->ID );

		$this->assertNotEmpty($award_data, "Should have an award assigned to our user, but our data does not show as such.");

	}

	// Updating meta on a user to where we satisfy award requirements should award people.
	public function testSuccessfulAwardOnUpdateAssignedTriggerType() {
		$post = $this->post;
		$user = wp_get_current_user();


		$UserAwards = new UserAwards\BusinessLogic\Core( $this->wpdb );
		$Grammar = new UserAwards\Grammar\Core();

		$UserAwards_Grammar = get_post_meta( $post->ID, USER_AWARDS_GRAMMAR_META_TYPE, true);
		$Grammar->parse($UserAwards_Grammar);

		// Update our user meta to a certain point
		$user_meta_added = add_user_meta( $user->ID, $Grammar->trigger->descriptor, 5, false );

		if ( ! $user_meta_added )
		{
			$this->fail("Test Failure Occured: " . $e->getMessage() . "\nFile: " . $e->getFile() . "\nLine: " . $e->getLine() );
		}

		// Fail test if we do not listen correctly
		try {
			$listener = new UserAwards\Listener\Core( $post->ID, $Grammar, $UserAwards );
			$listener->add_listeners( $user );
		} catch ( Exception $e ) {
			$this->fail("Test Failure Occured: " . $e->getMessage() . "\nFile: " . $e->getFile() . "\nLine: " . $e->getLine() );
		}

		// Listeners should be available now. Add meta to our users.
		$user_meta_updated = update_user_meta( $user->ID, $Grammar->trigger->descriptor, 20 );

		if ( ! $user_meta_updated ) {
			$this->fail("User Meta was not updated correctly");
		}

		// Check to see if our listener assigned an award to this user
		$award_data = $UserAwards->GetUserAward( $user->ID );

		$this->assertNotEmpty($award_data, "Should have an award assigned to our user, but our data does not show as such.");
	}

	// Passing of two tests above should allow us to create/update an award on a user.
	// An award requirement of when a user needs to have *zero* of something should still pass this test.
	public function testSuccessfulAwardWhenUserMetaIsZero() {
		$post = $this->post_2;
		$user = wp_get_current_user();


		$UserAwards = new UserAwards\BusinessLogic\Core( $this->wpdb );
		$Grammar = new UserAwards\Grammar\Core();

		$UserAwards_Grammar = get_post_meta( $post->ID, USER_AWARDS_GRAMMAR_META_TYPE, true);
		$Grammar->parse($UserAwards_Grammar);

		// Update our user meta to a certain point
		$user_meta_added = add_user_meta( $user->ID, $Grammar->trigger->descriptor, 5, false );

		if ( ! $user_meta_added )
		{
			$this->fail("Test Failure Occured: " . $e->getMessage() . "\nFile: " . $e->getFile() . "\nLine: " . $e->getLine() );
		}

		// Fail test if we do not listen correctly
		try {
			$listener = new UserAwards\Listener\Core( $post->ID, $Grammar, $UserAwards );
			$listener->add_listeners( $user );
		} catch ( Exception $e ) {
			$this->fail("Test Failure Occured: " . $e->getMessage() . "\nFile: " . $e->getFile() . "\nLine: " . $e->getLine() );
		}

		// Listeners should be available now. Add meta to our users.
		$user_meta_updated = update_user_meta( $user->ID, $Grammar->trigger->descriptor, 0 );

		if ( ! $user_meta_updated ) {
			$this->fail("User Meta was not updated correctly");
		}

		// Check to see if our listener assigned an award to this user
		$award_data = $UserAwards->GetUserAward( $user->ID );

		$this->assertNotEmpty($award_data, "Should have an award assigned to our user, but our data does not show as such.");
	}

	// Passing of two tests above should allow us to create/update an award on a user.
	// An award requirement of when a user needs to have *zero* of something should still pass this test.
	public function testSuccessfulAwardWhenUserMetaIsZeroAtStart() {
		$post = $this->post_2;
		$user = wp_get_current_user();


		$UserAwards = new UserAwards\BusinessLogic\Core( $this->wpdb );
		$Grammar = new UserAwards\Grammar\Core();

		$UserAwards_Grammar = get_post_meta( $post->ID, USER_AWARDS_GRAMMAR_META_TYPE, true);
		$Grammar->parse($UserAwards_Grammar);



		// Fail test if we do not listen correctly
		try {
			$listener = new UserAwards\Listener\Core( $post->ID, $Grammar, $UserAwards );
			$listener->add_listeners( $user );
		} catch ( Exception $e ) {
			$this->fail("Test Failure Occured: " . $e->getMessage() . "\nFile: " . $e->getFile() . "\nLine: " . $e->getLine() );
		}

		// Listeners should be available now. Add meta to our users.
		$user_meta_added = add_user_meta( $user->ID, $Grammar->trigger->descriptor, 0, false );

		if ( ! $user_meta_added )
		{
			$this->fail("Test Failure Occured: " . $e->getMessage() . "\nFile: " . $e->getFile() . "\nLine: " . $e->getLine() );
		}

		// Check to see if our listener assigned an award to this user
		$award_data = $UserAwards->GetUserAward( $user->ID );

		$this->assertNotEmpty($award_data, "Should have an award assigned to our user, but our data does not show as such.");
	}

	public function testSuccessfulAwardsMultipleListeners() {
		$post = $this->post_2;
		$post_2 = $this->post;
		$post_3 = $this->post_3;

		$user = wp_get_current_user();


		$UserAwards = new UserAwards\BusinessLogic\Core( $this->wpdb );
		$Grammar = new UserAwards\Grammar\Core();
		$Grammar2 = new UserAwards\Grammar\Core();
		$Grammar3 = new UserAwards\Grammar\Core();

		$UserAwards_Grammar = get_post_meta( $post->ID, USER_AWARDS_GRAMMAR_META_TYPE, true);
		$Grammar->parse($UserAwards_Grammar);
		$UserAwards_2_Grammar = get_post_meta( $post_2->ID, USER_AWARDS_GRAMMAR_META_TYPE, true);
		$Grammar2->parse($UserAwards_2_Grammar);
		$UserAwards_3_Grammar = get_post_meta( $post_3->ID, USER_AWARDS_GRAMMAR_META_TYPE, true);
		$Grammar3->parse($UserAwards_3_Grammar);

		// Update our user meta to a certain point
		$user_meta_added = add_user_meta( $user->ID, $Grammar->trigger->descriptor, 5, false );
		$user_meta_2_added = add_user_meta( $user->ID, $Grammar2->trigger->descriptor, 5, false );
		$user_meta_3_added = add_user_meta( $user->ID, $Grammar3->trigger->descriptor, 5, false );

		if ( ! $user_meta_added )
		{
			$this->fail("Test Failure Occured: " . $e->getMessage() . "\nFile: " . $e->getFile() . "\nLine: " . $e->getLine() );
		}

		// Fail test if we do not listen correctly
		try {
			$awards = get_posts([ 'post_type' => USER_AWARDS_POST_TYPE ]);


			foreach ( $awards as $award ) {
				$loop_grammar = new UserAwards\Grammar\Core();
				$listener = null;

				$award_grammar = get_post_meta( $award->ID, USER_AWARDS_GRAMMAR_META_TYPE, true );

				// The parse function can throw errors, so wrap it in a try block
				try
				{
					$loop_grammar->parse( $award_grammar );
				}
				catch( Exception $e )
				{
					continue;
				}

				// Apply our listener. Set it and forget it. Include a parsed grammar and inject the UserAwards dependency
				$listener = new UserAwards\Listener\Core( $award->ID, $loop_grammar, $UserAwards );
				$listener->add_listeners( $user );
			}
		} catch ( Exception $e ) {
			$this->fail("Test Failure Occured: " . $e->getMessage() . "\nFile: " . $e->getFile() . "\nLine: " . $e->getLine() );
		}

		// Listeners should be available now. Add meta to our users.
		$user_meta_updated = update_user_meta( $user->ID, $Grammar->trigger->descriptor, 0 );
		$user_meta_2_updated = update_user_meta( $user->ID, $Grammar2->trigger->descriptor, 30 );
		$user_meta_3_updated = update_user_meta( $user->ID, $Grammar3->trigger->descriptor, 10 );

		// Check to see if our listener assigned an award to this user
		$award_data = $UserAwards->GetUserAward( $user->ID );

		$this->assertNotEmpty($award_data, "Should have an award assigned to our user, but our data does not show as such.");
		$this->assertEquals(count($award_data), 3);
	}
}
?>