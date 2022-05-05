<?php
/**
 * BoldGrid Library Registration Utility Interface
 *
 * @package Boldgrid\Library
 * @subpackage \Util\Registration
 *
 * @version 1.0.0
 * @author BoldGrid <wpb@boldgrid.com>
 */

namespace Boldgrid\Library\Util\Registration;

/**
 * BoldGrid Library Registration Interface
 *
 * This interface dictates how classes that implement it
 * should be registering and deregistering products.
 */
interface RegistrationInterface {

	/**
	 * Register the product in WordPress options.
	 *
	 * @since  1.0.0
	 *
	 * @return null
	 */
	public function register();

	/**
	 * Deregister the product in WordPress options.
	 *
	 * @since  1.0.0
	 *
	 * @return null
	 */
	public function deregister();

	/**
	 * Gets the product class property.
	 *
	 * @since  1.0.0
	 *
	 * @return string $product Product class property.
	 */
	public function getProduct();

	/**
	 * Get the dependency class property.
	 *
	 * @since  1.0.0
	 *
	 * @return string $dependency The dependency class property.
	 */
	public function getDependency();
}