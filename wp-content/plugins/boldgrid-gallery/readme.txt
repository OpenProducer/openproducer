=== BoldGrid Gallery ===
Contributors: imh_brad, joemoto, rramo012, timph
Tags: gallery, slider, slideshow, masonry
Requires at least: 4.4
Tested up to: 5.5
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

BoldGrid Gallery is a standalone plugin used for slideshows and galleries.

== Description ==

BoldGrid Gallery is a standalone plugin used for slideshows and galleries.

== Requirements ==

* PHP 5.3 or higher.

== Installation ==

1. Upload the entire boldgrid-gallery folder to the /wp-content/plugins/ directory.

2. Activate the plugin through the Plugins menu in WordPress.

== Changelog ==

= 1.5.1 =
* Bug fix:                      Fix update class.
* Bug fix:                      Trying to access array offset on value of type bool.

= 1.5 =
* Update:						Bump version.

= 1.4.3 =
* Update:		JIRA WPB-3292	Updated plugin URI.

= 1.4.2 =
* Bug fix:		JIRA WPB-3161	Fixed auto plugin update.

= 1.4.1 =
* Bug fix:		JIRA WPB-3151	Added check and load before using get_plugin_data() for updates.
* Update:		JIRA WPB-3112	Updated wc-gallery: 1.52 => 1.55.

= 1.4 =
* Bug fix:		JIRA WPB-2912	Fixed issue when installing plugins from the Tools Import page.

= 1.3.1 =
* Bug fix:		JIRA WPB-2892	Fixed plugin update checks for some scenarios (WP-CLI, Plesk, etc).
* Testing:		JIRA WPB-2744	Tested on WordPress 4.7.
* Misc:			JIRA WPB-2503	Added plugin requirements to readme.txt file.

= 1.3 =
* Update:						Bump Version.

= 1.2.3 =
* Misc:			JIRA WPB-2344	Updated readme.txt for Tested up to 4.6.1.
* Bug fix:		JIRA WPB-2336	Load BoldGrid settings from the correct WP option (site/blog).
* Update:		JIRA WPB-2368	Version constant is now set from plugin file.

= 1.2.2 =
* Bug fix:		JIRA WPB-2310	Removed broken plugin Settings link.  Pending review on WPB-2309.

= 1.2.1 =
* Misc:			JIRA WPB-2256	Updated readme.txt for Tested up to: 4.6.
* Rework:		JIRA WPB-1825	Formatting.

= 1.2 =
* Bug fix:		JIRA WPB-2114	Fixed gallery displaying in editor on wordpress 4.6.
* Bug fix:		JIRA WPB-2114	Fixing ordering of gallery items.

= 1.1.2 =
* New feature:	JIRA WPB-2037	Added capability for auto-updates by BoldGrid API response.
* Update:		JIRA WPB-2024	Updated wc-gallery: 1.48 => 1.52.
* Misc:         Updated editor.js to pass JSHint.

= 1.1.1 =
* Update:		JIRA WPB-1884	Passed WordPress 4.5.1 testing.
* Bug fix:		JIRA WPB-1893	JS errors in console when viewing attachments.

= 1.1.0.1 =
* Bug fix:		JIRA WPB-1816	Fixed update class interference with the Add Plugins page.

= 1.1 =
* Bug fix:		JIRA WPB-1809	Fixed undefined index "action" for some scenarios.  Optimized update class and addressed CodeSniffer items.

= 1.0.4 =
* Misc:			JIRA WPB-1361	Added license file.
* Bug Fix:		JIRA WPB-1646	Fixing Issues where masonry gallery was not WYSIWYG.

= 1.0.3 =
* Update:		JIRA WPB-1611	Updated wc-gallery: 1.40 => 1.48.
* Rework:		JIRA WPB-1617	Updated require and include statements for standards.

= 1.0.2 =
* Bug fix:		JIRA WPB-1553	Changed __DIR__ to dirname( __FILE__ ) for PHP <=5.2.
* Misc			JIRA WPB-1468	Updated readme.txt for Tested up to: 4.4.1

= 1.0.1 =
* New feature:	JIRA WPB-1363	Updated readme.txt for WordPress standards.

= 1.0 =
* Initial public release.

== Upgrade Notice ==
