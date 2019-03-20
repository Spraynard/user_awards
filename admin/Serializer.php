<?php
/**
 * Class that handles interaction with the "saving" and "Updating" portion of the database, while also providing all of the needed checks to see if we have the right permissions.
 */
class Serializer {
	private $DataLayer;

	function __construct( $DataLayer ) {
		$this->DataLayer = $DataLayer;

		// add_action('admin_init', [$this, 'bootstrap_request_framework']);

		// "save_award" actions listener
		add_action('admin_post_update_award', [$this, 'handle_request']);

		// "delete_award" actions listener
		add_action('admin_post_delete_award', [$this, 'handle_request']);

		// "new_award" actions listener
		add_action('admin_post_new_award', [$this, 'handle_request']);
	}

	function bootstrap_request_framework() {

	}

	public function errorOccured() {
		$class = "notice notice-error";
		$message = __("There was an error with performing a serializing operation");

		printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), esc_html( $message ) );
	}

	public function successOccured() {
		$class = "notice notice-success id-dismissable";
		$message = __("Success!");

		printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), esc_html( $message ) );
	}

	/**
	 * Function that inserts or updates into our
	 * database, based on if we already have
	 * an ID of that name or not.
	 * @param  [type] $id [description]
	 * @return [type]     [description]
	 */
	function update( $id ) {

		if ( ! check_admin_referer( "update_award_$id" ) || empty( $id ) )
		{
			header("HTTP/1.1 401 Unauthorized");
			wp_die( 'forbidden' );
		}

		$award_title = $_POST["award_title"];
		$award_description = $_POST["award_description"];

		if ( ! $this->DataLayer->UpdateAward( $id, $award_title, $award_description ) )
		{
			return false;
		}

		return true;
	}

	function new() {
		if ( ! check_admin_referer( "new_award" ) )
		{
			header("HTTP/1.1 401 Unauthorized");
			wp_die( 'forbidden' );
		}

		$award_title = $_POST["award_title"];
		$award_description = $_POST["award_description"];

		if ( ! $this->DataLayer->InsertAward( $award_title, $award_description ) )
		{
			return false;
		}

		return true;
	}

	// Deletes an award if and only if the ID is avalid and the person has the correct permissions
	function delete( $id ) {

		// Check the nonce or if the ID is an actual ID
		if ( ! check_admin_referer( "delete_award_$id" ) || empty( $id ) )
		{
			return false;
		}

		if ( ! $this->DataLayer->DeleteAward( $id ) )
		{
			return false;
		}

		return true;
	}


	function handle_request() {
		if ( isset( $_REQUEST['award_id'] ) )
		{
			$id = $_REQUEST['award_id'];
		}

		// Action in both GET and POST requests
		$action = $_REQUEST['action'];

		// Check if the user can even give this request.
		if ( ! current_user_can('manage_options') )
		{
			header("HTTP/1.1 401 Unauthorized");
			wp_die( 'forbidden' );
		}

		switch( $action ) {
			case "update_award":
				$status = $this->update( $id );
				break;
			case "delete_award":
				$status = $this->delete( $id );
				break;
			case "new_award":
				$status = $this->new();
				break;
			default:
				echo "No given action available";
				break;
		}


		wp_redirect( admin_url('admin.php?page=award-dashboard') );
		exit;
	}
}
?>