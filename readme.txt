=== Smallchat for Wordpress ===
Contributors: dotzak
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl.html
Tags: chat, smallchat, small.chat, slack, live-chat, live-help, live-support
Tested up to: 6.1.1
Requires at least: 4.9
Requires PHP: 5.6
Stable tag: 1.0.1

Add Smallchat to a WordPress website, allowing visitors to talk to teams via Slack.

== Description ==
Easily add the [Smallchat](https://small.chat/) client to a WordPress site, allowing visitors to talk to teams via [Slack](https://slack.com/).

The official [installation instructions](https://docs.small.chat/installation) from Smallchat are to install a plugin which allows you to add arbitrary JS to your site. This plugin loads only the Smallchat embed JS code, instead, which is useful for environments where there may be restrictions on the plugins allowed to be installed.

== Installation ==
= Prequisites =

Before using this plugin you need to add [Smallchat to Slack](https://my.small.chat/). After you\'ve added Smallchat to Slack, you can get your client ID from the embed code.

= Configuration =

1. Activate the plugin
1. Navigate to WP Admin > Settings > Smallchat.
1. Add your client ID

After that the Smallchat client will appear on the front end for non-admin, non-editor users, and in the WP Customizer.

== Changelog ==

This plugin is not under active development. Smallchat have not significantly changed their embed code since I originally wrote this, so it is unlikely that this plugin will need to be updated in the future.

Version bumps will be made to ensure that the plugin is compatible with the latest version of WordPress.

= 1.0.1 =

Release date: 2023-01-25

This release is just a version bump after testing to ensure that the plugin is compatible with WordPress 6.1.1.

* Bump WordPress tested up to version to 6.1.1
* Changed license from GPLv2 to GPLv3
