<?php

/**
 * Boldgrid Source Code
 *
 * @package Boldgrid_Gallery
 * @copyright BoldGrid.com
 * @version $Id$
 * @author BoldGrid.com <wpb@boldgrid.com>
 *
 */

if ( ! defined( 'WPINC' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit();
}

/**
 * Boldgrid_Gallery class
 */
class Boldgrid_Gallery {
	/**
	 * Class property to hold the Boldgrid_Gallery_Config class object
	 *
	 * @var object
	 */
	private $boldgrid_gallery_config;

	/**
	 * Display Name for slider auto start fade
	 *
	 * @var string
	 */
	private $slider_auto_start = 'sliderfadeauto';

	/**
	 * Get $this->boldgrid_gallery_config
	 *
	 * @return Boldgrid_Gallery_Config
	 */
	public function get_boldgrid_gallery_config() {
		return $this->boldgrid_gallery_config;
	}

	/**
	 * Set $this->boldgrid_gallery_config
	 *
	 * @param
	 *        	object Boldgrid_Gallery_Config
	 *
	 * @return bool
	 */
	private function set_boldgrid_gallery_config( $config ) {
		$this->boldgrid_gallery_config = $config;

		return true;
	}

	/**
	 * Get $this->slider_auto_start
	 *
	 * @return string $this->slider_auto_start
	 */
	private function get_slider_auto_start() {
		return $this->slider_auto_start;
	}

	/**
	 * Set $this->plugin path
	 *
	 * @param string $slider_auto_start
	 *
	 * @return bool
	 */
	private function set_slider_auto_start( $slider_auto_start ) {
		$this->slider_auto_start = $slider_auto_start;

		return true;
	}

	/**
	 * Constructor.
	 */
	public function __construct() {
		// Load and instantiate Boldgrid_Gallery_Config.
		require_once BOLDGRID_GALLERY_PATH . '/boldgrid/includes/class-boldgrid-gallery-config.php';

		$boldgrid_gallery_config = new Boldgrid_Gallery_Config();

		$this->set_boldgrid_gallery_config( $boldgrid_gallery_config );

		$this->prepare_plugin_update();
	}

	/**
	 * Initialization Process of the BoldGrid Gallery functions
	 */
	public function init() {
		$this->load_includes();

		// Add styles to editor
		if ( is_admin() ) {

			$boldgrid_editor = new Boldgrid_Gallery_Editor();
			$boldgrid_editor->init();

			// Add filter to display options
			add_filter( 'boldgrid_gallery_display_types',
				array (
					$this,
					'filter_display_options'
				) );
		} else {
			add_filter( 'boldgrid_gallery_slider_types',
				array (
					$this,
					'filter_slider_options'
				) );
			add_action( 'wp_enqueue_scripts',
				array (
					$this,
					'register_frontend_scripts'
				) );
		}
	}

	/**
	 * Update the Slider options.
	 *
	 * @param array $slider_options
	 * @return array
	 */
	public function filter_slider_options( $slider_options ) {
		$slider_options[] = $this->get_slider_auto_start();
		return $slider_options;
	}

	/**
	 * Update the Display options.
	 *
	 * These are the gallery/slider types
	 *
	 * @param array $display_options
	 * @return array
	 */
	public function filter_display_options( $display_options ) {
		unset( $display_options['owlautowidth'] );
		unset( $display_options['owlcolumns'] );
		unset( $display_options['carousel'] );
		unset( $display_options['slider3bottomlinks'] );
		unset( $display_options['slider4bottomlinks'] );

		$display_options['sliderauto'] = __( 'Slider (Slide Auto Start)', 'wc_gallery' );
		$slider_auto_start = $this->get_slider_auto_start();
		$display_options[$slider_auto_start] = __( 'Slider (Fade Auto Start)', 'wc_gallery' );
		$display_options['coverflow'] = __( 'Coverflow', 'wc_gallery' );

		return $display_options;
	}

	/**
	 * Add the scripts and styles needed for the front end
	 */
	public function register_frontend_scripts() {
		$coverflow_path_ext = 'boldgrid/assets/js/coverflow';

		wp_register_script( 'boldgrid-gallery-interpolate',
			WC_GALLERY_PLUGIN_URL . $coverflow_path_ext . '/jquery.interpolate.js',
			array (
				'jquery'
			), BOLDGRID_GALLERY_VERSION, true );

		wp_register_script( 'boldgrid-gallery-touchswipe',
			WC_GALLERY_PLUGIN_URL . $coverflow_path_ext . '/jquery.touchSwipe.min.js',
			array (
				'jquery'
			), BOLDGRID_GALLERY_VERSION, true );

		wp_register_script( 'boldgrid-gallery-reflection',
			WC_GALLERY_PLUGIN_URL . $coverflow_path_ext . '/reflection.js',
			array (
				'jquery'
			), BOLDGRID_GALLERY_VERSION, true );

		// Add Coverflow scripts
		wp_register_script( 'boldgrid-gallery-coverflow',
			WC_GALLERY_PLUGIN_URL . $coverflow_path_ext . '/jquery.coverflow.js',
			array (
				'jquery',
				'jquery-ui-widget',
				'boldgrid-gallery-reflection',
				'boldgrid-gallery-touchswipe',
				'boldgrid-gallery-interpolate'
			), BOLDGRID_GALLERY_VERSION, true );
	}

	/**
	 * Load the files needed by BoldGrid Gallery
	 */
	public function load_includes() {
		require_once BOLDGRID_GALLERY_PATH . '/boldgrid/includes/class-boldgrid-gallery-editor.php';
	}

	/**
	 * Prepare for the update class.
	 *
	 * @since 1.3.1
	 */
	public function prepare_plugin_update() {
		$is_cron = ( defined( 'DOING_CRON' ) && DOING_CRON );
		$is_wpcli = ( defined( 'WP_CLI' ) && WP_CLI );

		if ( $is_cron || $is_wpcli || is_admin() ) {
			require_once BOLDGRID_GALLERY_PATH . '/boldgrid/includes/class-boldgrid-gallery-update.php';

			$plugin_update = new Boldgrid_Gallery_Update( $this->boldgrid_gallery_config->get_configs() );

			add_action( 'init', array (
				$plugin_update,
				'add_hooks'
			) );
		}
	}
}
