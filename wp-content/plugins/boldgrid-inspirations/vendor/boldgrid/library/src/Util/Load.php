<?php
/**
 * BoldGrid Library Load Utility
 *
 * @package Boldgrid\Library
 * @subpackage \Util
 *
 * @version 1.0.0
 * @author BoldGrid <wpb@boldgrid.com>
 */

namespace Boldgrid\Library\Util;

/**
 * BoldGrid Library Load Utiliy Class.
 *
 * This class is responsible for determining the highest version of
 * available libraries to load and then loading it.
 *
 * @since 1.0.0
 */
class Load {

	/**
	 * @access private
	 *
	 * @since 1.0.0
	 *
	 * @var array  $configs   BoldGrid Library configurations.
	 * @var array  $libraries Available BoldGrid Library versions.
	 * @var object $load {
	 *     An object containing the highest BoldGrid Library version found to load.
	 *
	 *     @type string $product The product in which the library was found.
	 *                           Example: "plugin/plugin.php".
	 *     @type string $version The library version found.
	 *                           Example: "2.8.0.0".
	 * }
	 * @var string $path      The path to the BoldGrid Library to load.
	 *                        Specifically, the path to the parent directory of the vendor folder.
	 *                        Example: /home/user/public_html/wp-content/plugins/plugin
	 */
	private
		$configs,
		$libraries,
		$load,
		$path;

	/**
	 * Initialize class and set class properties.
	 *
	 * @since 1.0.0
	 *
	 * @param array $configs BoldGrid Library configurations.
	 */
	public function __construct( array $configs = array() ) {
		// Set the configuration array.
		$this->configs = $configs;

		// Build the registration class.
		$class = __NAMESPACE__ . '\\Registration\\' . ucfirst( $this->configs['type'] );

		$this->filesystemFixes();

		// Add hooks for registration.
		$this->registration = new $class( $this->configs['file'] );

		// Gets the available Library versions that can be loaded.
		$this->libraries = Option::get( 'library' );

		// Sets the BoldGrid Library version to load.
		$this->setLoad( $this->getLibraries() );

		// Sets the path to the Library files to load for themes/plugins.
		$this->setPath();

		// Add loaded Library path to configs.
		$this->configs['libraryDir'] = trailingslashit( $this->getPath() ) . 'vendor/boldgrid/library/';

		// Add loaded Library URL to configs.
		$this->configs['libraryUrl'] = plugin_dir_url( $this->configs['libraryDir'] ) . 'library/';

		// Initialize BoldGrid Library.
		$this->load( $this->configs['loader'] );
	}

	/**
	 * Determine the path to a product.
	 *
	 * What is the "path"?
	 * The path to a product is the parent directory of the vendor folder. So, the path we return is
	 * assumed to have a vendor folder.
	 *
	 * What is the "product"?
	 * The product is the product identifier, such as "plugin/plugin.php".
	 *
	 * The contents of this method were originall in self::setPath(), but have been moved here for
	 * reusability.
	 *
	 * @since 2.7.3
	 *
	 * @param  string $product The product identifier.
	 *                         Example: plugin/plugin.php
	 * @return string
	 */
	public static function determinePath( $product ) {
		// Loading from must use plugin directory?
		if ( ! is_file( $path = trailingslashit( WPMU_PLUGIN_DIR ) . $product ) ) {

			// Loading from plugin directory?
			if ( ! is_file( $path = trailingslashit( WP_PLUGIN_DIR ) . $product ) ) {

				// Loading from a parent theme directory?
				$path = get_template_directory() . '/inc/boldgrid-theme-framework/includes/theme';
			}
		}

		// Loading from framework path override directory?
		if ( defined( 'BGTFW_PATH' ) ) {
			$dir = ABSPATH . trim( BGTFW_PATH, '/' ) . '/includes';
			if ( is_dir( $dir . '/vendor/boldgrid/library' ) ) {
				$path = $dir . '/theme';
			}
		}

		$path = dirname( $path );

		return $path;
	}

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
	 * @todo Remove this method after an appropriate amount of time.  The requirement was removed in BGB on 10/10/2019.
	 */
	public function filesystemFixes() {
		require __DIR__ . '/Pluggable.php';
	}

	/**
	 * Sets the product and version of library to load.
	 *
	 * This check will loop through the available libraries and load the
	 * highest version found.  If an active plugin or theme contains a
	 * git branch instead of a tagged version, then that library will be
	 * loaded instead.
	 *
	 * @since  1.0.0
	 *
	 * @param  array  $libraries {
	 *     An array of available library versions.
	 *
	 *     The array keys represent a product, and the values represent the library version.
	 *     Example: [plugin/plugin.php] => 2.8.0.0
	 * }
	 * @return object $load      The product and version to load.
	 */
	public function setLoad( $libraries ) {
		$load = false;
		$product = false;
		foreach( $libraries as $name => $version ) {

			// Check for branch versions normalized (dev/master).
			if ( strpos( $version, 'dev' ) !== false ) {
				$load = $version;
				$product = $name;
				break;
			}

			// Check for highest loaded version.
			if ( version_compare( $load, $version ) === -1 ) {
				// Get and validate the path. It must have the library in it.
				$path = self::determinePath( $name );
				if ( ! $this->isValidPath( $path ) ) {
					continue;
				}

				$load = $version;
				$product = $name;
			}
		}

		return $this->load = ( object ) array( 'product' => $product, 'version' => $load );
	}

	/**
	 * Sets the path class property.
	 *
	 * @since  1.0.0
	 *
	 * @return string $path Path to the library to load.
	 */
	public function setPath() {
		$found = $this->getLoad();
		$path = false;

		if ( ! empty( $found->product ) ) {
			$path = self::determinePath( $found->product );
		}

		return $this->path = $path;
	}

	/**
	 * Adds the Library paths to the autoloader.
	 *
	 * @since  1.0.0
	 *
	 * @param  object $loader Autoloader instance.
	 *
	 * @return null
	 */
	public function load( $loader ) {
		if ( ! empty( $this->configs['libraryDir'] ) ) {
			$library = $this->configs['libraryDir'] . 'src/Library';

			// Only create a single instance of the BoldGrid Library Start.
			if ( did_action( 'Boldgrid\Library\Library\Start' ) === 0 ) {
				do_action( 'Boldgrid\Library\Library\Start', $library );

				// Check dir and add PSR-4 dir of the BoldGrid Library to autoload.
				if ( is_dir( $library ) ) {
					$loader->addPsr4( 'Boldgrid\\Library\\Library\\', $library );
					$load = new \Boldgrid\Library\Library\Start( $this->configs );
					$load->init();
				}
			}
		}
	}

	/**
	 * Get the libraries class property.
	 *
	 * @since  1.0.0
	 *
	 * @return array $libraries Available libraries.
	 */
	public function getLibraries() {
		return $this->libraries;
	}

	/**
	 * Get the load class property.
	 *
	 * @since  1.0.0
	 *
	 * @return object $load Highest library version found to load.
	 */
	public function getLoad() {
		return $this->load;
	}

	/**
	 * Get the path class property.
	 *
	 * @since  1.0.0
	 *
	 * @return string $path path to the library to load.
	 */
	public function getPath() {
		return $this->path;
	}

	/**
	 * Determine whether or not a library path is valid.
	 *
	 * If you look at the docblocks for self::determinePath(), the path is, "[...] the parent directory
	 * of the vendor folder. So, the path we return is assumed to have a vendor folder".
	 *
	 * This method simply ensures the path indeed has a vendor/boldgrid/library folder. This is VITAL
	 * because if a plugin is deleted via the filesystem, the entry will still remain in the boldgrid_settings
	 * option, and it will crash other plugins requiring it.
	 *
	 * @since 2.12.2
	 *
	 * @return bool
	 */
	public function isValidPath( $path ) {
		return Version::getWpFilesystem()->exists( trailingslashit( $path ) . 'vendor/boldgrid/library' );
	}
}
