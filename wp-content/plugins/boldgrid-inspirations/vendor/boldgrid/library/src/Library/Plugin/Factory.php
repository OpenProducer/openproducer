<?php //phpcs:ignore WordPress.Files.FileName.NotHyphenatedLowercase
/**
 * BoldGrid Library Plugin Factory.
 *
 * Library package uses different naming convention.
 * phpcs:disable WordPress.NamingConventions.ValidVariableName
 * phpcs:disable WordPress.NamingConventions.ValidFunctionName
 *
 * @package Boldgrid\Plugin
 *
 * @since 2.12.2
 * @author BoldGrid <wpb@boldgrid.com>
 */

namespace Boldgrid\Library\Library\Plugin;

use Boldgrid\Library\Library\Configs;
use Boldgrid\Library\Library\Settings;
use Boldgrid\Library\Library\Plugin\Page;

/**
 * Plugin Factory
 *
 * @since 2.12.2
 */
class Factory {

	/**
	 * Class Constructor.
	 *
	 * @since 2.12.2
	 *
	 * @param string $pluginName Plugin slug or filename passed from constructor.
	 * @param array  $pluginConfig Array of plugin config data.
	 * @param bool   $getUpdateData Whether or not to get the updateData on initialization.
	 *
	 * @return Plugin
	 */
	public static function create( $pluginName, $pluginConfig = null, $getUpdateData = false ) {
		$plugin = null;
		if ( false === strpos( $pluginName, '.' ) ) {
			$plugin = self::createFromSlug( $pluginName, $pluginConfig, $getUpdateData );
		} else {
			$plugin = self::createFromFile( $pluginName, $pluginConfig, $getUpdateData );
		}

		return $plugin;
	}

	/**
	 * Creates a Plugin Object using a slug as the paramater.
	 *
	 * @param string $slug Plugin Slug.
	 * @param array  $pluginConfig Array of plugin config data.
	 * @param bool   $getUpdateData Whether or not to get updateData.
	 *
	 * @return Plugin
	 */
	public static function createFromSlug( $slug, $pluginConfig, $getUpdateData ) {

		$slug = ! empty( $slug ) ? $slug : '';

		$pluginConfig = ! empty( $pluginConfig ) ? $pluginConfig : array();

		$file = self::fileFromSlug( $slug );

		$path = ABSPATH . 'wp-content/plugins/' . $file;

		$isInstalled = self::isPluginInstalled( $path );

		$childPlugins = self::getChildPlugins( $file );

		return new Plugin(
			array(
				'slug'         => $slug,
				'pluginConfig' => $pluginConfig,
				'file'         => $file,
				'path'         => $path,
				'isInstalled'  => $isInstalled,
				'childPlugins' => $childPlugins,
				'getUpdateData' => $getUpdateData,
			)
		);
	}

	/**
	 * Creates a Plugin Object using a file as the paramater.
	 *
	 * @param string $slug Plugin Slug.
	 * @param array  $pluginConfig Array of plugin config data.
	 *
	 * @return Plugin
	 */
	private static function createFromFile( $file, $pluginConfig ) {

		$file = ! empty( $file ) ? $file : '';

		$pluginConfig = ! empty( $pluginConfig ) ? $pluginConfig : array();

		$slug = self::slugFromFile( $file );

		$path = ABSPATH . 'wp-content/plugins/' . $file;

		$isInstalled = self::isPluginInstalled( $path );

		$childPlugins = self::getChildPlugins( $file );

		return new Plugin(
			array(
				'slug'         => $slug,
				'pluginConfig' => $pluginConfig,
				'file'         => $file,
				'path'         => $path,
				'isInstalled'  => $isInstalled,
				'childPlugins' => $childPlugins,
			)
		);
	}

	/**
	 * Gets the plugin's file from the slug passed in construction.
	 *
	 * If a slug was passed, not a file, then this will find the file for us.
	 *
	 * @since 2.12.2
	 *
	 * @param string $slug Slug passed in construction.
	 *
	 * @return string
	 */
	public static function fileFromSlug( $slug ) {
		$plugin_file = '';
		if ( file_exists( WP_PLUGIN_DIR . '/' . $slug . '/' . $slug . '.php' ) ) {
			$plugin_file = $slug . '/' . $slug . '.php';
		} elseif ( file_exists( WP_PLUGIN_DIR . '/' . $slug . '.php' ) ) {
			$plugin_file = $slug . '.php';
		} else {
			$file_list = scandir( WP_PLUGIN_DIR );
			foreach ( $file_list as $file ) {
				if ( false !== strpos( $file, '.php' ) && false !== strpos( $slug, explode( '.', $file )[0] ) ) {
					$plugin_file = $file;
				}
			}
		}
		return $plugin_file;
	}

	/**
	 * Gets the plugin's slug from the file name passed in construction.
	 *
	 * If a filename was passed, not a slug, then this will find the slug for us.
	 *
	 * @since 2.12.2
	 *
	 * @param string $file Filename passed in construction.
	 */
	public static function slugFromFile( $file ) {
		$slug = '';
		if ( false !== strpos( $file, '/' ) ) {
			// If the filename has a '/' in it, the slug should be the first part of the string.
			$slug = explode( '/', $file )[0];
		} else {
			/*
			 * If the filename does not have a '/' then the slug will ahve to be pulled from the plugin's
			 * file contents. This is because the plugins with just a name, such as hello.php, do not
			 * always match their slug.
			 */
			$file_contents = file_get_contents( WP_PLUGIN_DIR . '/' . $file );
			$lines         = explode( "\n", $file_contents );
			foreach ( $lines as $line ) {
				if ( false !== strpos( $line, '@package' ) ) {
					$package = strtolower( explode( ' ', $line )[3] );
					$slug    = str_replace( '_', '-', $package );
				}
			}
		}
		return $slug;
	}

	/**
	 * Set whether or not the plugin is installed (different from activated).
	 *
	 * @since 2.12.2
	 */
	public static function isPluginInstalled( $path ) {
		$wp_filesystem = \Boldgrid\Library\Util\Version::getWpFilesystem();
		return $wp_filesystem->exists( $path );
	}

	/**
	 * Set our child plugins.
	 *
	 * @since 2.12.2
	 *
	 * @param string $file Filename.
	 *
	 * @return array.
	 */
	public static function getChildPlugins( $file ) {
		$child_plugins = array();

		$config  = array();
		$plugins = Configs::get( 'plugins' );
		if ( $plugins ) {
			foreach ( $plugins as $plugin ) {
				if ( $plugin['file'] === $file ) {
					$config = $plugin;
				}
			}
		}

		if ( empty( $config['childPlugins'] ) ) {
			return array();
		}

		foreach ( $config['childPlugins'] as $file ) {
			$childPlugins[] = self::create( $file );
		}

		return $childPlugins;
	}
}
