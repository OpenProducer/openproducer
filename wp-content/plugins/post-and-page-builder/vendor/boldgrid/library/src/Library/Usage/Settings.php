<?php
/**
 * BoldGrid Usage Settings.
 *
 * @package Boldgrid\Library
 *
 * @version 2.11.0
 * @author BoldGrid <wpb@boldgrid.com>
 */

namespace Boldgrid\Library\Library\Usage;

/**
 * BoldGrid Library Usage Settings Class.
 *
 * @since 2.11.0
 */
class Settings {
	/**
	 * The boldgrid_setting's option key that holds this information.
	 *
	 * @since 2.11.0
	 */
	private static $agreeKey = 'usage_agree';

	/**
	 * Whether or not the user has agreed.
	 *
	 * @since 2.11.0
	 *
	 * @return bool
	 */
	public static function hasAgreed() {
		$hasAgreed = \Boldgrid\Library\Library\Settings::getKey( self::$agreeKey );

		return ! empty( $hasAgreed );
	}

	/**
	 * Whether or not the user has made a yes / no desicion.
	 *
	 * @since 2.11.0
	 *
	 * @return bool
	 */
	public static function hasAgreeDecision() {
		return \Boldgrid\Library\Library\Settings::hasKey( self::$agreeKey );
	}

	/**
	 * Set the user's decision.
	 *
	 * @since 2.11.0
	 *
	 * @return bool True on success.
	 */
	public static function setAgree( $value ) {
		$value = ! empty( $value ) ? 1 : 0;

		return \Boldgrid\Library\Library\Settings::setKey( self::$agreeKey, $value );
	}
}
