<?php

/**
 * Boldgrid Source Code
 *
 * @package Boldgrid_Gallery_Editor
 * @copyright BoldGrid.com
 * @version $Id$
 * @author BoldGrid.com <wpb@boldgrid.com>
 *
 */

// Prevent direct calls
if ( ! defined( 'WPINC' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit();
}

/**
 * Boldgrid_Gallery_Editor class
 */
class Boldgrid_Gallery_Editor {

	/**
	 * This is a list of settings in the gallery that are not applicable to the display type
	 *
	 * @var array
	 */
	protected $display_inputs_invalid_opts = array (
		'default' => array (
			'speed'
		),
		'masonry' => array (
			'speed',
			'reflections'
		),
		'coverflow' => array (
			'columns',
			'captions',
			'captiontype',
			'gutterwidth',
			'hidecontrols',
			'class'
		)
	);

	/**
	 * Bold Grid Initialization process
	 */
	public function init() {
		if ( is_admin() ) {
			add_action( 'admin_print_footer_scripts',
				array (
					$this,
					'boldgrid_gallery_override'
				) );
			add_action( 'admin_enqueue_scripts',
				array (
					$this,
					'wc_gallery_boldgrid_enqueue_admin_scripts'
				) );
		}
	}

	/**
	 * Add the admin scripts
	 */
	public function wc_gallery_boldgrid_enqueue_admin_scripts() {
		$screen = get_current_screen();

		// Is this a 'page' or a 'post' ( IE not an 'attachment' ).
		$is_post = ( 'page' === $screen->post_type || 'post' === $screen->post_type );

		// Add styles to editor
		if ( 'post' == $screen->base && $is_post ) {
			add_editor_style( WC_GALLERY_PLUGIN_URL . 'includes/css/style.css' );
			add_editor_style( WC_GALLERY_PLUGIN_URL . 'boldgrid/assets/css/editor.css' );

			wp_register_script( 'boldgrid-gallery',
				WC_GALLERY_PLUGIN_URL . 'boldgrid/assets/js/editor.js', array (),
				BOLDGRID_GALLERY_VERSION, true );

			// Enqueing our own version of masonry due to compat issues in the editor.
			wp_register_script( 'boldgrid-gallery-masonry',
				WC_GALLERY_PLUGIN_URL . 'boldgrid/assets/js/masonry/masonry.min.js', array (),
				BOLDGRID_GALLERY_VERSION, true );

			wp_localize_script( 'boldgrid-gallery', 'BOLDGRIDGallery',
				$this->display_inputs_invalid_opts );
			wp_enqueue_script( 'boldgrid-gallery' );

			// Masonry
			wp_enqueue_script( 'wordpresscanvas-imagesloaded',
				WC_GALLERY_PLUGIN_URL . 'includes/js/imagesloaded.pkgd.min.js', array (), '4.1.0', true );
			wp_enqueue_script( 'wc-gallery', WC_GALLERY_PLUGIN_URL . 'includes/js/gallery.js',
				array (
					'jquery',
					'wordpresscanvas-imagesloaded',
					'boldgrid-gallery-masonry'
				), BOLDGRID_GALLERY_VERSION, true );
		}
	}

	/**
	 * These template files define what the gallery should look like within the tinyMCE editor
	 */
	public function boldgrid_gallery_override() {
		// Set $boldgrid_dir:
		$boldgrid_dir = realpath( BOLDGRID_GALLERY_PATH . '/boldgrid' );

		// Validate $boldgrid_dir:
		if ( empty( $boldgrid_dir ) ) {
			error_log(
				__METHOD__ . ': Error: Could not locate the BoldGrid Gallery include directory.' );

			return false;
		}

		// Print templates:
		include $boldgrid_dir . '/templates/masonry.php';
		include $boldgrid_dir . '/templates/carousel.php';
		include $boldgrid_dir . '/templates/owl-autowidth.php';
		include $boldgrid_dir . '/templates/owl-columns.php';
		include $boldgrid_dir . '/templates/slider-3bottom.php';
		include $boldgrid_dir . '/templates/slider-4bottom.php';
		include $boldgrid_dir . '/templates/slider.php';
	}
}
