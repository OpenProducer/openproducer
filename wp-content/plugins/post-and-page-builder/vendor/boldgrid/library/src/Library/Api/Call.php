<?php
/**
 * BoldGrid Library API Call.
 *
 * @package Boldgrid\Library
 * @subpackage \Library\Api
 *
 * @version 1.0.0
 * @author BoldGrid <wpb@boldgrid.com>
 */

namespace Boldgrid\Library\Library\Api;

use Boldgrid\Library\Library\Configs;

/**
 * BoldGrid Library API Call Class.
 *
 * This class is responsible for making API calls
 * and returning back the appropriate responses.
 *
 * @since 1.0.0
 */
class Call {

	/**
	 * @access private
	 *
	 * @var array  $url      URL to make call to.
	 * @var string $key      The API key for request.
	 * @var string $siteHash The site hash for request.
	 * @var array  $args     Request parameters
	 * @var object $response The API call response object.
	 * @var string $error    The API call error message.
	 */
	private
		$url,
		$key,
		$siteHash,
		$args,
		$response,
		$error;

	/**
	 * Request method.
	 *
	 * @since 2.2.0
	 * @access private
	 *
	 * @var string
	 */
	private $method;

	/**
	 * Initialize class and set class properties.
	 *
	 * @since 1.0.0
	 *
	 * @param string $url  The URL to make API calls to.
	 * @param array  $args The arguments to pass to API call.
	 */
	public function __construct( $url, array $args = array(), $method = 'post' ) {
		$this->setKey();
		$this->setSiteHash();
		$this->setUrl( $url );
		$this->setArgs( $args );
		$this->setMethod( $method );
		$availability = new Availability( Configs::get( 'api' ) );
		if ( $availability->getAvailable() ) {
			$this->call();
		} else {
			$this->error = __( 'The API was not able to be reached from this server!', 'boldgrid-library' );
		}
	}

	/**
	 * Sets the url class property.
	 *
	 * @since  1.0.0
	 *
	 * @param  string $url The url that the API call will be made to.
	 *
	 * @return string $url The url class property.
	 */
	protected function setUrl( $url ) {
		return $this->url = $url;
	}

	/**
	 * Sets the key class property.
	 *
	 * @since  1.0.0
	 *
	 * @return mixed $key The key string or false if not found.
	 */
	protected function setKey() {
		return $this->key = get_option( 'boldgrid_api_key' );
	}

	/**
	 * Sets the siteHash class property.
	 *
	 * @since  1.0.0
	 *
	 * @return mixed $siteHash The site hash string or false if not found.
	 */
	protected function setSiteHash() {
		return $this->siteHash = get_option( 'boldgrid_site_hash' );
	}

	/**
	 * Sets the args class property.
	 *
	 * These are the arguments being passed for the API call parameters.
	 *
	 * @since  1.0.0
	 *
	 * @param  array $args API call arguments.
	 *
	 * @return array $args The args class property.
	 */
	protected function setArgs( $args ) {
		// Check for an API key being stored.
		if ( $this->getKey() ) {
			$args = wp_parse_args( $args, array( 'key' => $this->getKey() ) );
		}

		// Check for a site hash being stored.
		if ( $this->getSiteHash() ) {
			$args = wp_parse_args( $args, array( 'site_hash' => $this->getSiteHash() ) );
		}

		return $this->args = array(
			'timeout' => 15, // Default timeout is 5 seconds, change to 15.
			'body' => $args
		);
	}

	/**
	 * Sets the request method.
	 *
	 * @since 2.2.0
	 *
	 * @param  string $method Request method.
	 * @return string
	 */
	protected function setMethod( $method ) {
		return $this->method = $method;
	}

	/**
	 * Makes the API call.
	 *
	 * @since  1.0.0
	 *
	 * @return bool  Was the API call successful?
	 */
	private function call() {
		// Make the request.
		if ( 'get' === $this->method ) {
			$response = wp_remote_get( $this->url, $this->args );
		} else {
			$response = wp_remote_post( $this->url, $this->args );
		}

		// Decode the response and set class property.
		$this->response = json_decode( wp_remote_retrieve_body( $response ) );

		// Validate the raw response.
		if ( $this->validateResponse( $response ) === false ) {
			return false;
		}

		// Response should be an object.
		if ( ! is_object( $this->response ) ) {
			$this->error = __( 'An invalid response was returned.', 'boldgrid-library' );
			return false;
		}

		return true;
	}

	/**
	 * Validates the API response.
	 *
	 * @since  1.0.0
	 *
	 * @param  object $response API response object.
	 *
	 * @return bool
	 */
	private function validateResponse( $response ) {

		// Make sure WordPress errors are handled.
		if ( is_wp_error( $response ) ) {
			$this->error = $response->get_error_message();
			return false;
		}

		// Check for 200 response code from server.
		$responseCode = wp_remote_retrieve_response_code( $response );
		if ( false === strstr( $responseCode, '200' ) ) {
			$responseMessage = wp_remote_retrieve_response_message( $response );
			$this->error = "{$responseCode} {$responseMessage}";
			return false;
		}

		// Check for nested status code not being successful.
		if ( isset( $this->response->status ) && 200 !== $this->response->status ) {
			$this->error = $this->response->result->data;
			return false;
		}

		return true;
	}

	/**
	 * Gets the API Key as the key class property.
	 *
	 * @since  1.0.0
	 *
	 * @return string $key The key class property.
	 */
	public function getKey() {
		return $this->key;
	}

	/**
	 * Gets the Site Hash as the siteHash class property.
	 *
	 * @since  1.0.0
	 *
	 * @return string $siteHash The siteHash class property.
	 */
	public function getSiteHash() {
		return $this->siteHash;
	}

	/**
	 * Gets the API base URL.
	 *
	 * @since  1.0.0
	 *
	 * @return string $url The API base URL as url class property.
	 */
	private function getUrl() {
		return $this->url;
	}

	/**
	 * Gets the error messages.
	 *
	 * @since  1.0.0
	 *
	 * @return string $error Error message for call as response class property.
	 */
	public function getError() {
		return $this->error;
	}

	/**
	 * Gets the API response message.
	 *
	 * @since  1.0.0
	 *
	 * @return mixed $response Success response as the response class property.
	 */
	public function getResponse() {
		return $this->response;
	}

}
