<?php
/**
 * BoldGrid Source Code
 *
 * @package Boldgrid_Inspirations_External_Plugin
 * @copyright BoldGrid.com
 * @version $Id$
 * @author BoldGrid <support@boldgrid.com>
 */

/**
 * The BoldGrid External Plugin class.
 */
class Boldgrid_Inspirations_External_Plugin {

	/**
	 * An instance of WPB_Plugin.
	 *
	 * @var WPB_Plugin
	 */
	protected $configs;

	/**
	 * Array of plugin statuses.
	 *
	 * @var array
	 */
	protected $boldgrid_plugins_status;

	/**
	 * Accessor for active plugins.
	 *
	 * @return array
	 */
	public function get_active_boldgrid_plugins() {
		return $this->boldgrid_plugins_status;
	}

	/**
	 * Accessor for configs.
	 *
	 * @return array
	 */
	public function get_configs() {
		return $this->configs;
	}

	/**
	 * Checks if a paticular boldgrid plugin is active by name.
	 *
	 * @param string $name
	 * @return boolean
	 */
	public function plugin_is_active( $name ) {
		return isset( $this->boldgrid_plugins_status[$name] ) ? $this->boldgrid_plugins_status[$name] : null;
	}

	/**
	 * Accepts configs.
	 *
	 * @param array $configs
	 */
	public function __construct( $boldgrid_configs = null ) {
		$this->configs = $boldgrid_configs;

		add_action( 'wp_loaded', array (
			$this,
			'find_plugins'
		) );
	}

	/**
	 * Check if plugin is active, before admin_init.
	 *
	 * @param string $plugin_name Sub-directory/file.
	 */
	public function is_active( $plugin_name ) {
		$all_active_plugins = $this->get_all_active_plugins();
		return ( false !== array_search( $plugin_name, $all_active_plugins, true ) );
	}

	/**
	 * Get all active plugins.
	 */
	public function get_all_active_plugins() {
		$site_plugins = get_option( 'active_plugins', array () );
		$sitewide_plugins = array_keys( get_site_option( 'active_sitewide_plugins', array() ) );
		return array_unique( array_merge( $site_plugins, $sitewide_plugins ) );
	}

	/**
	 * Find all plugins that are active.
	 */
	public function find_plugins() {
		$configs = $this->get_configs();

		$active_plugins = $this->get_all_active_plugins();

		if ( ! empty( $configs ) ) {
			foreach ( $configs['plugins'] as $name => $plugin ) {
				$this->boldgrid_plugins_status[$name] = false !== array_search( $plugin['path'],
					$active_plugins );
			}
		}
	}
}
