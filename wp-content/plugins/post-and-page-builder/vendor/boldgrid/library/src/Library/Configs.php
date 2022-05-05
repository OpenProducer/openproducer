<?php
/**
 * BoldGrid Library Configs Class
 *
 * @package Boldgrid\Library
 * @subpackage \Library
 *
 * @version 1.0.0
 * @author BoldGrid <wpb@boldgrid.com>
 */

namespace Boldgrid\Library\Library;

use Boldgrid\Library\Library\Plugin\Plugin;

/**
 * BoldGrid Library Configs Class.
 *
 * This class is responsible for setting and getting configuration
 * options that are set for the library.
 *
 * @since 1.0.0
 */
class Configs {

	/**
	 * @access private
	 *
	 * @var array $configs Configuration options.
	 */
	private static $configs;


	/**
	 * Initialize class and set class properties.
	 *
	 * @since 1.0.0
	 *
	 * @param array $configs Plugin configuration array.
	 */
	public function __construct( $configs = null ) {
		$defaults = include_once dirname( __DIR__ ) . '/library.global.php';

		self::set( $configs, $defaults );
	}

	/**
	 * [set description]
	 *
	 * @since 1.0.0
	 *
	 * @param [type] $configs  [description]
	 * @param [type] $defaults [description]
	 *
	 * @return object $this self.
	 */
	public static function set( $configs, $defaults = null ) {
		$defaults = $defaults ? $defaults : self::get();

		/**
		 * Allow the default configs to be filtered.
		 *
		 * @since 2.8.0
		 *
		 * @param array $defaults
		 */
		$defaults = apply_filters( 'Boldgrid\Library\Configs\set', $defaults );

		// Check if local library file is added.
		$localPath = dirname( __DIR__ ) . '/library.local.php';
		if ( file_exists( $localPath ) && is_readable( $localPath ) ) {
			$local = include_once $localPath;
			$defaults = wp_parse_args( $local, $defaults );
		}

		// Check if constant is added.
		if ( defined( 'BGLIB_CONFIGS' ) ) {
			$localPath = ABSPATH . BGLIB_CONFIGS;
			if ( file_exists( $localPath ) && is_readable( $localPath ) ) {
				$local = include_once $localPath;
				$defaults = wp_parse_args( $local, $defaults );
			}
		}

		return self::$configs = wp_parse_args( $configs, $defaults );
	}

	/**
	 * Get configs or config by key.
	 *
	 * @since 1.0.0
	 *
	 * @param  [type] $key [description]
	 *
	 * @return [type]      [description]
	 */
	public static function get( $key = null ) {
		$configs = self::$configs;
		if ( $key ) {
			$configs = ! empty( self::$configs[ $key ] ) ? self::$configs[ $key ] : null;
		} else {
			$configs = self::$configs;
		}

		return $configs;
	}

	/**
	 * Get the slug of the product that is loading the library.
	 *
	 * The configs include a "file" setting, which indicate which product is actually loading the
	 * library. If the file is "boldgrid-backup/boldgrid-backup.php" for example, this method will
	 * return "boldgrid-backup".
	 *
	 * @since 2.9.0
	 *
	 * @return string
	 */
	public static function getFileSlug() {
		$file = explode( '/', self::get('file') );

		return $file[0];
	}

	/**
	 * Get plugins from our configs.
	 *
	 * @since 2.9.0
	 *
	 * @param array $filters An optional array of filters to help get a specific type of plugin.
	 * @return array
	 */
	public static function getPlugins( $filters = array() ) {
		$plugins = array();

		foreach ( self::get( 'plugins' ) as $plugin ) {
			// If no filters, add the plugin. Else, only add the plugin if all filters match.
			if ( empty( $filters ) ) {
				$plugins[] = \Boldgrid\Library\Library\Plugin\Factory::create( $plugin['file'] );
			} else {
				$addPlugin = true;

				foreach( $filters as $key => $value ) {
					if ( ! isset( $plugin[ $key ] ) || $value !== $plugin[ $key ] ) {
						$addPlugin = false;
					}
				}

				if ( $addPlugin ) {
					$plugins[] = \Boldgrid\Library\Library\Plugin\Factory::create( $plugin['file'] );
				}
			}
		}

		return $plugins;
	}

	/**
	 * Set a new configuration item
	 *
	 * @since 2.4.0
	 *
	 * @param  string $key Name of new item.
	 */
	public static function setItem( $key, $value ) {
		self::$configs[ $key ] = $value;
	}
}
