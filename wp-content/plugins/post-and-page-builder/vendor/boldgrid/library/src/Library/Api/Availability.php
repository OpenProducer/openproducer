<?php
/**
 * BoldGrid Library API Availability.
 *
 * @package Boldgrid\Library
 * @subpackage \Library\Api
 *
 * @version 1.0.0
 * @author BoldGrid <wpb@boldgrid.com>
 */

namespace Boldgrid\Library\Library\Api;

use \WP_Http;

/**
 * BoldGrid Library API Availability class.
 *
 * This class is responsible for checking that a server is
 * able to make calls to the API, and that the API is responsive.
 *
 * @since 1.0.0
 */
class Availability {
	/**
	 * @var array $url URL to make call to.
	 * @var array Request parameters
	 */
	private
		$available,
		$url;

	/**
	 * Initialize class and set class properties.
	 *
	 * @since 1.0.0
	 *
	 * @param string $url The API URL to test.
	 */
	public function __construct( $url ) {
		$this->setUrl( $url );
		$this->setAvailable();
	}

	/**
	 * Sets the API host URL as url class property.
	 *
	 * @since  1.0.0
	 *
	 * @param  string $url The URL to set.
	 *
	 * @return string $url The url class property.
	 */
	protected function setUrl( $url ) {
		return $this->url = $url;
	}

	/**
	 * Sets the availablity status of API host.
	 *
	 * @since  1.0.0
	 *
	 * @return bool $available The available class property.
	 */
	protected function setAvailable() {
		return $this->available = $this->checkAvailability();
	}

	/**
	 * Checks that the API can be reached.
	 *
	 * @since  1.0.0
	 *
	 * @return bool
	 */
	private function checkAvailability() {
		// Get the boldgrid_available transient.
		$available = get_site_transient( 'boldgrid_available' );

		// No transient was found.
		$wp_http = new WP_Http();
		$url = $this->getUrl();

		// Check that calling server is not blocked locally.
		if ( $wp_http->block_request( $url ) === false ) {
			$available = 1;
		}

		// Update the boldgrid_available transient.
		set_site_transient( 'boldgrid_available', ( int ) $available, 2 * MINUTE_IN_SECONDS );

		return $available;
	}

	/**
	 * Get the API Host URL that is being tested from the url class property.
	 *
	 * @since  1.0.0
	 *
	 * @return string $url The API url to test availability of.
	 */
	public function getUrl() {
		return $this->url;
	}

	/**
	 * Get the API availability status from availability class property.
	 *
	 * @since  1.0.0
	 *
	 * @return bool  $available The available class property.
	 */
	public function getAvailable() {
		return $this->available;
	}
}
