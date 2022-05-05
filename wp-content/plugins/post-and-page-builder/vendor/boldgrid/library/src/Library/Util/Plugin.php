<?php
/**
 * BoldGrid Library Util Plugin.
 *
 * @package Boldgrid\Library
 *
 * @version 2.2.1
 * @author BoldGrid <wpb@boldgrid.com>
 */

namespace Boldgrid\Library\Library\Util;

/**
 * BoldGrid Library Util Plugin Class.
 *
 * This class is responsible for plugin related utility helpers.
 *
 * @since 2.2.1
 */
class Plugin {

	/**
	 * Get plugin data filtered by plugin slug pattern.
	 *
	 * @since 2.2.1
	 *
	 * @param  string $pattern A regex pattern used to filter the plugin data array.
	 * @return array
	 */
	public static function getFiltered( $pattern = '' ) {
		if ( ! function_exists( 'get_plugins' ) ) {
			require_once ABSPATH . 'wp-admin/includes/plugin.php';
		}

		$plugins = get_plugins();

		if ( empty( $pattern ) ) {
			return $plugins;
		}

		$filtered = array();

		foreach ( $plugins as $slug => $data ) {
			if ( preg_match( '#' . $pattern . '#', $slug ) ) {
				$filtered[ $slug ] = $data;
			}
		}

		return $filtered;
	}

	/**
	 * Determine if a plugin is a BoldGrid plugin.
	 *
	 * @since 2.2.1
	 *
	 * @param  string $plugin Such as post-and-page-builder/post-and-page-builder.php
	 * @return bool
	 */
	public static function isBoldgridPlugin( $plugin ) {
		if( empty( $plugin ) ) {
			return false;
		}

		$pluginChecker = new \Boldgrid\Library\Library\Plugin\Checker();

		$plugins = \Boldgrid\Library\Library\Util\Plugin::getFiltered( $pluginChecker->getPluginPattern() );

		return array_key_exists( $plugin, $plugins );
	}
}
