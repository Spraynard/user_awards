<?php
/**
 * Defined constants to be used in different operation of the source.
 *
 * Should be self explanatory to what they are, but some will have comments.
 */

if ( ! defined('USER_AWARDS_DB_VERSION_KEY') )
{
	define('USER_AWARDS_DB_VERSION_KEY', 'user_awards_version');
}

if ( ! defined('USER_AWARDS_DB_VERSION_VALUE') )
{
	define('USER_AWARDS_DB_VERSION_VALUE', '0.1');
}

if ( ! defined('USER_AWARDS_POST_TYPE') )
{
	define('USER_AWARDS_POST_TYPE', 'user_awards_cpt');
}

if ( ! defined('USER_AWARDS_GRAMMAR_META_TYPE') )
{
	define('USER_AWARDS_GRAMMAR_META_TYPE', 'UserAwards_Grammar');
}

if ( ! defined('USER_AWARDS_AUTO_GIVE_TYPE') )
{
	define('USER_AWARDS_AUTO_GIVE_TYPE', 'UserAwards_Auto_Give');
}

// Table defined in our database that contains the awards that users have.
if ( ! defined('USER_AWARDS_TABLE_USER_AWARDS') )
{
	define('USER_AWARDS_TABLE_USER_AWARDS', 'user_awards');
}
?>