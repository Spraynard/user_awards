/**
 * Name: wp_award_page_edit_scripts.js
 * Description: Holds all applicable JavaScripts needed for full functionality of the WP_Awards Post Edit page.
 */

function injectUserIDHiddenInput( user_id, selector ) {
	inputHTML = '<input type="hidden" value="' + user_id + '"/>';
	$(selector).append(inputHTML)
}

jQuery(document).ready(function($) {
	/**
	 * WPAwards Post Edit List screen:
	 * - Bulk Actions:
	 * 		* If the bulk action that we're doing is "Assign to User",
	 *   		then we have to harvest the user information somehow.
	 *
	 *   		We will do this by opening up a modal that displays all
	 *   		of the current website members. The user chooses a member,
	 *   		and the ID of that member is then inserted as a hidden
	 *   		input into our whole edit form here.
	 */

	var modal_get_user = $("#modal-get-user");
	var modal_get_user_submit = modal_get_user.find("button-primary");
	var modal_get_user_cancel = modal_get_user.find("button-secondary");

	modal_get_user_submit.click(function() {
		// injectUserIDHiddenInput(
	});

	modal_get_user_cancel.click(function() {

	})

	var bulk_actions_submit = $(".bulkactions input[type=\"submit\"]");
	var modal_get_user_link = $("#modal-get-user-link");

	// Init TB on these here modal windows

	bulk_actions_submit.click(function(e) {
		var bulk_actions_dropdown = $(this).prev("select");

		// Carry on as usual
		if ( bulk_actions_dropdown.val() !== "assign_to_user" )
		{
			return
		}

		// Prevent Submission
		e.preventDefault();

		// Ugh, so tired... Insert HTML into the body and then perform actions
		modal_get_user_link.click()

		// Gain User info or a cancel process occured

		// Set user info into form

	});
});