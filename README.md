# Wordpress Awards

Allows your membership blog or website the ability to assign and give awards to users based on the actions that they take. Let your users know how much you appreciate them!

Give them a lifetime supply of cherished memories that they can hold dear to their hearts for years to come.

One of the main goals of this plugin is to gamify experiences on your website and reward users with awards that they would appreciate based on the specific actions that they take.

## Installation

Put the plugins files within your `wp-content/plugins/` folder and then activate it from the admin view as you would any regular plugin.

## Usage

These awards are given to users in two ways. The first and easiest way is to set up a `trigger` string in the "New" or "Edit" award interface. The trigger is based on a specific grammar for our plugin that is automatically parsed and used to set up wordpress based functionality that will listen to this trigger action. An example of a `trigger` string you would use would be

`CURRENT_USER_META UPDATED WHERE key=total_hours GTEQ 50`

If this `trigger` string were applied to our award, then we would "assign" this award to our user if the WordPress specific `user_meta` value with a key of `total_hours` was updated to have a value **greater than or equal to** 50. Documentation for this grammar is located in the documentation section below.

## Documentation

### WordPress Award Grammar

TBA