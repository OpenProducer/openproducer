<?php
/**
 * BoldGrid Library Connect Page Class.
 *
 * @package Boldgrid\Library
 * @subpackage \Library\Library\Page
 *
 * @version 2.4.0
 * @author BoldGrid <support@boldgrid.com>
 */

namespace Boldgrid\Library\Library\Page;

use Boldgrid\Library\Library\Filter;
use Boldgrid\Library\Library\Configs;

/**
 * BoldGrid Library Connect Page Class.
 *
 * Create the BoldGrid Connect Page.
 *
 * @since 2.4.0.
 */
class Connect {

	/**
	 * Add all filters for this class.
	 *
	 * @since 2.4.0
	 */
	public function __construct() {
		Filter::add( $this );
	}

	/**
	 * Run any needed methods on the Connect Page load.
	 *
	 * @since 2.4.0
	 *
	 * @hook current_screen
	 */
	public function onLoad( $screen ) {
		if ( $this->isConnectScreen( $screen ) ) {
			self::setupNotice();
		}
	}

	/**
	 * If we are performing an ajax call, setup the notice.
	 *
	 * Standard ajax handler is bound when the notice is created. Notice is not
	 * created during ajax call.
	 *
	 * @since 2.4.0
	 *
	 * @hook admin_init
	 */
	public function ajax() {
		if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
			if ( ! empty( $_POST['action'] ) && 'addKey' === sanitize_text_field( $_POST['action'] ) ) {
				self::setupNotice();
			}
		}
	}

	/**
	 * Setup the connect key notice.
	 *
	 * @since 2.4.0
	 */
	public static function setupNotice() {
		Configs::get( 'start' )->setupKeyConnections();
		Configs::get( 'start' )->getKey()->setNotice( true );
	}

	/**
	 * Check if the current screen is the connect screen.
	 *
	 * @since 2.4.0
	 *
	 * @param  object  $screen Current Screen.
	 * @return boolean         Is the screen the connect screen?
	 */
	public function isConnectScreen( $screen ) {
		$base = ! empty( $screen->base ) ? $screen->base : null;
		return 'settings_page_boldgrid-connect' === $base;
	}

	/**
	 * Fiter the show prompt display to true.
	 *
	 * @since 2.4.0
	 *
	 * @hook Boldgrid\Library\Library\Notice\KeyPrompt_display
	 */
	public function showPrompt( $shouldDisplay ) {
		return $this->isConnectScreen( get_current_screen() ) ? true : $shouldDisplay;
	}

	/**
	 * Enqueue scripts needed for this page.
	 *
	 * @since 2.4.0
	 *
	 * @hook admin_enqueue_scripts
	 */
	public function addScripts() {
		if ( $this->isConnectScreen( get_current_screen() ) ) {
			// Enqueue bglib-connect js.
			$handle = 'bglib-connect';

			wp_register_script(
				$handle,
				Configs::get( 'libraryUrl' ) . 'src/assets/js/connect.js' ,
				array( 'jquery' ),
				date( 'Ymd' ),
				false
			);

			$translation = array(
				'settingsSaved' => __( 'Settings saved.', 'boldgrid-library' ),
				'unknownError'  => __( 'Unknown error.', 'boldgrid-library' ),
				'ajaxError'     => __( 'Could not reach the AJAX URL address. HTTP error: ', 'boldgrid-library' ),
			);

			wp_localize_script( $handle, 'BoldGridLibraryConnect', $translation );

			wp_enqueue_script( $handle );

			// Enqueue jquery-toggles js.
			wp_enqueue_script(
				'jquery-toggles',
				Configs::get( 'libraryUrl' ) . 'build/toggles.min.js',
				array( 'jquery' ),
				date( 'Ymd' ),
				true
			);

			// Enqueue jquery-toggles css.
			wp_enqueue_style( 'jquery-toggles-full',
				Configs::get( 'libraryUrl' ) . 'build/toggles-full.css', array(), date( 'Ymd' ) );

			/**
			 * Add additional scripts to Connect page.
			 *
			 * @since 2.4.0
			 */
			do_action( 'Boldgrid\Library\Library\Page\Connect\addScripts' );
		}
	}

	/**
	 * Create the BoldGird Connect Page.
	 *
	 * @since 2.4.0
	 *
	 * @hook admin_menu
	 */
	public function addPage() {
		add_submenu_page(
			'options-general.php',
			'BoldGrid Connect',
			'BoldGrid Connect',
			'manage_options',
			'boldgrid-connect.php',
			function () {
				include __DIR__ . '/../Views/Connect.php';
			}
		);
	}

	/**
	 * AJAX callback for the Connect Settings page.
	 *
	 * @since 2.7.0
	 *
	 * @uses $_POST['autoupdate']             Optional auto-update settings for plugins and themes.
	 * @uses $_POST['plugin_release_channel'] Plugin release channel.
	 * @uses $_POST['theme_release_channel']  Theme release channel.
	 *
	 * @see self::sanitizeSettings()
	 *
	 * @hook wp_ajax_boldgrid_library_connect_settings_save
	 */
	public function saveSettings() {
		// Check user permissions.
		if ( ! current_user_can( 'update_plugins' ) ) {
			wp_send_json_error( array(
				'error' => __( 'User access violation!', 'boldgrid-library' ),
			) );
		}

		// Check security nonce and referer.
		if ( ! check_admin_referer( 'boldgrid_library_connect_settings_save' ) ) {
			wp_send_json_error( array(
				'error' => __( 'Security violation! Please try again.', 'boldgrid-library' ),
			) );
		}

		// Read settings form POST request, sanitize, and merge settings with saved.
		$boldgridSettings = array_merge(
			get_option( 'boldgrid_settings' ),
			self::sanitizeSettings(
				array(
					'autoupdate'            => ! empty( $_POST['autoupdate'] ) ?
						(array) $_POST['autoupdate'] : array(),
					'release_channel'       => ! empty( $_POST['plugin_release_channel'] ) ?
						sanitize_key( $_POST['plugin_release_channel'] ) : 'stable',
					'theme_release_channel' => ! empty( $_POST['theme_release_channel'] ) ?
						sanitize_key( $_POST['theme_release_channel'] ) : 'stable',
				)
			)
		);

		// If new auto-update settings were passed, then remove deprecated settings.
		if ( ! empty( $_POST['autoupdate'] ) ) {
			unset( $boldgridSettings['plugin_autoupdate'], $boldgridSettings['theme_autoupdate'] );
		}

		update_option( 'boldgrid_settings', $boldgridSettings );

		wp_send_json_success();
	}

	/**
	 * Sanitize settings.
	 *
	 * @since 2.7.0
	 *
	 * @static
	 *
	 * @param  array $settings {
	 *     Settings.
	 *
	 *     @type array  $autoupdate {
	 *         Optional auto-update settings.  This array does not always get included.
	 *
	 *         @type array $plugins {
	 *             Plugin auto-update settings.
	 *
	 *             @type string $slug Plugin auto-update setting (1=Enabled, 0=Disabled).
	 *         }
	 *         @type array $themes {
	 *             Theme auto-update settings.
	 *
	 *             @type string $stylesheet Theme auto-update setting (1=Enabled, 0=Disabled).
	 *         }
	 *     }
	 *     @type string $release_channel        Plugin release channel.
	 *     @type string $theme_release_channel  Theme release channel.
	 * }
	 * @return array
	 */
	public static function sanitizeSettings( array $settings ) {
		$result = array();

		if ( ! empty( $settings['autoupdate'] ) && is_array( $settings['autoupdate'] ) ) {
			foreach ( $settings['autoupdate'] as $category => $itemSetting ) {
				$category = sanitize_key( $category );

				foreach ( $itemSetting as $id => $val ) {
					$id = sanitize_text_field( $id );

					$result['autoupdate'][ $category ][ $id ] = (bool) $val;
				}
			}
		}

		// Validate release channel settings.
		$channels = array(
			'stable',
			'edge',
			'candidate',
		);

		if ( empty( $settings['release_channel'] ) ||
			! in_array( $settings['release_channel'], $channels, true ) ) {
				$result['release_channel'] = 'stable';
		} else {
			$result['release_channel'] = $settings['release_channel'];
		}

		if ( empty( $settings['theme_release_channel'] ) ||
			! in_array( $settings['theme_release_channel'], $channels, true ) ) {
				$result['theme_release_channel'] = 'stable';
		} else {
			$result['theme_release_channel'] = $settings['theme_release_channel'];
		}

		return $result;
	}
}
