<?php
namespace WPAward\PluginLogic;

class RegistrationHooks {
	/** Function to handle plugin activation */
	function Activate() {
		global $wpdb;
		$wpdb_table = $wpdb->prefix . "awards";
		$wpdb_collation = $wpdb->get_charset_collate();

		$sql = "CREATE TABLE IF NOT EXISTS {$wpdb_table} (
				id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
				user_id bigint(20) unsigned NOT NULL,
				award_id bigint(20) unsigned NOT NULL,
				date_assigned datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
				date_given datetime DEFAULT NULL,
				PRIMARY KEY  (id)
			) {$wpdb_collation};";

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' ); // for dbDelta
		dbDelta( $sql );

		add_option( 'awards_db_version', "0.1" );
	}

	function Deactivate() {
		// Don't really know what I'd do for deactivation.
	}

	/** Function to handle plugin uninstallation */
	function Uninstall() {
		global $wpdb;
		$wpdb_table = $wpdb->prefix . "awards";

		$sql = "DROP TABLE IF EXISTS {$wpdb_table}";
		$wpdb->query( $sql );
		delete_option('awards_db_version');
	}
}
?>
