<?php
/**
 * File: Pluggable.php
 *
 * @link       https://www.boldgrid.com
 * @since      2.10.4
 *
 * @package    Boldgrid\Library
 * @subpackage \Util
 * @copyright  BoldGrid
 * @author     BoldGrid <support@boldgrid.com>
 */

/**
 * Avoid fatal errors due to certain filesystem types.
 *
 * This fix is only to prevent fatal errors. It is up to the plugins including this library to
 * test the filesystem and determine whether or not they're compatible.
 *
 * ftpext
 * Fatal error: Call to undefined function wp_generate_password() in wp-admin/includes/file.php
 *
 * Including the pluggable.php file caused issues with some other plugins that override functions.
 * Instead, we defined the wp_generate_password here.
 *
 * The code below was copied from "wp-includes/pluggable.php" in WordPress code 5.2.3.
 *
 * @since 2.10.4
 *
 * @todo Remove this method after an appropriate amount of time.  The requirement was removed in BGB on 10/10/2019.
 */
if ( ! function_exists( 'wp_rand' ) ) :
	/**
	 * Generates a random number.
	 *
	 * @since 2.6.2
	 * @since 4.4.0 Uses PHP7 random_int() or the random_compat library if available.
	 *
	 * @global string $rnd_value
	 * @staticvar string $seed
	 * @staticvar bool $use_random_int_functionality
	 *
	 * @param int $min Lower limit for the generated number
	 * @param int $max Upper limit for the generated number
	 * @return int A random number between min and max
	 */
	function wp_rand( $min = 0, $max = 0 ) {
		global $rnd_value;

		// Some misconfigured 32bit environments (Entropy PHP, for example) truncate integers larger than PHP_INT_MAX to PHP_INT_MAX rather than overflowing them to floats.
		$max_random_number = 3000000000 === 2147483647 ? (float) '4294967295' : 4294967295; // 4294967295 = 0xffffffff.

		// We only handle Ints, floats are truncated to their integer value.
		$min = (int) $min;
		$max = (int) $max;

		// Use PHP's CSPRNG, or a compatible method.
		static $use_random_int_functionality = true;
		if ( $use_random_int_functionality ) {
			try {
				$_max = ( 0 != $max ) ? $max : $max_random_number;
				// wp_rand() can accept arguments in either order, PHP cannot.
				$_max = max( $min, $_max );
				$_min = min( $min, $_max );
				$val  = random_int( $_min, $_max );
				if ( false !== $val ) {
					return absint( $val );
				} else {
					$use_random_int_functionality = false;
				}
			} catch ( Error $e ) {
				$use_random_int_functionality = false;
			} catch ( Exception $e ) {
				$use_random_int_functionality = false;
			}
		}

		/*
			* Reset $rnd_value after 14 uses.
			* 32(md5) + 40(sha1) + 40(sha1) / 8 = 14 random numbers from $rnd_value.
			*/
		if ( strlen( $rnd_value ) < 8 ) {
			if ( defined( 'WP_SETUP_CONFIG' ) ) {
				static $seed = '';
			} else {
				$seed = get_transient( 'random_seed' );
			}
			$rnd_value  = md5( uniqid( microtime() . mt_rand(), true ) . $seed );
			$rnd_value .= sha1( $rnd_value );
			$rnd_value .= sha1( $rnd_value . $seed );
			$seed       = md5( $seed . $rnd_value );
			if ( ! defined( 'WP_SETUP_CONFIG' ) && ! defined( 'WP_INSTALLING' ) ) {
				set_transient( 'random_seed', $seed );
			}
		}

		// Take the first 8 digits for our value.
		$value = substr( $rnd_value, 0, 8 );

		// Strip the first eight, leaving the remainder for the next call to wp_rand().
		$rnd_value = substr( $rnd_value, 8 );

		$value = abs( hexdec( $value ) );

		// Reduce the value to be within the min - max range.
		if ( $max != 0 ) {
			$value = $min + ( $max - $min + 1 ) * $value / ( $max_random_number + 1 );
		}

		return abs( intval( $value ) );
	}
endif;

if ( ! function_exists( 'wp_generate_password' ) ) :
	/**
	 * Generates a random password drawn from the defined set of characters.
	 *
	 * Uses wp_rand() is used to create passwords with far less predictability
	 * than similar native PHP functions like `rand()` or `mt_rand()`.
	 *
	 * @since 2.5.0
	 *
	 * @param int  $length              Optional. The length of password to generate. Default 12.
	 * @param bool $special_chars       Optional. Whether to include standard special characters.
	 *                                  Default true.
	 * @param bool $extra_special_chars Optional. Whether to include other special characters.
	 *                                  Used when generating secret keys and salts. Default false.
	 * @return string The random password.
	 */
	function wp_generate_password( $length = 12, $special_chars = true, $extra_special_chars = false ) {
		$chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
		if ( $special_chars ) {
			$chars .= '!@#$%^&*()';
		}
		if ( $extra_special_chars ) {
			$chars .= '-_ []{}<>~`+=,.;:/?|';
		}

		$password = '';
		for ( $i = 0; $i < $length; $i++ ) {
			$password .= substr( $chars, wp_rand( 0, strlen( $chars ) - 1 ), 1 );
		}

		/**
		 * Filters the randomly-generated password.
		 *
		 * @since 3.0.0
		 *
		 * @param string $password The generated password.
		 */
		return apply_filters( 'random_password', $password );
	}
endif;
