<?php
/**
 * BoldGrid Library Registration.
 *
 * @package Boldgrid\Library
 *
 * @version 2.2.1
 * @author BoldGrid <wpb@boldgrid.com>
 */

namespace Boldgrid\Library\Library;

/**
 * BoldGrid Library Registration Class.
 *
 * This is a companion class to Boldgrid\Library\Util\Registration class.
 *
 * @since 2.2.1
 */
class Registration {

	/**
	 * Constructor.
	 *
	 * @since 2.2.1
	 */
	public function __construct() {
		Filter::add( $this );
	}

	/**
	 * Take action before a plugin is upgraded.
	 *
	 * It's nice that WordPress has an "upgrader_process_complete" action you
	 * can hook into to take action after a plugin is upgraded. However, what
	 * about an action BEFORE the upgrade? Such an action does not exist.
	 *
	 * What does exist is the ability to filter our $upgrader options before the
	 * upgrade takes place. So, in order to do an action before the upgrade
	 * runs, we'll hook into the upgrader_package_options filter.
	 *
	 * @since 2.2.1
	 *
	 * @hook: upgrader_package_options
	 *
	 * @param  array $options See filter declaration in
	 *                        wp-admin/includes/class-wp-upgrader.php
	 * @return array
	 */
	public function preUpgradePlugin( $options ) {
		$plugin = ! empty( $options['hook_extra']['plugin'] ) ? $options['hook_extra']['plugin'] : null;

		$isBoldgridPlugin = \Boldgrid\Library\Library\Util\Plugin::isBoldgridPlugin( $plugin );

		if( $isBoldgridPlugin ) {

			/*
			 * Before this plugin is upgraded, remove it from the list of
			 * registered libraries.
			 *
			 * We need to remove it because this plugin may be upgraded
			 * (or downgraded) to a version of the plugin where it does not
			 * contain the library.
			 *
			 * Reregistration of this plugin will take place in the constructor
			 * the next time this class is instantiated.
			 */
			$utilPlugin = new \Boldgrid\Library\Util\Registration\Plugin( $plugin );
			$utilPlugin->deregister();
		}

		return $options;
	}
}
