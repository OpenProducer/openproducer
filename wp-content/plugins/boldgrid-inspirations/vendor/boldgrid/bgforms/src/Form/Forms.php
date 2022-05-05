<?php
/**
 * BoldGrid Source Code
 *
 * @package Boldgrid\Library\Form
 * @copyright BoldGrid.com
 * @author BoldGrid.com <wpb@boldgrid.com>
 */

namespace Boldgrid\Library\Form;

/**
 * Class: Forms
 *
 * @since 1.0.0
 */
class Forms {
	/**
	 * Forced plugin slug.
	 *
	 * @since 1.0.1
	 *
	 * @access private
	 *
	 * @var string
	 */
	private $preferred_slug = '';

	/**
	 * Tracking for WpForms share-a-sale.
	 *
	 * @var \Boldgrid\Library\Form\Wpforms\Tracking
	 */
	private $tracking;

	/**
	 * Constructor.
	 *
	 * @since 1.0.0
	 *
	 * @see \Boldgrid\Library\Form\Forms::get_preferred_slug()
	 */
	public function __construct() {
		$this->preferred_slug = $this->get_preferred_slug();
		$this->tracking       = new Wpforms\Tracking();

		switch ( true ) {
			case 'wpforms-lite/wpforms.php' === $this->preferred_slug:
				// If WPForms is preferred, then convert the shortcodes.
				$wpforms = new Wpforms();

				// Add a filter for converting shortcodes for use with WPForms.
				add_filter( 'boldgrid_deployment_pre_insert_post', [
					$wpforms,
					'convert_nf_shortcodes',
				] );
				break;
			case 'weforms/weforms.php' === $this->preferred_slug:
			default:
				// If weForms is preferred, then convert the shortcodes.
				$weforms = new WeForms();

				// Add a filter for converting shortcodes for use with weForms.
				add_filter( 'boldgrid_deployment_pre_insert_post', [
					$weforms,
					'convert_nf_shortcodes',
				] );
				break;
		}
	}

	/**
	 * Get a plugin slug (folder/file).
	 *
	 * If the plugin is not found, then an empty string is returned.
	 *
	 * @since 1.0.0
	 *
	 * @param  array $match_names An array of plugin names to match.
	 * @return string
	 */
	public function get_plugin_slug( array $match_names ) {
		$plugins = get_plugins();

		foreach ( $plugins as $plugin_slug => $plugin_data ) {
			if ( in_array( $plugin_data['Name'], $match_names, true ) ) {
				return $plugin_slug;
			}
		}

		return '';
	}

	/**
	 * Get the WPForms slug (folder/file).
	 *
	 * @since 1.0.0
	 *
	 * @see self::get_plugin_slug()
	 * @uses Wpforms::$match_names
	 *
	 * @return string
	 */
	public function get_wpforms_slug() {
		return $this->get_plugin_slug( Wpforms::$match_names );
	}

	/**
	 * Get the weForms slug (folder/file).
	 *
	 * @since 1.2.0
	 *
	 * @see self::get_plugin_slug()
	 * @uses WeForms::$match_names
	 *
	 * @return string
	 */
	public function get_weforms_slug() {
		return $this->get_plugin_slug( WeForms::$match_names );
	}

	/**
	 * Get the preferred form plugin slug (folder/file).
	 *
	 * @since 1.0.0
	 *
	 * @see self::get_wpforms_slug()
	 * @see self::get_weforms_slug()
	 *
	 * @return string
	 */
	public function get_preferred_slug() {
		$slug = $this->get_wpforms_slug() ?: $this->get_weforms_slug();

		return apply_filters( 'Boldgrid\Library\Form\Forms\get_preferred_slug', $slug ); // phpcs:ignore WordPress.NamingConventions.ValidHookName
	}

	/**
	 * Activate the preferred forms plugin.
	 *
	 * @since 1.0.0
	 *
	 * @see is_plugin_active()
	 * @see activate_plugin()
	 *
	 * @return bool
	 */
	public function activate_preferred_plugin() {
		if ( ! $this->preferred_slug ) {
			return false;
		}

		return ! is_wp_error( activate_plugin( $this->preferred_slug ) );
	}

	/**
	 * Install and activate the weForms plugin and import forms.
	 *
	 * @since 1.0.0
	 *
	 * @see is_plugin_active()
	 * @see \Boldgrid\Library\Form\WeForms::install_plugin()
	 * @see self::activate_preferred_plugin()
	 * @see \Boldgrid\Library\Form\WeForms::import_forms()
	 *
	 * @return bool
	 */
	public function install() {
		if ( $this->preferred_slug && array_key_exists( $this->preferred_slug, get_plugins() ) ) {
			$this->activate_preferred_plugin();

			return false;
		}

		$weforms = new WeForms();

		$result = $weforms->install_plugin();

		if ( $result ) {
			$this->preferred_slug = $this->get_weforms_slug();
			$result               = $this->activate_preferred_plugin();
			$weforms->import_forms();
		}

		return $result;
	}

	/**
	 * Check to ensure that all forms are imported.
	 *
	 * @since 1.0.0
	 *
	 * @see self::get_weforms_slug()
	 * @see \Boldgrid\Library\Form\WeForms::import_forms()
	 */
	public function check_forms() {
		if ( $this->get_weforms_slug() ) {
			$weforms = new WeForms();
			$weforms->import_forms();
		}
	}

	/**
	 * Hide form notices.
	 *
	 * @since 1.2.0
	 */
	public function hide_notices() {
		if ( $this->get_weforms_slug() ) {
			$weforms = new WeForms();
			$weforms->hide_notices();
		}
	}
}
