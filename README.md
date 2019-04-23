# Wordpress Awards

Allows your membership blog or website the ability to assign and give awards to users based on the actions that they take. Let your users know how much you appreciate them!

Give them a lifetime supply of cherished memories that they can hold dear to their hearts for years to come.

One of the main goals of this plugin is to gamify experiences on your website and reward users with awards that they would appreciate based on the specific actions that they take.

### Todos
This will be added to in interations

#### Release

* Mass Assign Award on the post edit page
* Mass Give Award on the post edit page
* Singular Give on Post Edit page.Possibly just make a checkbox in the "Assign Award" meta box
* Singular give on the User Awards list page
* User Awards tab sould be above `New Award` (If this is possible...)

## Installation

Put the plugins files within your `wp-content/plugins/` folder and then activate it from the admin view as you would any regular plugin.

## Usage

### Trigger Strings

These awards are given to users in two ways. The first and easiest way is to set up a `trigger` string in the "New" or "Edit" award interface. The trigger is based on a specific grammar for our plugin that is automatically parsed and used to set up wordpress based functionality that will listen to this trigger action. An example of a `trigger` string you would use would be

`CURRENT_USER_META UPDATED WHERE key=total_hours GTEQ 50`

If this `trigger` string were applied to our award, then we would "assign" this award to our user if the WordPress specific `user_meta` value with a key of `total_hours` was updated to have a value **greater than or equal to** 50. Documentation for this grammar is located in the documentation section below.

### Global Object
There is also a `global $WPAward` object that includes the core functionality that is used within this plugin. Developers should be able to enjoy applying awards to users using their own non-trivial ways. The documentation for this object is located below

## Documentation
<!-- ### Grammar
#### Syntax
 -->
### WPAward Global Object

This object serves as the core functionality for this plugin. Supplied as a global object to allow for developers who know how to party to bypass the shortcomings that are inherent in our `Trigger Strings`.

To use, make sure to signify `global $WPAward` at the top of your file and use it as you would any other object in PHP.

```php
global $WPAward;

/**
 * Check to see if a user already has a specific award
 * @param  int $user_id  - WPUser_ID
 * @param  int $award_id - WPAward_ID (Post ID)
 * @return bool           Whether or not this user has an award with the current award id
 */
$WPAward->UserHasAward( $user_id, $award_id );

/**
 * Assigns multiple awards to users using AssignAward
 * @param  int $user_id  - WPUser_ID
 * @param  array $award_ids - Array of WPAward_IDs (Post ID)
 * @return bool             - True if awards were assigned, false if there was an error with assigning awards
 */
$WPAward->AssignAwards( $user_id, $award_ids );

/**
 * Function that marks an award as assigned to a user.
 * We insert a new record into our awards table that relates the award to the user.
 *
 * We do check to see if there is an auto-assignment of the award before we finish up our function though.
 *
 * @param int $user_id  - ID of the user that we are "awarding" the award to
 * @param int $award_id - ID of the award that we are "awarding"
 * @return bool 		- True if award was assigned,
 *                  	  False if:
 *                  	  	- User already has that award
 *                  	  	- Error with assigning our award
 */
$WPAward->AssignAward( $user_id, $award_id );

/**
 * Give multiple awards to users using GiveAward().
 * @param  int $user_id  - WPUser_ID
 * @param  array $award_ids - Array of WPAward_IDs (Post ID)
 * @return bool             - True if awards were given, false if there was an error with giving awards
 */
$WPAward->GiveAwards( $user_id, $award_ids );

/**
 * Function that will mark an award as given to a user,
 * which essentially means that we mark the "date_given" time with
 * an actual date.
 *
 * Returns the return value of a `db->update` call
 *
 * @param int $user_id  - ID of the user that we are "awarding" the award to
 * @param int $award_id - ID of the award that we are "awarding"
 * @return mixed        - Return value of a $wpdb->update() call
 */
$WPAward->GiveAward( $user_id, $award_id );

/**
 * Removes awards from our database.
 * If "$award_id" is null, then we are going to delete everything in the database with the specific "$user_id"
 *
 * @param int $user_id  - ID of the user that we are "awarding" the award to
 * @param int $award_id - ID of the award that we are "awarding"
 * @return mixed 		- Return the value of a $wpdb->delete() call
 */
$WPAward->RemoveUserAward( $user_id, $award_id = NULL );

/**
 * Function that grabs as many awards assigned to the user as we can based on the parameters given.
 * For example, if just a user_id is supplied, then we will return all of the awards with that user_id.
 * If an award_id is supplied along with our user_id then we will probably get only one award. Hopefully
 *
 * @param int $user_id  - ID of the user that we are "awarding" the award to
 * @param int $award_id - ID of the award that we are "awarding"
 * @return mixed 		- Returnes the value of a $wpdb->get_results() call
 */
$WPAward->GetUserAward( $user_id, $award_id = NULL);
```

### WordPress Award Grammar

