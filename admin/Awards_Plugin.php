<?php
class Awards_Plugin {
	private $awards_db_version = '0.1';

	function __construct() {

		/** Insert Awards Plugin admin menu when
		we show the admin page */
		// add_action('admin_menu', array( $this, 'awards_admin_menu' ) );
	}

	/** Path to the main awards dashboard page */
	static function awards_dashboard_page_path() {
		if ( ! is_admin() ) {
			wp_die( __('You must be an admin to visit this page'));
		}

		$action = $_GET['action'];
		$include_file = "";

		switch ( $action ) {
			case 'edit':
				$include_file = "award_edit.php";
				break;
			default:
				$include_file = "dashboard.php";
				break;
		}

		include_once(dirname(__FILE__) . "/admin/$include_file");
	}

	/** Path to the awards options */
	static function awards_options_page_path() {
		if ( ! is_admin() ) {
			wp_die( __('You must be an admin to visit this page'));
		}
		include_once(dirname(__FILE__) . '/admin/options.php');
	}

	/** Handles the administrator menu for our plugin*/
	function awards_admin_menu() {
		/**
		 * add_menu_page( $page_title, $menu_title, $capability
		 * 					$menu_slug, $function, $icon_url,
		 * 					$position
		 * );
		 */
		add_menu_page(
			'Awards Options',
			'Awards',
			'administrator',
			'awards',
			array( __CLASS__, 'awards_dashboard_page_path'),
			''
		);

		add_submenu_page(
			'awards',
			'Awards Options',
			'Options',
			'administrator',
			'awards-options',
			array( __CLASS__, 'awards_options_page_path')
		);
	}
}
?>