<?php
/**
 * Class: Boldgrid_Editor_Service
 *
 * Handle services.
 *
 * @since      1.6
 * @package    Boldgrid_Editor
 * @subpackage Boldgrid_Editor_Service
 * @author     BoldGrid <support@boldgrid.com>
 * @link       https://boldgrid.com
 */

/**
 * Class: Boldgrid_Editor_Service
 *
 * Handle services.
 *
 * @since      1.6
 */
abstract class Boldgrid_Editor_Service {

	protected static $services;

	/**
	 * Register a service.
	 *
	 * Stores instance into an array.
	 *
	 * @since 1.6
	 *
	 * @param  string $name     Name of Service.
	 * @param  mixed $instance  Instance of service.
	 */
	public static function register( $name, $instance ) {
		self::$services[$name] = $instance;
	}

	/**
	 * Get the service.
	 *
	 * @since 1.6
	 *
	 * @param  string $name Name of Service.
	 * @return mixed        Service Instance.
	 */
	public static function get( $name ) {
		return self::$services[ $name ];
	}

}
