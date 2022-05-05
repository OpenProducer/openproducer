# library

The BoldGrid Library for shared code used in official BoldGrid plugins and themes.

Using composer, you can get started quickly:

```php
composer require boldgrid/library

```

## Changelog ##

### 2.13.5 ###

Release date: September 8, 2021

* Bug Fix: Review prompt prevents admins from deleting other user accounts [#192](https://github.com/BoldGrid/post-and-page-builder/issues/192)

### 2.13.4 ###

Release date: June 10th, 2021

* Bug Fix: Avoid PHP Error in Dashboard/SortWidgets.php by validating widget containers.

### 2.13.3 ###

Release date: October 13th, 2020

* Bug Fix: Avoid PHP Warning in Dashboard/SortWidgets.php by validating widgets.
* Bug Fix: Avoid PHP Warning in NewsWidget.php by validating posts.

### 2.13.2 ###

Release date: August 12th, 2020

* Update: Various changes for WordPress 5.5
* Bug Fix: Added transient validatiors.

### 2.13.1 ###

Release date: July 28th, 2020

* Bug Fix: php 7.4 compatibility issue in Dashboard News Widget.

### 2.13.0 ###

Release date: July 7th, 2020

* Bug Fix: Fixed css inconsistency between firefox and chrome
* Bug Fix: Fixed padding of links to match footer text.
* Update: Removed Auto Update functionality from library and moved to Total Upkeep plugin.
* New Feature: Added Plugin\Factory Class allowing for Plugin\Plugin objects to be generated via factory.
* New Feature: Added new methods to Plugin\Plugins class.
* New Feature: Added new UnitTests.
* New Feature: Updated UI to use a fixed container layout.

### 2.12.3 ###

Release date: June 23rd, 2020

* Bug fix: Check for Card class before using card.

### 2.12.2 ###

Release date: May 29th, 2020

* Bug fix: Do not load libraries from deleted plugins.
* Update: Expanded Plugins class and added Themes class.

### 2.12.1 ###

Release date: February 7th, 2020

* Update: Display plugin notices via javascript.

### 2.12.0 ###

Release date: February 6th, 2020

* Update: Added plugin notifications.

### 2.11.1 ###

Release date: January 14th, 2019

* Update: Added spinner for the UI menu while the page loads.

### 2.11.0 ###

Release date: December 19th, 2019

* Update: Added Usage class.
* Update: Added methods to Plugin class for getting / testing version data via plugins_checked.

### 2.10.7 ###

Release date: December 10th, 2019

* Update: Changed the recommended form plugin from WPForms to weForms.

### 2.10.6 ###

Release date: November 19, 2019

* Update: Renamed plugin from "BoldGrid Backup" to "Total Upkeep".

### 2.10.5 ###

Release date: November 11, 2019

* Bug fix: Misc style fixes for WordPress 5.3.

### 2.10.4 ###

Release date: October 11th, 2019

* Bug fix: Moved temporary pluggable function code to a new file to fix scope.

### 2.10.3 ###

Release date: October 10th, 2019

* Bug fix: Stop using pluggable.php file.

### 2.10.2 ###

Release date: September 17th, 2019

* Update: Added methods to easily get a plugin's install and activate urls.

### 2.10.1 ###

Release date: September 5th, 2019

* Update: Recommend W3 Total Cache in Plugins > Add New
* Update: Removing BoldGrid Staging from Plugins > Add New

### 2.10.0 ###

Release date: August 29th, 2019

* New feature: Show BoldGrid News widget in the dashboard.
* New feature: Show BoldGrid Notifications widget in the dashboard.
* New feature: New dashboard pages, can be utilized by plugins.

### 2.9.2 ###

Release date: August 1st, 2019

* Bug fix: Optimized plugin checker.
* Updated: Updated deps.

### 2.9.1 ###

Release date: July 25th, 2019

* Update: Added a trailing slash to Central url for the Reseller.

### 2.9.0 ###

Release date: July 2nd, 2019

* New feature: Added A BoldGrid RSS feed to the dashboard.

### 2.8.2 ###

Release date: June 7th, 2019

* Update: Updated dependencies.

### 2.8.1 ###

Release date: May 21st, 2019

* Bug fix: Ensure correct library versions are set during bulk activation.

### 2.8.0 ###

Release date: Apr 16th, 2019

* New feature: "Get a new key" updated to link to BoldGrid Central, and automatically add the key given.
* New feature: Adding German translations, de_DE.
* Update: Made translation ready. Text domain changed to boldgrid-library.

### 2.7.7 ###

Release date: Jan 15th, 2019

* Update: Add method to get a plugin's download url from the api server.
* Update: New system that asks user for bug fixes / new features, or requests plugin rating.
* Update: Minor updates to the BoldGrid Connect Key prompt.

### 2.7.6 ###

Release date: Dec 5th, 2018

* Update: Inverse logic fixes, is_plugin_active vs is_plugin_inactive.

### 2.7.5 ###

Release date: Dec 4th, 2018

* Bug fix: BoldGrid logo not showing in front end admin bar.

### 2.7.4 ###

Release date: Dec 4th, 2018

* Bug fix: JIRA BGCONN-35   Prevent Connect Key notice on block editor pages.

### 2.7.3 ###

Release date: Nov 27th, 2018

* Feature: Adding Crio's enzo to the admin icons font.

### 2.7.2 ###

Release date: Nov 26th, 2018

* Update:                   Updated production build process to use composer post-autoload-dump hook.

### 2.7.1 ###

Release date: Nov 20th, 2018

* Bug fix: JIRA BGCONN-29   Fixed API key entry form; removing inputs and displaying success message.
* Update:  JIRA BGCONN-32   Removed auto-update section from settings.  It is still used by the Backup plugin.
* Update:  JIRA BGBKUP-285  Save settings and reload to the current section.

### 2.7.0 ###

Release date: Oct 30th, 2018

* Feature: JIRA BGCONN-16   Added BoldGrid Connect settings for individual plugin and theme auto-updates.
* Feature:                  JS framework for Postbox settings.
* Bug fix: JIRA BGCONN-19   Fixed display when the library is used in a theme.
* Bug fix: JIRA BGBKUP-270  Dismissible notices are not staying dismissed.

### 2.6.1 ###
* Update:                   Theme check fixes.

### 2.6.0 ###
* Feature: JIRA BGTHEME-576 Add filter to check for is premium.
* Feature: JIRA BGCONN-23   Added mini Connect Key entry form.
* Update:                   Update success message after successful key entry.

### 2.4.2 ###
* Bug fix: Show timeout message when saving key times out.
* Bug fix: Misc bug fuxes.

### 2.4.1 ###
* Fix: Display issue with key entry prompt HTML.

### 2.4.0 ###
* Feature: JIRA BGTHEME-361 BoldGrid Connect Page.
* Feature: JIRA BGTHEME-361 Admin Bar Menus.
* Update:  JIRA WPB-3922    Updated license API to v2.

### 2.3.6 ###
* Update:  JIRA BGINSP-23   Updated "boldgrid_available" transient lifetime and checking. Also fixed fatal error when API has an error response.

### 2.3.5 ###
* Bug fix:                  Updating boldgrid-backup link in config.

### 2.3.4 ###
* Update:  JIRA BGBKUP-220  Sanitize inputs.

### 2.3.3 ###
* Bug fix: JIRA BGINSP-15   Disable Connect Key request button after submission.

### 2.3.2 ###
* Update:                   More clear error message on failed ajax license clears.
* Update:                   Added prettier-eslint.

### 2.3.1 ###
* Bug fix: JIRA BGBKUP-180  Fixed empty check for PHP 5.3.

### 2.3.0 ###
* Feature: JIRA BGBKUP-180  Handle auto updates as configured by the boldgrid_settings option.

### 2.2.2 ###
* Bug fix: JIRA WPB-3767    Prevent invalid API calls for check-version.

### 2.2.1 ###
* Bug fix: JIRA WPB-3730    Fixed loading of plugin installer class.
* Update:  JIRA WPB-3725    Use a transient in Checker::findUpdated().
* Bug fix: JIRA WPB-3724    Do not call getLicense if Connect Key is not available.
* Update:  JIRA WPB-3721    Moved Plugin\Checker back to Library.
* Bug fix:                  Duplicate admin notices showing.

### 2.2.0 ###
* Bug fix: JIRA WPB-3714    Fixed PHP notice in Key::verifyData().
* Feature:                  As a user, I can refresh my license key status.

### 2.1.0 ###
* Feature: JIRA BGTHEME-103 Added ClaimPremiumKey notice.

### 2.0.0 ###
* Feature: JIRA BGINSP-3    Added filter to display Connect Key prompt admin notice, even if dismissed.
* Update:  JIRA WPB-3684    Moved plugin install to its own package (boldgrid/plugin-install).
* Feature: JIRA BGBKUP-75   Added dismiss/undismiss for Connect Key prompt/notice.

### 1.1.6 ###
* Bug fix: JIRA BGBKUP-67   Fixed key prompt is-dismissible, and hid duplicate notice from other plugin.
* Feature: JIRA WPB-3638    Added post-and-page-builder and boldgrid-easy-seo to the Plugins >> Add New page.
* Bug fix: JIRA WPB-3636    Fixed invalid version number sent for plugins not installed, but in config.
* Bug fix: JIRA WPB-3635    API calls now respect release channels.

### 1.1.5 ###
* Bug fix: JIRA WPB-3518    Fixed fatal error in certain scenarios from double inclusion of WP core files.

### 1.1.4 ###
* Bug fix: JIRA WPB-3427    Adjusted handling of plugin update transients.
*                           Added premium product check to license class.

### 1.1.3 ###
*                           Validate plugin before printing card.

### 1.1.2 ###
*                           Bug fixes.

### 1.1.1 ###
*                           Added form affiliate data.

### 1.1.0 ###
*                           Added action for when theme release channel changed.
*                           Added Reseller class.

## Development ##

### Installing Dependencies
Before you can use the development version of this plugin you must install the dependencies.

```
composer install -o --prefer-source
yarn install
gulp
```

### Auto Updates
To test / trigger auto updates, you can run the following:

```
wp option delete auto_updater.lock & wp transient delete --all && wp cron event run wp_version_check
```
