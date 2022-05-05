<?php
/**
 * BoldGrid Library Settings Class
 *
 * @package Boldgrid\Library
 *
 * @version 2.11.0
 * @author BoldGrid <wpb@boldgrid.com>
 */

namespace Boldgrid\Library\Library;

/**
 * BoldGrid Library Settings Class.
 *
 * @since 2.11.0
 */
class Settings {
	/**
	 * The option name where boldgrid settings are stored.
	 *
	 * @since 2.11.0
	 * @access private
	 * @var string
	 */
	private static $optionName = 'boldgrid_settings';

	/**
	 * Get the option.
	 *
	 * @since 2.11.0
	 *
	 * @return array
	 */
	public static function get() {
		return get_option( self::$optionName, [] );
	}

	/**
	 * Get a key from the option.
	 *
	 * @since 2.11.0
	 *
	 * @param string $key     The key of the array to return.
	 * @param mixed  $default The default value to return.
	 *
	 * @return mixed.
	 */
	public static function getKey( $key, $default = false ) {
		$option = self::get();

		return isset( $option[ $key ] ) ? $option[ $key ] : $default;
	}

	/**
	 * Whether or not a key exists in the option.
	 *
	 * @since 2.11.0
	 *
	 * @param  string $key The key to look for.
	 * @return bool
	 */
	public static function hasKey( $key ) {
		$option = self::get();

		return isset( $option[ $key ] );
	}

	/**
	 * Set the value of the option.
	 *
	 * @since 2.11.0
	 *
	 * @param  array $values The values to set.
	 * @return bool          True if saved.
	 */
	public static function set( array $values ) {
		return update_option( self::$optionName, $values );
	}

	/**
	 * Set the value of a specific key.
	 *
	 * @since 2.11.0
	 *
	 * @param  string $key   The key to set.
	 * @param  string $value The value to set.
	 * @return bool          True if the key was updated.
	 */
	public static function setKey( $key, $value ) {
		$option = self::get();

		$option[ $key ] = $value;

		return self::set( $option );
	}
}
