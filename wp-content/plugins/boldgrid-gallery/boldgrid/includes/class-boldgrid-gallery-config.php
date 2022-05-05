<?php

/**
 * BoldGrid Source Code
 *
 * @package Boldgrid_Gallery_Config
 * @copyright BoldGrid.com
 * @version $Id$
 * @author BoldGrid.com <wpb@boldgrod.com>
 */

// Prevent direct calls
if ( ! defined( 'WPINC' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit();
}

/**
 * Boldgrid_Gallery_Config class
 */
class Boldgrid_Gallery_Config {
	/**
	 * Configs.
	 *
	 * @var array
	 */
	protected $configs;

	/**
	 * Get configs.
	 *
	 * @return array
	 */
	public function get_configs() {
		return $this->configs;
	}

	/**
	 * Set configs.
	 *
	 * @param array $Configs
	 *
	 * @return bool
	 */
	protected function set_configs( $configs ) {
		$this->configs = $configs;

		return true;
	}

	/**
	 * Constructor.
	 */
	public function __construct() {
		// Define Editor configuration directory, if not defined.
		if ( false === defined( 'BOLDGRID_GALLERY_CONFIGDIR' ) ) {
			define( 'BOLDGRID_GALLERY_CONFIGDIR', BOLDGRID_GALLERY_PATH . '/boldgrid/includes/config' );
		}

		$global_configs = require BOLDGRID_GALLERY_CONFIGDIR . '/config.plugin.php';

		$local_configs = array ();

		if ( file_exists( $local_config_filename = BOLDGRID_GALLERY_CONFIGDIR . '/config.local.php' ) ) {
			$local_configs = include $local_config_filename;
		}

		$configs = array_merge( $global_configs, $local_configs );

		$this->set_configs( $configs );
	}
}
