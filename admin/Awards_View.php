<?php
	class Awards_View {
		private $submenu;

		function __construct( $submenu ) {
			$this->submenu = $submenu;
		}

		public function init() {
			add_action('admin_menu', [ $this, 'add_menu_page' ] );
		}

		function add_menu_page() {
			add_menu_page(
				'Awards Dashboard',
				'Awards',
				'administrator',
				'award-dashboard',
				array( $this->submenu, 'render' ),
				''
			);
		}
	}
?>