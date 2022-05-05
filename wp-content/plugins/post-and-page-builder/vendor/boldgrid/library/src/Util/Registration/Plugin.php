<?php
/**
 * BoldGrid Library Plugin Registration Utility
 *
 * @package Boldgrid\Library
 * @subpackage \Util\Registration
 *
 * @version 1.0.0
 * @author BoldGrid <wpb@boldgrid.com>
 */

namespace Boldgrid\Library\Util\Registration;

use Boldgrid\Library\Util;

/**
 * BoldGrid Library Plugin Registration Class.
 *
 * This class is responsible for handling the registration of
 * a plugin and it's associated dependency version.
 *
 * @since 1.0.0
 */
class Plugin extends Util\Registration {

	/**
	 * Initialize class and set class properties.
	 *
	 * @since 1.0.0
	 *
	 * @param string $product The path of the product.
	 */
	public function __construct( $product ) {
		parent::init( $product );
		register_activation_hook( $this->getProduct(), array( $this, 'register' ) );
		register_deactivation_hook( $this->getProduct(), array( $this, 'deregister' ) );
	}
}
