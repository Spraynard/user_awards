<?php
/**
 * Defined constants to be used in different operation of the source.
 *
 * Should be self explanatory to what they are, but some will have comments.
 */

if ( ! defined('WP_AWARDS_DB_VERSION_KEY') )
{
	define('WP_AWARDS_DB_VERSION_KEY', 'wp_awards_version');
}

if ( ! defined('WP_AWARDS_DB_VERSION_VALUE') )
{
	define('WP_AWARDS_DB_VERSION_VALUE', '0.1');
}

if ( ! defined('WP_AWARDS_POST_TYPE') )
{
	define('WP_AWARDS_POST_TYPE', 'wp_awards_cpt');
}

if ( ! defined('WP_AWARDS_GRAMMAR_META_TYPE') )
{
	define('WP_AWARDS_GRAMMAR_META_TYPE', 'WPAward_Grammar');
}

// Table defined in our database that contains the awards that users have.
if ( ! defined('WP_AWARDS_TABLE_USER_AWARDS') )
{
	define('WP_AWARDS_TABLE_USER_AWARDS', 'user_awards');
}
?>