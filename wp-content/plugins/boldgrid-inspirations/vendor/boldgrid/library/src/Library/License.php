<?php
/**
 * BoldGrid Library License Class
 *
 * @package Boldgrid\Library
 * @subpackage \Library\License
 *
 * @version 1.0.0
 * @author BoldGrid <wpb@boldgrid.com>
 */

namespace Boldgrid\Library\Library;

use Boldgrid\Library\Library;

/**
 * BoldGrid Library License Class.
 *
 * This class is responsible for calling the API and retrieving the
 * remote license data required for licensed plugins to function.
 *
 * @since 1.0.0
 */
class License {
	/**
	 * @access private
	 *
	 * @since 1.0.0
	 *
	 * @var array  $key           Transient data key.
	 * @var object $license       BoldGrid license details.
	 * @var string $licenseString A string representing the type of license, such
	 *                            as "Free" or "Premium".
	 * @var object $data          BoldGrid license data.
	 */
	private
		$key,
		$license,
		$licenseString,
		$data;

	/**
	 * API version number.
	 *
	 * @since 2.4.0
	 *
	 * @var int
	 */
	private $apiVersion = 2;

	/**
	 * Initialize class and set class properties.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		$this->key = $this->setKey();

		$this->initLicense();

		Filter::add( $this );
	}

	/**
	 * Handle ajax request to clear license data.
	 *
	 * @since 2.2.0
	 *
	 * @hook: wp_ajax_bg_clear_license
	 */
	public function ajaxClear() {
		$plugin = ! empty( $_POST['plugin'] ) ? sanitize_text_field( $_POST['plugin'] ) : null;

		if ( empty( $plugin ) ) {
			wp_send_json_error( __( 'Unknown plugin.', 'boldgrid-library' ) );
		}

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( __( 'Access denied.', 'boldgrid-library' ) );
		}

		$success = $this->clearTransient();
		if ( ! $success ) {
			wp_send_json_error( array(
				'string' => sprintf(
					// translators: 1 The name of a transient that we were unable to delete.
					__( 'Failed to clear license data. Unable to delete site transient "%1$s".', 'boldgrid-library' ),
					$this->getKey()
				),
			));
		}

		$this->initLicense();

		$return = array(
			'isPremium' => $this->isPremium( $plugin ),
			'string' => $this->licenseString,
		);

		wp_send_json_success( $return );
	}

	/**
	 * Register scripts.
	 *
	 * @since 2.2.0
	 *
	 * @hook: admin_enqueue_scripts
	 */
	public function registerScripts() {
		$handle = 'bglib-license';

		wp_register_script(
			$handle,
			Library\Configs::get( 'libraryUrl' ) . 'src/assets/js/license.js',
			'jQuery'
		);

		$translations = array(
			'unknownError' => __( 'Unknown error', 'boldgrid-library' ),
		);

		wp_localize_script( $handle, 'bglibLicense', $translations );
	}

	/**
	 * Set key class property.
	 *
	 * This is the key used to access the transient data stored.
	 *
	 * @since  1.0.0
	 *
	 * @return string $key The key class property.
	 */
	private function setKey() {
		return $this->key = sanitize_key( 'bg_license_data' );
	}

	/**
	 * Set the license class property.
	 *
	 * @since  1.0.0
	 *
	 * @return mixed $license The response object or error string of call.
	 */
	private function setLicense() {
		if ( ! $this->getApiKey() ) {
			$license = 'Missing Connect Key';
		} else if ( ! ( $license = $this->getTransient() ) || ! $this->isVersionValid( $license ) ) {
			delete_site_transient( $this->getKey() );
			$license = $this->getRemoteLicense();
		}

		return $this->license = $license;
	}

	/**
	 * Sets the transient for the license data.
	 *
	 * @since  1.0.0
	 *
	 * @return bool  Was the transient successfully set?
	 */
	private function setTransient() {
		return ! $this->getTransient() && set_site_transient( $this->getKey(), $this->getLicense(), $this->getExpiration( $this->getData() ) );
	}

	/**
	 * Check if the current license is valid.
	 *
	 * Is valid if the refresh-by timestamp is not more than 1 day past due.
	 *
	 * @since  1.0.0
	 *
	 * @return mixed  Checks if the license data is available.
	 */
	private function isValid() {
		$data = $this->getTransient();
		$valid = array( 'key', 'cipher', 'iv', 'data' );
		if ( is_object( $data ) ) {
			$props = array_keys( get_object_vars( $data ) );
			$valid = array_diff( $valid, $props );
		}

		return empty( $valid );
	}

	/**
	 * Set the data class property.
	 *
	 * @since  1.0.0
	 *
	 * @return object $data The license data class property.
	 */
	private function setData() {
		if ( $license = $this->getLicense() ) {
			$data = json_decode(
				openssl_decrypt(
					$license->data,
					$license->cipher,
					$license->key,
					0,
					urldecode( $license->iv )
				)
			);
		}

		return $this->data = $data;
	}

	/**
	 * Displays the license notice in the WordPress admin.
	 *
	 * @since 1.0.0
	 */
	protected function licenseNotice() {
		if ( ! $this->isValid() ) {
			new Notice( 'invalidLicense' );
			// Disables the activate message.
			if ( isset( $_GET['activate'] ) ) {
				unset( $_GET['activate'] );
			}
		}
	}

	/**
	 * Runs plugin deactivation when deactivate_plugins is available.
	 *
	 * @since 1.0.0
	 *
	 * @hook: admin_init
	 */
	public function deactivate() {
		if ( ! $this->isValid() && Configs::get( 'licenseActivate' ) ) {
			delete_site_transient( $this->getKey() );
			deactivate_plugins( Configs::get( 'file' ) );
		}
	}

	/**
	 * Clear the transient containing license data.
	 *
	 * @since 2.2.0
	 *
	 * @hook: Boldgrid\Library\License\clearTransient
	 *
	 * @return bool True on success
	 */
	public function clearTransient() {
		return delete_site_transient( $this->getKey() );
	}

	/**
	 * Get the latest license data from the API server.
	 *
	 * The current API version is 2.
	 *
	 * @since  1.0.0
	 *
	 * @return mixed $response The remote license data object or error string.
	 */
	private function getRemoteLicense() {
		$call = new Api\Call( Configs::get( 'api' ) . '/api/plugin/getLicense?v=' .
			$this->apiVersion );

		if ( ! $response = $call->getError() ) {
			$response = $call->getResponse()->result->data;
		}

		return $response;
	}

	/**
	 * Get the key class property.
	 *
	 * @since  1.0.0
	 *
	 * @return string $key The key class property.
	 */
	public function getKey() {
		return $this->key;
	}

	/**
	 * Get the transient containing license data.
	 *
	 * @since  1.0.0
	 *
	 * @return mixed The API license data from the WordPress transient.
	 */
	protected function getTransient() {
		return get_site_transient( $this->getKey() );
	}

	/**
	 * Gets the expiration date from data passed.
	 *
	 * @since  1.0.0
	 *
	 * @param  object $data The data object from API license request.
	 *
	 * @return string       The expiration of the data stored.
	 */
	private function getExpiration( $data ) {
		return strtotime( $data->refreshBy ) - strtotime( 'now' );
	}

	/**
	 * Get the license class property.
	 *
	 * @since  1.0.0
	 *
	 * @return mixed $license The license class property.
	 */
	protected function getLicense() {
		return $this->license;
	}

	/**
	 * Return the license string.
	 *
	 * @since 2.2.0
	 *
	 * @return string
	 */
	public function getLicenseString() {
		return $this->licenseString;
	}

	/**
	 * Get our API Key.
	 *
	 * @since 2.6.0
	 *
	 * @hook Boldgrid\Library\License\getApiKey
	 */
	public function getApiKey() {
		return get_option( 'boldgrid_api_key' );
	}

	/**
	 * Get the data class property.
	 *
	 * @since  1.0.0
	 *
	 * @hook Boldgrid\Library\License\getData
	 *
	 * @return $data The data class property.
	 */
	public function getData() {
		return $this->data;
	}

	/**
	 * Get license validation.
	 *
	 * @nohook
	 *
	 * @return [type] [description]
	 */
	public function getValid() {
		return $this->isValid();
	}

	/**
	 * Init the license.
	 *
	 * This method was originally contained within the constructor, however it
	 * was pulled out as it was needed in additional places. For example, in cases
	 * we instantiate this class and then clear the license data, we may need to
	 * get fresh license data at that time by initializing the license again.
	 *
	 * @since 2.2.0
	 */
	public function initLicense() {
		$this->license = $this->setLicense();

		if ( is_object( $this->getLicense() ) ) {
			$this->data = $this->setData();
			$this->setTransient( $this->getData() );
			$licenseData = array( 'licenseData' => $this->getData() );
			Configs::set( $licenseData, Configs::get() );
		}
	}

	/**
	 * Checks if product is premium or free.
	 *
	 * @since 1.1.4
	 *
	 * @hook Boldgrid\Library\License\isPremium
	 *
	 * @param string $product Such as "boldgrid-backup" or "post-and-page-builder".
	 * @return bool
	 */
	public function isPremium( $product ) {
		$isPremium = isset( $this->getData()->$product );

		$this->licenseString = $isPremium ?
			__( 'Premium', 'boldgrid-connect' ) : __( 'Free', 'boldgrid-library' );

		return $isPremium;
	}

	/**
	 * Check if the license version and encoding is correct.
	 *
	 * The license data is valid (for the API version) if the "version" and "iv" properties are set,
	 * the decoded initialization vector (iv) is 16 characters in length, and the "version" is
	 * $this->apiVersion.
	 *
	 * @since 2.4.0
	 *
	 * @param Object $license Current license data.
	 *
	 * @return bool
	 */
	public function isVersionValid( $license ) {
		return ( ! empty( $license->version ) && ! empty( $license->iv ) &&
			16 === strlen( urldecode( $license->iv ) ) &&
			$this->apiVersion === $license->version );
	}
}
