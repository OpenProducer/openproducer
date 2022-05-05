<?php
/**
 * BoldGrid Library Theme Registration Utility
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
 * BoldGrid Library Theme Registration Class.
 *
 * This class is responsible for handling the registration of
 * a theme and it's associated dependency version.
 *
 * @since 1.0.0
 */
class Theme extends Util\Registration {

	/**
	 * Initialize class and set class properties.
	 *
	 * @since 1.0.0
	 *
	 * @param string $product The path of the product.
	 */
	public function __construct( $product ) {
		parent::init( get_template( $product ) );
		add_action( 'after_switch_theme', array( $this, 'register' ) );
		add_action( 'switch_theme', array( $this, 'deregister' ) );
	}
}
