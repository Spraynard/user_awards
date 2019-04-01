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

	// Set up award posts before we run any tests.
	public function setUp() {
		$postarrfiftyhours = array(
			'post_type' => 'wap_award',
			'post_title' => 'Fifty Hours Worked',
			'post_content' => 'Awarded to users if they have more than 50 hours worked for us. They are really nice people',
			'post_author' => 'Test Admin',
			'meta_input' => array(
				'wap_grammar' => "CURRENT_USER_META UPDATED WHERE key=total_hours GTEQ 50"
			)
		);

		$setupArray = array(
			$postarrfiftyhours
		);

		foreach( $setupArray as $postArray )
		{
			echo "wp_insert_post here:\n";
			print_r( $postArray );
			$post_id = wp_insert_post( $postArray );

			if ( $post_id )
			{
				echo "We have successfully created a post with POST_ID: {$post_id}";
			}
			else
			{
				echo "There was a problem creating a post";
			}
		}
	}


	public function testNumberOne() {
		$post_type_args = array(
			'post_type' => 'wap_award'
		);

		$wap_awards_posts = get_posts();

		echo "These are our posts\n";
		print_r( $wap_awards_posts );
		$this->assertTrue(true);
	}
}

?>