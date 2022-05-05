<?php
/**
 * Manage javascript, css, and images. Front end assets.
 *
 * @package Boldgrid\Library
 * @subpackage \Library\Library\Asset
 *
 * @version 2.4.0
 * @author BoldGrid <wpb@boldgrid.com>
 */

namespace Boldgrid\Library\Library;

/**
* Manage javascript, css, and images. Front end assets.
 *
 * @since 2.4.0.
 */
class Asset {

	/**
	 * Add all filters for this class.
	 *
	 * @since 2.4.0
	 */
	public function __construct() {
		Filter::add( $this );
	}

	/**
	 * Add front end scripts.
	 *
	 * @since 2.7.5
	 *
	 * @hook wp_enqueue_scripts
	 */
	public function addWpScripts() {
		if ( is_user_logged_in() ) {
			// Enqueue on front end for logged in users so they get BoldGrid logo in admin bar.
			$this->EnqueueAdminIcon();
		}
	}

	/**
	 * Add styles for all admin pages.
	 *
	 * @since 2.4.0
	 *
	 * @hook admin_enqueue_scripts
	 */
	public function addStyles() {
		wp_enqueue_style( 'bglib-admin',
			Configs::get( 'libraryUrl' ) . 'src/assets/css/admin.css' );

		$this->EnqueueAdminIcon();
	}

	/**
	 * Enqueue admin icon css.
	 *
	 * Used to show BoldGrid font / logo. Separate method created for reusability on front end vs
	 * back end.
	 *
	 * @since 2.7.5
	 */
	public function EnqueueAdminIcon() {
		wp_enqueue_style( 'bglib-admin-icon',
			Configs::get( 'libraryUrl' ) . 'src/assets/css/admin-icon.css' );
	}
}
 ?>
