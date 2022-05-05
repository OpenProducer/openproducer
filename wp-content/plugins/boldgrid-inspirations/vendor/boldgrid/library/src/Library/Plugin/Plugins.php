<?php //phpcs:ignore WordPress.Files.FileName
/**
 * BoldGrid Library Plugin Plugins.
 * phpcs:disable WordPress.NamingConventions.ValidVariableName
 * phpcs:disable WordPress.NamingConventions.ValidFunctionName
 *
 * @package Boldgrid\Plugin
 *
 * @since 2.10.0
 * @author BoldGrid <wpb@boldgrid.com>
 */

namespace Boldgrid\Library\Library\Plugin;

use Boldgrid\Library\Library\Configs;

/**
 * Generic plugins class.
 *
 * Unlike the Plugin class, Plugins is a generic class for "plugins" related methods.
 *
 * @since 2.10.0
 */
class Plugins {
	/**
	 * Get active BoldGrid plugins only ( based on the Configs data ).
	 *
	 * This is used for obtaining BoldGrid plugins for the Dashboard Notifications Widget.
	 *
	 * @since 2.10.0
	 *
	 * @return array
	 */
	public static function getActive() {
		// Get an array of plugins that should be within the widget.
		$plugins = Configs::getPlugins(
			array(
				'inNotificationsWidget' => true,
			)
		);

		// Then filter that array of plugins to get our active plugins.
		$activePlugins = array();
		foreach ( $plugins as $plugin ) {
			if ( $plugin->isActive() ) {
				$activePlugins[] = $plugin;
			}
		}
		unset( $plugins );

		return $activePlugins;
	}

	/**
	 * Get All Plugins.
	 *
	 * @since 2.12.2
	 *
	 * @return array
	 */
	public static function getAllPlugins() {
		include_once ABSPATH . 'wp-admin/includes/plugin.php';
		$plugins     = get_plugins();
		$all_plugins = array();
		foreach ( $plugins as $file => $plugin_data ) {
			$all_plugins[] = Factory::create( $file );
		}
		return $all_plugins;
	}

	/**
	 * Get Active Plugin by Slug.
	 *
	 * @since 2.12.2
	 *
	 * @param array  $activePlugins List of Plugin objects.
	 * @param string $slug Slug string.
	 *
	 * @return Plugin
	 */
	public static function getBySlug( array $activePlugins, $slug ) {
		$activePlugins = self::getAllPlugins();
		foreach ( $activePlugins as $plugin ) {
			if ( $plugin->getSlug() === $slug ) {
				return $plugin;
			}
		}
		return new \WP_Error( 'boldgrid_plugin_not_found', sprintf( 'No plugin could be found with the slug %s.', $slug ) );
	}
}
