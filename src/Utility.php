<?php

namespace WPAward;

class Utility {
	/**
	 * Displays this plugins's formatted method of username (with e-mail)
	 * output
	 * @param WP_User $user - WP_User object
	 */
	static function FormatUserDisplay( $username, $email ) {

		return "{$username} - ({$email})";
	}

	/**
	 * Function that outputs a <select> and nested <option> elements that correspond
	 * to a listing of all of the users
	 * @param string $name        - Name and ID of the <select>
	 * @param string $initialText - Text seen initially as the first <option> element
	 * @param mixed $users        - Can start off as null, but will always end up being a list of
	 *                            WP_User objects
	 */
	static function UserSelectHTML( $name, $initialText = "Select A User", $users = NULL ) {
		$users = ( empty( $users ) ) ? get_users() : $users;

		$returnHTML = <<<HTML
		<select id="{$name}" name="{$name}">
		<option value="0">{$initialText}</option>
HTML;
		foreach( $users as $user ) {
			$ID = esc_attr( $user->ID );
			$FormattedUserHTML = call_user_func(["WPAward\Utility","FormatUserDisplay"], $user);
			$returnHTML .= <<<HTML
			<option value="{$ID}">{$FormattedUserHTML}</option>
HTML;
		}

		$returnHTML .= <<<HTML
		</select>
HTML;

		return $returnHTML;
	}
}

?>