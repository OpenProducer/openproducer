<?php
/**
 * BoldGrid Library Form Add New
 *
 * @package Boldgrid\Library
 * @subpackage Form
 *
 * @version 1.0.2
 * @author BoldGrid <wpb@boldgrid.com>
 */

namespace Boldgrid\Library\Form\Wpforms;

use Boldgrid\Library\Library\Filter;

/**
 * BoldGrid Library Form Add New Class.
 *
 * This class is responsible for modifying the Plugins > Add New >
 * BoldGrid tab in the WordPress admin dashboard.
 *
 * @since 1.0.2
 */
class Tracking {

	protected
		$id = '1581233',
		$url = 'https://wpforms.com/lite-upgrade';

	/**
	 * Initiallize class and set class properties.
	 *
	 * @param array $configs array of library configuration options.
	 */
	public function __construct() {
		Filter::add( $this );
	}

	/**
	 * Add shareasale ID for wpforms links.
	 *
	 * @since 1.0.2
	 *
	 * @hook: wpforms_shareasale_id
	 *
	 * @return $id The plugins to save update information for.
	 */
	public function shareasale( $id ) {

		// If this WordPress installation already has an WPForms Shareasale ID specified, use that.
		if ( ! empty( $id ) && $id !== $this->id ) {
			update_option( 'wpforms_shareasale_id', $this->id );
		}

		return $id;
	}

	/**
	 * Modify the Library's URLs for premium plugin data.
	 *
	 * @since 1.0.2
	 *
	 * @hook: Boldgrid\Library\Plugin\Installer\premiumUrl
	 *
	 * @param string  $url     The URL to modify.
	 * @param string  $plugin  The plugin's slug to modify.
	 *
	 * @return object $plugins The plugins to save update information for.
	 */
	public function wpformsUrl( $url, $plugin ) {
		if ( $plugin === 'wpforms-lite' ) {

			// Set redirect URL.
			$url = $this->url;

			if ( ! get_option( 'wpforms_shareasale_redirect', false ) ) {
				update_option( 'wpforms_shareasale_redirect', $url );
			}

			$id = defined( 'WPFORMS_SHAREASALE_ID' ) ? WPFORMS_SHAREASALE_ID : get_option( 'wpforms_shareasale_id', '' ) || $this->id;

			// Gets the premium URL.
			if ( $id ) {
				$url = esc_url( sprintf(
					'http://www.shareasale.com/r.cfm?B=837827&U=%1$s&M=64312&urllink=%2$s',
					$id,
					$url
				) );
			}
		}

		return $url;
	}
}
