<?php
/**
 * BoldGrid Library Registration Utility
 *
 * @package Boldgrid\Library
 * @subpackage \Util\Registration
 *
 * @version 1.0.0
 * @author BoldGrid <wpb@boldgrid.com>
 */

namespace Boldgrid\Library\Util;

/**
 * BoldGrid Library Registration Class.
 *
 * This class is responsible for handling the registration of
 * a product and it's associated dependency version.
 *
 * @since 1.0.0
 */
class Registration implements Registration\RegistrationInterface {

	/**
	 * @access protected
	 *
	 * @var string $product    The product identifier.
	 * @var string $dependency The dependency to get the version of.
	 * @var array  $libraries  Libraries stored in options.
	 */
	protected
		$product,
		$dependency,
		$libraries;

	/**
	 * Initialize class and set class properties.
	 *
	 * @since  1.0.0
	 *
	 * @param  string $product     The path of the product.
	 * @param  string $dependency The dependency relied on by the product.
	 *
	 * @return null
	 */
	protected function init( $product, $dependency = 'boldgrid/library' ) {
		$this->product = $product;
		$this->dependency = $dependency;
		Option::init();
		$this->libraries = Option::get( 'library' );
		$this->verify();
	}

	/**
	 * Register the product in WordPress options.
	 *
	 * @since  1.0.0
	 *
	 * @return null
	 */
	public function register() {

		// Check the dependency version.
		$version = new Version( $this->getDependency(), $this->product );
		Option::set( $this->getProduct(), $version->getVersion() );
	}

	/**
	 * Deregister the product in WordPress options.
	 *
	 * @since  1.0.0
	 *
	 * @return null
	 */
	public function deregister() {
		Option::delete( $this->getProduct() );
	}

	/**
	 * Verify the product is found in the library option, or register it
	 * if it's not found.
	 *
	 * @since  1.0.0
	 *
	 * @return null
	 */
	public function verify() {
		if ( ! isset( $this->libraries[ $this->getProduct() ] ) ) {
			$this->register();
		}
	}

	/**
	 * Get the product class property.
	 *
	 * @since  1.0.0
	 *
	 * @return string $product The product class property.
	 */
	public function getProduct() {
		return $this->product;
	}

	/**
	 * Get the dependency class property.
	 *
	 * @since  1.0.0
	 *
	 * @return string $dependency The dependency class property.
	 */
	public function getDependency() {
		return $this->dependency;
	}
}
