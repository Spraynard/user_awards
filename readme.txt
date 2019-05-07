=== User Awards ===
Contributors: kwmartin
Donate link: N/A
Tags: awards, user engagement
Requires at least: 5.1.1
Tested up to: 5.1.1
Stable tag: 1.0.0
Requires PHP: 5.6
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Let your users know how much you appreciate them! Enhances your site with the abilty to assign and give awards to users based on the actions that they take.

== Description ==

This plugin will add an `Awards` custom post type to your website. This interface allows you to perform any action that you could perform with your regular posts, but with a few additions that provide access to the core behavior of this plugin.

There is also a `User Awards` sub-menu which gives a tabular view of all the awards that are assigned to users. This is accessible from the `Awards` admin menu in your WordPress administration area.

# __Usage__

Understanding the different actions you can take in each available window is key to having this plugin work for you.

## New Award / Edit Award Window
These administration windows have three meta boxes associated with them. Below are descriptions of each metabox and why it is included.

### Awards Trigger
Text Input. Accepts an **awards trigger string** that describes the behavior of how an award will be assigned to users.

#### Example
You have a membership blog site, and you want to award your site's members for liking at least ten blog posts. Each member has a `post_likes` *user_meta* value that is incremented each time a member likes a blog post (e.g. If a member likes 3 blog posts, they will have a `post_likes` meta value of `3`).

In order to set our award up to assign itself to a user after someone likes more than ten different posts, we would put something like this in the `Awards Trigger` input:

`CURRENT_USER_META ASSIGNED WHERE key=post_likes EQ 10`

This string tells the award to assign itself to the user if the `user_meta_post_likes` value of the current user's meta was updated or created to equal a value of `10`.

### Auto-Give Award
Checkbox input. Check this box to automatically have the award be *given* to a user when it would originally be assigned.

### Apply/Give Award To User
Select Input combined with a checkbox input. Provides the ability to select a user from your member list to either assign/give an award to a user.

The `Awards Trigger` meta box can be filled in with a string that, when valid, provides a method for assigning an award to a user.

Wi



== Installation ==

This section describes how to install the plugin and get it working.

e.g.

1. Upload the plugin files to the `/wp-content/plugins/plugin-name` directory, or install the plugin through the WordPress plugins screen directly.
1. Activate the plugin through the 'Plugins' screen in WordPress
1. Use the Settings->Plugin Name screen to configure the plugin
1. (Make your instructions match the desired user flow for activating and installing your plugin. Include any steps that might be needed for explanatory purposes)


== Frequently Asked Questions ==

= A question that someone might have =

An answer to that question.

= What about foo bar? =

Answer to foo bar dilemma.

== Screenshots ==

1. This screen shot description corresponds to screenshot-1.(png|jpg|jpeg|gif). Note that the screenshot is taken from
the /assets directory or the directory that contains the stable readme.txt (tags or trunk). Screenshots in the /assets
directory take precedence. For example, `/assets/screenshot-1.png` would win over `/tags/4.3/screenshot-1.png`
(or jpg, jpeg, gif).
2. This is the second screen shot

== Changelog ==

= 1.0 =
* A change since the previous version.
* Another change.

= 0.5 =
* List versions from most recent at top to oldest at bottom.

== Upgrade Notice ==

= 1.0 =
Upgrade notices describe the reason a user should upgrade.  No more than 300 characters.

= 0.5 =
This version fixes a security related bug.  Upgrade immediately.

== Arbitrary section ==

You may provide arbitrary sections, in the same format as the ones above.  This may be of use for extremely complicated
plugins where more information needs to be conveyed that doesn't fit into the categories of "description" or
"installation."  Arbitrary sections will be shown below the built-in sections outlined above.

== A brief Markdown Example ==

Ordered list:

1. Some feature
1. Another feature
1. Something else about the plugin

Unordered list:

* something
* something else
* third thing

Here's a link to [WordPress](https://wordpress.org/ "Your favorite software") and one to [Markdown's Syntax Documentation][markdown syntax].
Titles are optional, naturally.

[markdown syntax]: https://daringfireball.net/projects/markdown/syntax
            "Markdown is what the parser uses to process much of the readme file"

Markdown uses email style notation for blockquotes and I've been told:
> Asterisks for *emphasis*. Double it up  for **strong**.

`<?php code(); // goes in backticks ?>`
