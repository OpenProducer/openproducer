<?php
/**
 * BoldGrid Library Option Utility
 *
 * @package Boldgrid\Library
 * @subpackage \Util
 *
 * @version 1.0.0
 * @author BoldGrid <support@boldgrid.com>
 */

namespace Boldgrid\Library\Util;

/**
 * BoldGrid Library Option Class.
 *
 * This class is responsible for working with the WordPress options.
 *
 * @since 1.0.0
 */
class Option {
	/**
	 * @access public
	 *
	 * @var string $name   Name of the option to use.
	 * @var string $key    Key to use in option.
	 * @var string $option Option data retrieved.
	 */
	public static
		$name,
		$key,
		$option;


	/**
	 * Initialize the option utility.
	 *
	 * @since  1.0.0
	 *
	 * @param  string $name   Name of the option to use.
	 * @param  string $key    Key to use in option.
	 *
	 * @return null
	 */
	public static function init( $name = 'boldgrid_settings', $key = 'library' ) {
		self::$name = $name;
		self::$key = $key;
		self::$option = self::getOption();
	}

	/**
	 * Gets option from WordPress database.
	 *
	 * @since  1.0.0
	 *
	 * @return array Returns the option data from WordPress database.
	 */
	public static function getOption() {
		return get_option( self::$name, array() );
	}

	/**
	 * Set option key, value.
	 *
	 * @since  1.0.0
	 *
	 * @param  string $key   Key to add to option.
	 * @param  mixed  $value Value to assign to key.
	 *
	 * @return bool          Option update successful?
	 */
	public static function set( $key, $value ) {
		self::$option[ self::$key ][ $key ] = $value;
		return update_option( self::$name, self::$option );
	}

	/**
	 * Deletes by key in stored option.
	 *
	 * @since  1.0.0
	 *
	 * @param  string $key The key to delete.
	 *
	 * @return bool        Delete option successful?
	 */
	public static function delete( $key ) {
		unset( self::$option[ self::$key ][ $key ] );
		return update_option( self::$name, self::$option );
	}

	/**
	 * Retrieves an option key from the array.
	 *
	 * If no key is specified, then the default value will be returned.
	 *
	 * @since  1.0.0
	 *
	 * @param  mixed $key     Key to retrieve from option.
	 * @param  mixed $default The default value to return if key is not found.
	 *
	 * @return mixed          The key data or the default value if not found.
	 */
	public static function get( $key = null, $default = array() ) {
		return $key && ! empty( self::$option[ $key ] ) ? self::$option[ $key ] : $default;
	}

	/**
	 * Deletes plugin-related transients.
	 *
	 * @since 1.1.4
	 */
	public static function deletePluginTransients() {
		$names = array(
			'boldgrid_plugins',
			'boldgrid_wporg_plugins',
			'update_plugins',
		);

		foreach ( $names as $name ) {
			delete_site_transient( $name );
		}
	}
}
