<?php
/**
 * BoldGrid Source Code
 *
 * @package Boldgrid_Inspirations_Deploy_Post
 * @copyright BoldGrid.com
 * @version $Id$
 * @author BoldGrid.com <wpb@boldgrid.com>
 */

/**
 * The BoldGrid Inspiration Deploy Post class.
 *
 * This class is responsible for monitoring the status of the deployment process.
 *
 * @since 1.7.0
 */
class Boldgrid_Inspirations_Deploy_Status {
	/**
	 * The expiration time of our transient.
	 *
	 * When a deployment starts, we set a transient to indicate this. A transient is used because
	 * if an option was set instead and some fatal error prevented us from finishing the install,
	 * we don't want the system to indefinitely think we're in the middle of installing a site.
	 *
	 * Current transient expiration is set to 3 minutes.
	 *
	 * @since 1.7.0
	 * @access private
	 * @var int
	 */
	private $transient_expiration = 180;

	/**
	 * The transient name.
	 *
	 * @since 1.7.0
	 * @access private
	 * @var string
	 */
	private $transient_name = 'boldgrid_inspirations_full_deploy';

	/**
	 * Whether or not we are in the middle of deploying a site.
	 *
	 * @since 1.7.0
	 *
	 * @return bool
	 */
	public function is_deploying() {
		return false !== get_transient( $this->transient_name );
	}

	/**
	 * Flag that we are beginning a deployment.
	 *
	 * @since 1.7.0
	 */
	public function start() {
		set_transient( $this->transient_name, time(), $this->transient_expiration );
	}

	/**
	 * Flag that we have completed a deployment.
	 *
	 * @since 1.7.0
	 */
	public function stop() {
		delete_transient( $this->transient_name );
	}
}
