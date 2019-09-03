=== User Awards ===
Contributors: kwmartin
Donate link: N/A
Tags: awards, user engagement
Requires at least: 5.1.1
Tested up to: 5.2.2
Stable tag: 0.1.1
Requires PHP: 5.6
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Let your users know how much you appreciate them! Enhances your site with the ability to assign and give awards to users based on the actions that they take.

== Description ==

This plugin provides the ability to _assign_ and _give_ awards to users based on specific actions that they take on your website. How great would it be to give a gift to your site's most active users? Think of how great it would feel to be on the receiving end of that!

The plugin was made to work with your sites as easily as possible. To do this, awards are assigned using _trigger strings_. These strings are made using a _trigger string interface_ whenever you create or update an award.

We also provide a wordpress administration tab with three different categories of windows to be able to view, create, update, and perform administrative tasks on the awards that are assigned to your users.

For any questions about this plugin, please make sure and look at the `support` tab's forums, as well as any of the DOCUMENTATION or FAQ entries that are availale. You are also able to contact me at any time, and I will try and be as fast as possible with any answers to your questions.

Thank you for choosing this plugin!

== Installation ==

1. Upload the plugin files to the `/wp-content/plugins/user-awards` directory install the plugin through the WordPress plugins screen directly
2. Activate the plugin through the
This section describes how to install the plugin and get it working.
3. Activate the plugin through the ‘Plugins’ screen in WordPress. You should be notified if the plugin activation was successful.
4. Click on the `Awards` menu item on the administrator sidebar in order to interact with the User Awards plugin administration actions.

== Usage ==

The following usage documentation is specifically for understanding the basic operations in each of the windows that are for this application.

### "User Awards" Window

This window shows a listing of all of the awards that are currently assigned or given to users on your site.

A bulk action is provided on this window that allows you to mass remove awards from users.

You can also _give_ users awards that they have not been given as well, by clicking on the "Give Award" button when it is available to be clicked on. Otherwise, we display the datestamp in which the user was actually first given the award.

### "Add New Award" / "Edit Award" Window

Main windows for adding and editing awards and the functionality of how they are assigned to users. You are able to:

* Name your award
* Write a description for your award
* Specify the rules of how awards are given to users
* Apply or Give award to a specific user
* Update the award to be automatically "Given" to users when they are "Assigned" an award.

## "Awards" Window

This window is the general window for the plugin, and shows a listing of all the awards that are currently made, the "trigger" string associated with them, whether or not they are an _auto-give_ award, and the date on which the award was published.

There are also some bulk actions that you should be able to perform.

== Documentation ==

## Awards Trigger Syntax

Explanation of each of the items that make up our trigger string, with accepted values of each listed under.

* [ entity ] -- Used to scope your awards trigger to a specific action.
	- CURRENT_USER_META -- Consider the meta value of the current user

* [ trigger_type ] -- Type of action that is performed to the current entity.
	- UPDATED -- When entity value is updated (Listens to calls of the  update_user_meta() function)
	- CREATED -- When entity value is created (Listens to calls of the add_user_meta() function)
	- ASSIGNED -- Listens to calls of both the update_user_meta() and add_user_meta() function.
	- ~EXCLUDED~ -- Not Implemented

* [ trigger ] - Made up of three separate values itself, [ descriptor ] [ operator ] [ control ]
	- [ descriptor ]
		- [ entity_type ] = [ value ] ex: key = hours

    - [ operator ]
    	- GT - greater than
    	- LT - less than
    	- EQ - equal to
    	- GTEQ - greater than equal to
    	- LTEQ - less than equal to

    - [ control ]
    	- Value used to compare against. e.g. 2
    	- *NOTE*: The control *can also be a string*, but in order for this to work, you must use the EQ operator, as shown above.


## $UserAward Global Object

A global _$UserAward_ variable allows developers to interact with the core API of the plugin so you can develop your awarding schema however you would like to. That being said, this stuff is for developers ONLY.

I would recommend putting the logic that controls your interactions with this API in your `functions.php` file.

Documentation and usage for core API functions available to you [can be seen here](https://github.com/Spraynard/user_awards)

== Frequently Asked Questions ==
No frequently asked questions are known at this time.

== Changelog ==

= Version 0.1.1 =
Adding in new trigger string builder interface. Allows users to more easily set up award triggers based on user actions.

= Version 0.0.2 =
Updating readme.txt

= Version 0.0.1 =
Initial version of the plugin.

== Upgrade Notice ==

No Upgrade notices at this time

== Screenshots ==

1. Award List Window. Shows the awards that are available to give to users.
2. New/Edit Award Window #1
3. New/Edit Award Window #2
4. User Awards window. Shows any awards that are assigned to users.

== Attribution ==

This plugin's icon is not an original piece of work. It was made by [**Freepik** from Flaticon.com](www.flaticon.com)
