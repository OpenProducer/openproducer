<?php
/**
 * BoldGrid Source Code
 *
 * @package Boldgrid_Inspirations_Cache
 * @copyright BoldGrid.com
 * @version $Id$
 * @author BoldGrid.com <wpb@boldgrid.com>
 */

/**
 * BoldGrid Cache class.
 *
 * This class allows for caching of assets, such as images and other files.
 * Currently, it is only for use on our preview servers.
 *
 * @since 1.1.2
 */
class Boldgrid_Inspirations_Cache {
	/**
	 * Enable asset cache.
	 *
	 * @since 1.1.2
	 * @access private
	 * @var bool
	 */
	private $enable_asset_cache = false;

	/**
	 * Cache folder path.
	 *
	 * @since 1.1.2
	 * @access private
	 * @var string|null
	 */
	private $cache_folder = null;

	/**
	 * Is cache enabled?
	 *
	 * @since 1.1.2
	 *
	 * @return bool
	 */
	public function is_cache_enabled() {
		return $this->enable_asset_cache;
	}

	/**
	 * Constructor.
	 *
	 * @since 1.1.2
	 */
	public function __construct() {
		// Configure asset cache.
		$this->configure_asset_cache();
	}

	/**
	 * Configure asset file cache.
	 *
	 * @since 1.1.2
	 *
	 * @return null
	 */
	public function configure_asset_cache() {
		// Get the HOME environment variable.
		$env_home = getenv( 'HOME' );

		// Locate the home directory by environment variable or use parent of ABSPATH.
		$home_dir = ( ! empty( $env_home ) ? $env_home : dirname( ABSPATH ) );

		// Trim any trailing slash (or backslash in Windows).
		$home_dir = rtrim( $home_dir, DIRECTORY_SEPARATOR );

		// If the home directory is not defined, not a directory or not writable, then disable cache.
		if ( empty( $home_dir ) || ! is_dir( $home_dir ) ||
			 ! is_writable( $home_dir ) ) {
			$this->enable_asset_cache = false;

			return;
		}

		// Set the cache directory path.
		$this->cache_folder = $home_dir . '/boldgrid-asset-cache';

		// Create the cache directory if it does not exist.
		if ( ! file_exists( $this->cache_folder ) ) {
			mkdir( $this->cache_folder, 0700 );
		}

		// Enable cache only if the cache folder is defined, is a directory, and is writable.
		$this->enable_asset_cache = ( ! empty( $this->cache_folder ) &&
			 is_dir( $this->cache_folder ) && is_writable( $this->cache_folder ) );
	}

	/**
	 * Get shard directory.
	 *
	 * Returns a shard cache directory based on the first two characters of the cache id.
	 *
	 * @since 1.1.2
	 *
	 * @param string $cache_id Cache id string.
	 * @return string|bool Cache Directory path, or FALSE on failure.
	 */
	public function get_shard_directory( $cache_id ) {
		// Validate parent cache directory.
		if ( empty( $this->cache_folder ) || ! is_dir( $this->cache_folder ) ||
			 ! is_writable( $this->cache_folder ) ) {
			return false;
		}

		// Validate the cache id.
		if ( empty( $cache_id ) || strlen( $cache_id ) < 2 ) {
			return false;
		}

		// Get the shard directory name.
		$shard_name = substr( $cache_id, 0, 2 );

		// Set the shard directory path.
		$shard_directory_path = $this->cache_folder . '/' . $shard_name;

		// Ensure the directory exists.
		if ( ! is_dir( $shard_directory_path ) ) {
			mkdir( $shard_directory_path, 0700 );
		}

		// Validate shard cache directory.
		if ( ! is_dir( $shard_directory_path ) ||
			 ! is_writable( $shard_directory_path ) ) {
			return false;
		}

		return $shard_directory_path;
	}

	/**
	 * Get cache files by cache id.
	 *
	 * @since 1.1.2
	 *
	 * @param string $cache_id A cache id string.
	 * @return array|bool The file headers and body, in an array, or FALSE on error or not in cache.
	 */
	public function get_cache_files( $cache_id ) {
		// If caching is not enabled, abort.
		if ( ! $this->enable_asset_cache ) {
			return false;
		}

		// Try to get the $response from cache.
		if ( ! empty( $cache_id ) ) {
			// Get the shard cache directory.
			$cache_directory = $this->get_shard_directory( $cache_id );

			// Get the cache header and body file paths.
			$cache_header_path = $cache_directory . '/' . $cache_id . '.txt';
			$cache_body_path = $cache_directory . '/' . $cache_id . '.dat';

			// Check if the shard cache directories exist.
			$header_file_exists = file_exists( $cache_header_path );
			$body_file_exists = file_exists( $cache_body_path );
		}

		if ( $header_file_exists && $body_file_exists ) {
			/* Use cache. */

			// Read the header and body files into $response array.
			$response['headers'] = file_get_contents( $cache_header_path );
			$response['body'] = file_get_contents( $cache_body_path );

			// JSON decode the response headers.
			$response['headers'] = json_decode( $response['headers'], true );

			// If the cache file is invalid, then delete it and log.
			if ( empty( $response['headers'] ) || empty( $response['body'] ) ) {
				unlink( $cache_header_path );
				unlink( $cache_body_path );

				// LOG.
				error_log(
					__METHOD__ . ': Notice: Cache file was deleted; data was invalid.  ' . print_r(
						array (
							'$cache_id' => $cache_id,
							'$cache_header_path' => $cache_header_path,
							'$cache_body_path' => $cache_body_path
						), true ) );

				return false;
			}

			// Return the cached response.
			return $response;
		}

		return false;
	}

	/**
	 * Save cache files.
	 *
	 * @since 1.1.2
	 *
	 * @param string $cache_id A cache id.
	 * @param array $response An array containing the download file headers and body.
	 *
	 * @return bool Success of the cache file saves.
	 */
	public function save_cache_files( $cache_id, $response ) {
		// If cache is not enabled, abort.
		if ( ! $this->enable_asset_cache ) {
			return false;
		}

		// Validate input.
		if ( empty( $cache_id ) || empty( $response['headers'] ) || empty( $response['body'] ) ) {
			return false;
		}

		// Save the cache file.
		// Get the shard cache directory.
		$cache_directory = $this->get_shard_directory( $cache_id );

		// Validate cache directory.
		if ( empty( $cache_directory ) || ! is_writable( $cache_directory ) ) {
			error_log(
				__METHOD__ . ': Error: Cache directory "' . $cache_directory . '" is not writable.' );

			return false;
		}

		// Set the cache header and body file paths.
		$cache_header_path = $cache_directory . '/' . $cache_id . '.txt';
		$cache_body_path = $cache_directory . '/' . $cache_id . '.dat';

		// If the cache files already exist, abort.
		if ( file_exists( $cache_header_path ) && file_exists( $cache_body_path ) ) {
			return false;
		}

		// Remove some headers.
		unset( $response['headers']['expires'] );
		unset( $response['headers']['set-cookie'] );

		// JSON encode the response headers.
		$response_headers = wp_json_encode( $response['headers'] );

		// Save response header to a cache file.
		$cache_header_written = file_put_contents( $cache_header_path, $response_headers );

		unset( $response_headers );

		// Check for write failure.
		if ( ! $cache_header_written ) {
			$asset_id = $response['headers']['z-asset-id'] ? $response['headers']['z-asset-id'] : 'UNKNOWN';

			error_log(
				__METHOD__ . ': Notice: Error writing cache header file "' . $cache_header_path .
					 '" for asset id "' . $asset_id . '".' );

			return false;
		}

		// Save the response body to a cache file.
		$cache_body_written = file_put_contents( $cache_body_path, $response['body'] );

		// Check for write failure.
		if ( ! $cache_body_written ) {
			$asset_id = $response['headers']['z-asset-id'] ? $response['headers']['z-asset-id'] : 'UNKNOWN';

			error_log(
				__METHOD__ . ': Notice: Error writing cache body file "' . $cache_body_path .
					 '" for asset id "' . $asset_id . '".' );

			return false;
		}

		return true;
	}

	/**
	 * Create unique cache id.
	 *
	 * A cache id is an MD5 representation of an array passed as a parameter. The input array is
	 * usually the request headers, so we need to remove certain array keys before generating the id.
	 *
	 * @since 1.1.2
	 *
	 * @param array $cache_array An array of data for the request.
	 * @return string|false Returns a cache id string, or FALSE otherwise.
	 */
	public function set_cache_id( $cache_array ) {
		// Validate input.
		if ( empty( $cache_array ) ) {
			return false;
		}

		// Remove unneeded array elements.
		if ( isset( $cache_array['headers'] ) ) {
			unset( $cache_array['headers']['server'] );
			unset( $cache_array['headers']['date'] );
			unset( $cache_array['headers']['connection'] );
			unset( $cache_array['headers']['set-cookie'] );
			unset( $cache_array['headers']['cache-control'] );
			unset( $cache_array['headers']['pragma'] );
			unset( $cache_array['headers']['expires'] );
			unset( $cache_array['headers']['access-control-allow-methods'] );
			unset( $cache_array['headers']['access-control-allow-origin'] );
		}

		if ( isset( $cache_array['download_params'] ) ) {
			unset( $cache_array['download_params']['key'] );
			unset( $cache_array['download_params']['boldgrid_connect_key'] );
			unset( $cache_array['download_params']['is_redownload'] );
			unset( $cache_array['download_params']['user_transaction_item_id'] );
		}

		if ( isset( $cache_array['arguments']['timeout'] ) ) {
			unset( $cache_array['arguments']['timeout'] );
		}

		unset( $cache_array['download_type'] );
		unset( $cache_array['images_array_key'] );
		unset( $cache_array['post_id'] );
		unset( $cache_array['response'] );
		unset( $cache_array['cookies'] );

		// Remove key from the download url.
		if ( isset( $cache_array['download_url'] ) ) {
			$cache_array['download_url'] = preg_replace( '/&key=[0-9a-f]*/', '',
				$cache_array['download_url'] );
		}

		// For WordPress calls via GET and POST.
		if ( isset( $cache_array['method'] ) ) {
			// GET method.
			if ( 'get' === $cache_array['method'] ) {
				$cache_array['url'] = preg_replace( '/&key=[0-9a-f]*/', '', $cache_array['url'] );
			} else {
				// POST method.
				if ( 'post' === $cache_array['method'] ) {
					unset( $cache_array['arguments']['body']['key'] );

					if ( isset( $cache_array['arguments']['body']['boldgrid_connect_key'] ) ) {
						$cache_array['arguments']['body']['boldgrid_connect_key'];
					}
				}
			}
		} else {
			// If body was passed by accident, then remove it.
			if ( isset( $cache_array['body'] ) ) {
				unset( $cache_array['body'] );
			}
		}

		// Set cache_id.
		$cache_id = md5( print_r( $cache_array, true ) );

		// Return cache_id.
		return $cache_id;
	}
}
