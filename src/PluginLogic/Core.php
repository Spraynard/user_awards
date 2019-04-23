<?php
/**
 * Namespace: PluginLogic
 * Name: Core
 * Description: Contains our logic for providing WordPress specific logic for our plugin.
 * This includes entities such as Post Types, Meta Boxes, and the required functionality for each of these entities.
 */
namespace WPAward\PluginLogic;

class Core {
	private $post_type;
	private $WPAward; // Business Logic Layer
	private $MetaBoxes; // Meta box

	function __construct( $post_type, $WPAward = NULL, $MetaBoxes = NULL ) {
		$this->post_type = $post_type;
		$this->WPAward = $WPAward;
		$this->MetaBoxes = $MetaBoxes;

		if ( empty( $this->WPAward ) )
		{
			return new \WP_Error("Missing WPAward Dependency");
		}

		if ( empty( $this->MetaBoxes ) )
		{
			return new \WP_Error("Missing MetaBoxes Dependency");
		}

		/**
		 * Actions
		 */

		// Adds our custom post type
		add_action('init', [$this, 'PostType']);

		// Add meta boxes to post type and provide option to edit those values
		add_action('add_meta_boxes_' . $this->post_type, [$this->MetaBoxes, 'PostTypeMetaBoxes']);
		add_action('save_post', [$this->MetaBoxes, 'WPAwardsSaveMetaBoxes']); // Adds ability to save our meta values with our post

		// Adding in "User Awards" admin interface
		add_action('admin_menu', [$this, 'UserAwardsPage']);

		// Adding in Custom Modal through the "admin-notices" action
		add_action('admin_notices', [$this, 'ModalGetUser']);

		/**
		 * Filters
		 */
		// Adding in custom columns to our post type.
		add_filter('manage_' . $this->post_type . '_posts_columns', [$this, 'PostTypeAdminColumns']);

		// Adding in data for each of our columns
		add_action('manage_' . $this->post_type . '_posts_custom_column', [$this, 'PostTypeAdminColumnsData'] , 10, 2);

		// Defining BULK ACTIONS fors our custom post type edit window.
		add_filter('bulk_actions-edit-wordpress_awards', [$this, 'register_wordpress_awards_bulk_actions']);

		// Handling submission of the bulk action
		add_filter('handle_bulk_actions-wordpress_awards', [$this, 'handle_wordpress_awards_bulk_actions'], 10, 3 );

	}


	/**
	 * Creates Wordpress Post Type
	 */
	public function PostType() {
		$args = [
			'labels' => [
				'name' => 'Awards', // Name of our custom post type
				'singular_name' => 'Award', // Singular version of the name
				'add_new' => 'Add New Award',
				'add_new_item' => 'Add New Award', // Add New Page Header
				'edit_item' => 'Edit Award', // Edit Page
				'view_item' => 'View Award', // Text for viewing a single entry
				'search_items' => 'Search Awards', // Text displayed for searching
				'not_found' => 'No Awards Found', // Text displayed when no awards were found in a search
				'not_found_in_trash' => 'No Awards Found in Trash', // Test shown when awards found in trash
				'menu_name' => 'Awards'
			],
			'show_ui' => true
		];

		register_post_type($this->post_type, $args);
	}

	/**
	 * Creates User Awards Submenu Page
	 */
	public function UserAwardsPage() {
		add_submenu_page(
			'edit.php?post_type=' . $this->post_type,
			'User Awards',
			'User Awards',
			'manage_options',
			'user-awards-admin-view',
			[$this, 'UserAwardsPageHTML']
		);
	}

	/**
	 * HTML For the user awards page.
	 * This is used to display our table that shows the awards that are assigned to users.
	 */
	public function UserAwardsPageHTML() {
		$userAwardsTable = new UserAwardsTable();
		$userAwardsTable->prepare_items(); ?>
		<div class="wrap">
			<h1>User Awards</h1>
			<p>This window shows you which awards are assigned to specific users.</p>
			<p>This is also the interface where you can <em>assign your awards</em> to users by clicking on the <strong>Give To User</strong> button on the specific row of data.</p>
			<!-- Include this table inside a form if we want to enable bulk actions for the table -->
			<?php $userAwardsTable->display(); ?>
		</div>
		<?php
	}

	function register_wordpress_awards_bulk_actions( $bulk_actions ) {
		$bulk_actions['assign_to_user'] = __('Assign to User', 'assign_to_user');
		return $bulk_actions;
	}

	/**
	 * Handle any bulk actions on the EDIT page Wordpress Awards Custom Post Type View
	 * @param  string $redirect_to - URL browser will change to after we complete the bulk action.
	 * @param  [type] $action      [description]
	 * @param  [type] $post_ids    [description]
	 * @return [type]              [description]
	 */
	function handle_wordpress_awards_bulk_actions( $redirect_to, $action, $award_ids )
	{
		// Perform the other bulk actions if they are assigned.
		if ( $action !== 'assign_to_user' )
		{
			return $redirect_to;
		}

		// This should be available through a $POST variable or some such.
		$user_to_assign = $_POST['WPAward_UserID'];

		// Assign all the awards selected to that user
		$this->WPAward->AssignAwards( $user_to_assign, $award_ids );
	}

	/**
	 * Adding custom columns to post lists
	 */
	public function PostTypeAdminColumns( $columns )
	{
		$columns = [
			'cb' => $columns['cb'],
			'title' => $columns['title'],
			'trigger' => 'Trigger',
			'auto_give' => 'Auto Give',
			'date' => $columns['date']
		];

		return $columns;
	}

	public function PostTypeAdminColumnsData( $column, $post_id )
	{
		switch( $column ) {
			case 'trigger':
				$value = get_post_meta( $post_id, 'WPAward_Grammar', true);
				$value = empty($value) ? "[No Trigger Currently]" : $value;
				break;
			case 'auto_give':
				$value = empty( get_post_meta( $post_id, 'WPAward_Auto_Give', true) ) ? "No" : "Yes";
				break;
		}

		echo $value;
	}

	/**
	 * Outputs a modal that we will use to select our user
	 */
	function ModalGetUser() {
		$UserSelectHTML = call_user_func(["WPAward\Utility", "UserSelectHTML"], "WPAward_UserID");
		add_thickbox();
		echo <<<HTML
		<a id="modal-get-user-link" href="#TB_inline?width=200&height=200&inlineId=modal-get-user" style="display:none;" class="thickbox"></a>
		<div id="modal-get-user" style="display:none;">
		    <h2>Bulk Award Assign User Selection</h2>
		    <div class="admin-modal-content">
		    	{$UserSelectHTML}
		    </div>
		    <div class="admin-modal-buttons">
		    	<button class="button-primary">Choose</button>
		    	<button class="button-secondary">Cancel</button>
		    </div>
		</div>
HTML;
	}
}
?>