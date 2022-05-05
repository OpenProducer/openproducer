<?php
/**
 * BoldGrid Library Usage Helper Class.
 *
 * @package Boldgrid\Library
 *
 * @version 2.11.0
 * @author BoldGrid <wpb@boldgrid.com>
 */

namespace Boldgrid\Library\Library\Usage;

/**
 * BoldGrid Library Usage Helper Class.
 *
 * @since 2.11.0
 */
class Helper {
	/**
	 * Whether or not the current screen id begins with a prefix.
	 *
	 * @return bool True if the screen id begins with one of the prefixes.
	 */
	public static function hasScreenPrefix() {
		$prefixes        = apply_filters( 'Boldgrid\Library\Usage\getPrefixes', [] );
		$hasScreenPrefix = false;
		$screen          = get_current_screen();
		$screenId        = $screen->id;

		foreach ( $prefixes as $prefix ) {
			if ( substr( $screenId, 0, strlen( $prefix ) ) === $prefix ) {
				$hasScreenPrefix = true;
			}
		}

		return $hasScreenPrefix;
	}
}
