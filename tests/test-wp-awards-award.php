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
class Test_WP_Awards extends WP_UnitTestCase {
	// Post type to test against.
	private $post;
	private $post_2;
	private $user;
	private $wpdb;
	private $WPAward;
	private $plugin_basename;


	public function tearDown() {
		parent::tearDown();
		global $wpdb;

		$tablename = $wpdb->prefix . "awards";

		$wpdb->query( "TRUNCATE TABLE {$tablename}" );
	}

	// Set up award posts before we run any tests.
	public function setUp() {
		parent::setUp();

		// Get an instance of the wordpress db
		global $wpdb;
		$this->wpdb = $wpdb;

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

		$this->post_2 = $this->factory->post->create_and_get(array(
			'post_type' => 'wap_award',
			'post_title' => '60 Hours Worked',
			'post_content' => 'Awarded to users if they have more than 60 hours worked for us. They are really nice people',
			'post_author' => 1,
			'meta_input' => array(
				'wap_grammar' => "CURRENT_USER_META UPDATED WHERE key=total_hours GTEQ 60"
			)
		));

		// Create a user˝
		$this->user = $this->factory->user->create_and_get();

		// Set the named user as the current user
		wp_set_current_user( $this->user->get('ID') );

		$this->WPAward = new WPAward\WPAward( $this->wpdb );
	}

	// Should return empty with $user_id supplied
	public function testGetUserAwardNoAssignmentsOne()
	{
		$this->assertTrue(empty($this->WPAward->GetUserAward( $this->user->Get('ID') )));
	}

	// Should Return Empty with $user_id and $award_id supplied
	public function testGetUserAwardNoAssignmentsBoth()
	{
		$this->assertTrue( empty($this->WPAward->GetUserAward( $this->user->ID, $this->post->ID )));
	}

	// Basic award assignment test
	public function testAssignAward() {
		$this->assertTrue($this->WPAward->AssignAward($this->user->ID, $this->post->ID));
	}

	// Basic award assignment test
	public function testAssignAwardInitialStructure() {
		// Link a user to an award
		$assigned = $this->WPAward->AssignAward($this->user->ID, $this->post->ID);

		if ( ! $assigned )
		{
			$this->fail("Award not assigned");
		}

		// Get the data of the given award
		$award_data = $this->WPAward->GetUserAward( $this->user->ID, $this->post->ID );

		// Check that there isn't a null date in the "date_given" field.
		$this->assertNull($award_data[0]->date_given);
	}

	// Testing the the return type of getUserAward is an array.
	public function testGetAwardReturnType() {
		// Link a user to an award
		$assigned = $this->WPAward->AssignAward($this->user->ID, $this->post->ID);

		if ( ! $assigned )
		{
			$this->fail("Award not assigned");
		}

		$wp_award = $this->WPAward->GetUserAward($this->user->ID, $this->post->id);

		// Get that award from the DB
		$this->assertTrue( gettype( $wp_award ) === "array" );
	}

	/**
	 * Test that we return a singular item from
	 * the database even though we have multiple items assigned to the same user
	 */
	public function testGetAwardReturnSingular() {
		// Link a user to an award
		$assigned = $this->WPAward->AssignAward($this->user->ID, $this->post->ID);

		$assigned_2 = $this->WPAward->AssignAward($this->user->ID, $this->post_2->ID);

		if ( ! $assigned || ! $assigned_2 )
		{
			$this->fail("Award not assigned");
		}

		$user_award = $this->WPAward->GetUserAward( $this->user->ID, $this->post->ID);

		// Get that award from the DB
		$this->assertGreaterThan(0, count( $user_award) );
	}

	/**
	 * Test that multiple items are returned from GetUserAward when we don't supply an award_id
	 */
	public function testGetAwardReturnMultiple()
	{
		// Link a user to an award
		$assigned = $this->WPAward->AssignAward($this->user->ID, $this->post->ID);

		$assigned_2 = $this->WPAward->AssignAward($this->user->ID, $this->post_2->ID);

		if ( ! $assigned )
		{
			$this->fail("Award not assigned");
		}

		$user_award = $this->WPAward->GetUserAward( $this->user->ID);

		// Get that award from the DB
		$this->assertTrue( count( $user_award ) === 2 );
	}

	/**
	 * Test that an award has been successfully given
	 * to a user.
	 */
	public function testGiveAward()
	{
		// Link a user to an award
		$assigned = $this->WPAward->AssignAward($this->user->ID, $this->post->ID);

		if ( ! $assigned )
		{
			$this->fail("Award not assigned");
		}

		// "Give" the award to user.
		$award_given = $this->WPAward->GiveAward( $this->user->ID, $this->post->ID);

		// Get the data of the given award
		$award_data = $this->WPAward->GetUserAward( $this->user->ID, $this->post->ID );

		// Check that there isn't a null date in the "date_given" field.
		$this->assertNotNull($award_data[0]->date_given);
	}

	/**
	 * If we "give" an award and it doesn't exist, then
	 * the award should be assigned and given.
	 */
	public function testGiveAwardNoAssign()
	{
		// "Give" the award to user.
		$award_given = $this->WPAward->GiveAward( $this->user->ID, $this->post->ID);

		// Get the data of the given award
		$award_data = $this->WPAward->GetUserAward( $this->user->ID, $this->post->ID );

		// Check that there isn't a null date in the "date_given" field.
		$this->assertNotNull($award_data[0]->date_given);
	}


	public function testAutoGiveAwardOnAssign()
	{
		add_post_meta( $this->post->ID, 'wap_auto_give', "yes", false );

		// Link a user to an award. Since the posts 'wap_auto_give' is true, then we should automatically give the award out.
		$assigned = $this->WPAward->AssignAward($this->user->ID, $this->post->ID);

		if ( ! $assigned )
		{
			$this->fail("Award not assigned");
		}

		// "Give" the award to user.
		$award_given = $this->WPAward->GiveAward( $this->user->ID, $this->post->ID);

		// Get the data of the given award
		$award_data = $this->WPAward->GetUserAward( $this->user->ID, $this->post->ID );

		// Check that there isn't a null date in the "date_given" field.
		$this->assertNotNull($award_data[0]->date_given);
	}

	/**
	 * Test that an award is removed based on a return
	 * value from the database ( coming from GetUserAward )
	 */
	public function testRemoveAward()
	{
		// Link a user to an award. Since the posts 'wap_auto_give' is true, then we should automatically give the award out.
		$assigned = $this->WPAward->AssignAward($this->user->ID, $this->post->ID);

		if ( ! $assigned )
		{
			$this->fail("Award not assigned");
		}

		$this->WPAward->RemoveAward( $this->user->ID, $this->post->ID );

		// Get the data of the given award
		$award_data = $this->WPAward->GetUserAward( $this->user->ID, $this->post->ID );

		// Check that there isn't a null date in the "date_given" field.
		$this->assertEmpty( $award_data );
	}

	/**
	 * Testing return value of our remove award function, which should be the amount of rows that are deleted.
	 */
	public function testRemoveAwardReturn()
	{
		// Link a user to an award. Since the posts 'wap_auto_give' is true, then we should automatically give the award out.
		$assigned = $this->WPAward->AssignAward($this->user->ID, $this->post->ID);

		if ( ! $assigned )
		{
			$this->fail("Award not assigned");
		}

		$award_deleted = $this->WPAward->RemoveAward( $this->user->ID, $this->post->ID );

		$this->assertGreaterThan( 0, $award_deleted );
	}

	/**
	 * Testing that we can delete multiple awards from a user if we just supply only a user ID into our function
	 */
	public function testRemoveAwardMultiple()
	{
		// Link a user to an award
		$assigned = $this->WPAward->AssignAward($this->user->ID, $this->post->ID);

		$assigned_2 = $this->WPAward->AssignAward($this->user->ID, $this->post_2->ID);

		if ( ! $assigned || ! $assigned_2 )
		{
			$this->fail("Award not assigned");
		}

		$award_deleted = $this->WPAward->RemoveAward( $this->user->ID );

		$this->assertGreaterThan( 1, $award_deleted );
	}

	/** Test Expected Behavior of $val_1 > $val_2 when $val_1 > $val_2 */
	public function testShouldApplyAwardGTSuccess()
	{
		$this->assertTrue(
			$this->WPAward->ShouldApplyAward(2, 1, 'gt')
		);
	}

	/** Test Expected Behavior of $val_1 > $val_2 when val_1 < val_2 */
	public function testShouldApplyAwardGTFailure()
	{
		$this->assertFalse(
			$this->WPAward->ShouldApplyAward(1, 2, 'gt')
		);
	}

	public function testShouldApplyAwardGTFailureEqual()
	{
		$this->assertFalse(
			$this->WPAward->ShouldApplyAward(1, 1, 'gt')
		);
	}

	public function testShouldApplyAwardLTSuccess()
	{
		$this->assertTrue(
			$this->WPAward->ShouldApplyAward(1, 2, 'lt')
		);
	}

	public function testShouldApplyAwardLTFailure()
	{
		$this->assertFalse(
			$this->WPAward->ShouldApplyAward(2, 1, 'lt')
		);
	}

	public function testShouldApplyAwardLTFailureEqual()
	{
		$this->assertFalse(
			$this->WPAward->ShouldApplyAward(2, 2, 'lt')
		);
	}

	public function testShouldApplyAwardEQSuccess()
	{
		$this->assertTrue(
			$this->WPAward->ShouldApplyAward(50, 50, 'eq')
		);
	}

	public function testShouldApplyAwardEQSuccessStr()
	{
		$this->assertTrue(
			$this->WPAward->ShouldApplyAward("Hello", "Hello", 'eq')
		);
	}

	public function testShouldApplyAwardEQFailure()
	{
		$this->assertFalse(
			$this->WPAward->ShouldApplyAward(1, 50, 'eq')
		);
	}

	public function testShouldApplyAwardGTEQSuccess()
	{
		$this->assertTrue(
			$this->WPAward->ShouldApplyAward(20, 2, 'gteq')
		);
	}

	public function testShouldApplyAwardGTEQSuccessEqual()
	{
		$this->assertTrue(
			$this->WPAward->ShouldApplyAward(20, 20, 'gteq')
		);
	}

	public function testShouldApplyAwardGTEQFailure()
	{
		$this->assertFalse(
			$this->WPAward->ShouldApplyAward(1, 50, 'gteq')
		);
	}

	public function testShouldApplyAwardLTEQSuccess()
	{
		$this->assertTrue(
			$this->WPAward->ShouldApplyAward(10, 20, 'lteq')
		);
	}

	public function testShouldApplyAwardLTEQSuccessEqual()
	{
		$this->assertTrue(
			$this->WPAward->ShouldApplyAward(20, 20, 'lteq')
		);
	}

	public function testShouldApplyAwardLTEQFailure()
	{
		$this->assertFalse(
			$this->WPAward->ShouldApplyAward(50, 1, 'lteq')
		);
	}
}

?>