<?php
/**
 * BoldGrid Source Code
 *
 * @package Boldgrid_Inspirations_Easy_Attachment_Preview_Size
 * @copyright BoldGrid.com
 * @version $Id$
 * @author BoldGrid.com <wpb@boldgrid.com>
 */

/**
 * BoldGrid Inspirations Easy Attachment Preview Size.
 */
class Boldgrid_Inspirations_Easy_Attachment_Preview_Size {

	/**
	 * Add hooks
	 */
	public function add_hooks() {
		if ( is_admin() ) {
			add_action( 'admin_enqueue_scripts',
				array (
					$this,
					'admin_enqueue_scripts'
				) );
		}
	}

	/**
	 */
	public function admin_enqueue_scripts( $hook ) {
		$allowed_hooks = array (
			'post.php',
			'media-upload-popup'
		);

		// Abort if necessary.
		if ( ! in_array( $hook, $allowed_hooks ) ) {
			return;
		}

		// Add our javascript.
		wp_enqueue_script( 'easy-attachment-preview-size',
			plugins_url( 'assets/js/easy-attachment-preview-size.js',
				BOLDGRID_BASE_DIR . '/boldgrid-inspirations.php' ), array (), BOLDGRID_INSPIRATIONS_VERSION,
			true );

		// Add our css.
		wp_register_style( 'easy-attachment-preview-size',
			plugins_url( '/assets/css/easy-attachment-preview-size.css',
				BOLDGRID_BASE_DIR . '/boldgrid-inspirations.php' ), array (), BOLDGRID_INSPIRATIONS_VERSION );
		wp_enqueue_style( 'easy-attachment-preview-size' );
	}
}