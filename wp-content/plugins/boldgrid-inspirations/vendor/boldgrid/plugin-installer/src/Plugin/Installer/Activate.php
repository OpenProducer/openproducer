<?php
/**
 * BoldGrid Library Plugin Installer Activate
 *
 * @package Boldgrid\Plugin
 *
 * @version 1.0.0
 * @author BoldGrid <wpb@boldgrid.com>
 */

namespace Boldgrid\Library\Plugin\Installer;

use Boldgrid\Library\Library;
use Boldgrid\Library\Util;

/**
 * BoldGrid Library Plugin Installer Class.
 *
 * This class is responsible for installing BoldGrid plugins in the WordPress admin's
 * "Plugins > Add New" section.
 *
 * @since 1.0.0
 */
class Activate {
	/**
	 * Library configuration options.
	 *
	 * @since 1.0.0
	 *
	 * @var array
	 */
	protected $configs;

	/**
	 * Our text domain.
	 *
	 * @since 1.1.0
	 * @access private
	 * @var string
	 */
	private $textdomain = 'boldgrid-plugin-installer';

	/**
	 * Initialize class and set class properties.
	 *
	 * @since 1.0.0
	 *
	 * @param array $configs array of library configuration options.
	 */
	public function __construct( array $configs ) {
		$this->configs = $configs;
	}

	/**
	 * Adds filters.
	 *
	 * @since 1.0.0
	 */
	public function init() {
		load_textdomain( $this->textdomain, dirname( dirname( dirname( __FILE__ ) ) ) . '/languages/' . $this->textdomain . '-' . get_locale() . '.mo' );

		Library\Filter::add( $this );
	}

	/**
	 * Called via Ajax for activating a WordPress plugin.
	 *
	 * This is used for allowing the installed plugins listed in our tab to be
	 * activated.
	 *
	 * @since 1.0.0
	 *
	 * @hook: wp_ajax_activation
	 *
	 * @return $json
	 */
	public function activation() {
		if ( ! current_user_can( 'install_plugins' ) ) {
			wp_die( __( 'Sorry, you are not allowed to activate plugins on this site.', 'boldgrid-plugin-installer' ) );
		}

		$nonce = $_POST['nonce'];
		$plugin = $_POST['plugin'];

		// Check our nonce, if they don't match then bounce!
		if ( ! wp_verify_nonce( $nonce, 'bglibPluginInstallerNonce' ) ) {
			die( __( 'Error - unable to verify nonce, please try again.', 'boldgrid-plugin-installer' ) );
		}

		// Include the required libs for activation.
		if ( ! function_exists( 'install_plugin_information' ) ) {
			require_once ABSPATH . 'wp-admin/includes/plugin-install.php';
		}

		if ( ! class_exists( 'WP_Upgrader' ) ) {
			require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
		}

		if ( ! class_exists( 'Plugin_Upgrader' ) ) {
			require_once ABSPATH . 'wp-admin/includes/class-plugin-upgrader.php';
		}

		// Get Plugin Info.
		$api = plugins_api(
			'plugin_information',
			array(
				'slug' => $plugin,
				'fields' => array(
					'short_description' => false,
					'sections' => false,
					'requires' => false,
					'rating' => false,
					'ratings' => false,
					'downloaded' => false,
					'last_updated' => false,
					'added' => false,
					'tags' => false,
					'compatibility' => false,
					'homepage' => false,
					'donate_link' => false,
				),
			)
		);

		if ( $api->name ) {
			$file = ! empty( $this->configs['plugins'][ $plugin ]['file'] ) ?
				$this->configs['plugins'][ $plugin ]['file'] :
				Util\Plugin::getPluginFile( $api->slug );

			$status = 'success';

			if ( $file ) {
				$activate = activate_plugin( $file );

				if ( is_wp_error( $activate ) ) {
					// Process error.
					$msg = sprintf(
						// translators: 1 the name of the plugin the user was trying to activate.
						__( 'There was an error activating %1$s.', 'boldgrid-plugin-installer' ),
						$api->name
					);

					wp_send_json_error( array( 'message' => $msg ) );
				}

				$msg = sprintf(
					// translators: 1 the name of the plugin the user was trying to activate.
					__( '%1$s successfully activated.', 'boldgrid-plugin-installer' ),
					$api->name
				);

				wp_send_json_success( array( 'message' => $msg ) );
			}
		} else {
			$status = 'failed';

			$msg = sprintf(
				// translators: 1 the name of the plugin the user was trying to activate.
				__( 'There was an error activating %s.', 'boldgrid-plugin-installer' ),
				$api->name
			);

			wp_send_json_error( array( 'message' => $msg ) );
		}
	}
}
