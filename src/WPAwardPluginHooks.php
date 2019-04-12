<?php

namespace WPAward;

class WPAwardPluginHooks {
	private $wpdb;
	private $db_version = "0.1";

	function __construct() {
		global $wpdb;
		$this->wpdb = $wpdb;
		$this->db_table = $this->wpdb->prefix . "awards";
		$this->db_collation = $this->wpdb->get_charset_collate();
	}

	/** Function to handle plugin activation */
	function Activate() {
		$sql = "CREATE TABLE IF NOT EXISTS {$this->db_table} (
				id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
				user_id bigint(20) unsigned NOT NULL,
				award_id bigint(20) unsigned NOT NULL,
				date_assigned datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
				date_given datetime DEFAULT NULL,
				PRIMARY KEY  (id)
			) {$this->db_collation};";

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' ); // for dbDelta
		dbDelta( $sql );

		add_option( 'awards_db_version', $this->db_version );
	}

	function Deactivate() {
		// Don't really know what I'd do for deactivation.
	}

	/** Function to handle plugin uninstallation */
	function Uninstall() {
		$sql = "DROP TABLE IF EXISTS {$this->db_table}";
		$this->db->query( $sql );
		delete_option('awards_db_version');
	}
}
?>