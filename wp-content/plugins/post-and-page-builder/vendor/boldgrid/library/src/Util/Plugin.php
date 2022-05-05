<?php
/**
 * BoldGrid Library Plugin Utils
 *
 * @package Boldgrid\Library
 * @subpackage Util
 *
 * @version 1.0.2
 * @author BoldGrid <wpb@boldgrid.com>
 */

namespace Boldgrid\Library\Util;

/**
 * BoldGrid Library Util Plugin Class.
 *
 * This class is responsible for plugin related utility helpers.
 *
 * @since 1.0.2
 */
class Plugin {

	/**
	 * Helper to get and verify the plugin file.
	 *
	 * @since 1.0.2
	 *
	 * @param  string $slug Slug of the plugin to get main file for.
	 *
	 * @return mixed  $file Main plugin file of slug or null if not found.
	 */
	public static function getPluginFile( $slug ) {

		// Load plugin.php if not already included by core.
		if ( ! function_exists( 'get_plugins' ) ) {
			require ABSPATH . '/wp-admin/includes/plugin.php';
		}

		$plugins = get_plugins();

		foreach ( $plugins as $file => $info ) {

			// Get the basename of the plugin.
			$basename = dirname( plugin_basename( $file ) );
			if ( $basename === $slug ) {
				return $file;
			}
		}

		return null;
	}
}
