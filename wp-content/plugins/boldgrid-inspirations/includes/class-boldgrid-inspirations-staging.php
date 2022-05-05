<?php
/**
 * BoldGrid Source Code
 *
 * @package Boldgrid_Inspirations_Staging
 * @copyright BoldGrid.com
 * @version $Id$
 * @author BoldGrid.com <wpb@boldgrid.com>
 *
 * @since 1.5.5
 */

/**
 * BoldGrid Inspiration Staging class.
 *
 * @since 1.5.5
 */
class Boldgrid_Inspirations_Staging {
	/**
	 * The staging plugin file/slug.
	 *
	 * @since 1.5.5
	 *
	 * @var string
	 */
	private $plugin_file = 'boldgrid-staging/boldgrid-staging.php';

	/**
	 * Add hooks.
	 *
	 * @since 1.5.5
	 */
	public function add_hooks() {
		add_action( 'wp_ajax_install_staging', array( $this, 'install' ) );
		add_action( 'wp_ajax_activate_staging', array( $this, 'activate' ) );
	}

	/**
	 * Install the staging plugin via an ajax request.
	 *
	 * This is done during the final stage of Inspirations, when the user decided to download
	 * staging and install the site as a staged site.
	 *
	 * @since 1.5.5
	 *
	 * @see self::install_plugin()
	 */
	public function install() {
		if ( false === check_ajax_referer( 'nonce-install-staging', 'nonce-install-staging', false ) ) {
			wp_die( '0' );
		}

		$result = $this->install_plugin();

		if ( $result && is_plugin_active( $this->plugin_file ) ) {
			wp_die( '1' );
		} else {
			wp_die( '0' );
		}
	}

	/**
	 * Activate the staging plugin.
	 *
	 * @since 1.5.5
	 */
	public function activate() {
		if ( null === activate_plugin( $this->plugin_file ) ) {
			wp_die( '1' );
		} else {
			wp_die( '0' );
		}
	}

	/**
	 * Return the plugin URL address.
	 *
	 * @since 1.5.5
	 *
	 * @see \Boldgrid\Library\Library\ReleaseChannel::getPluginChannel()
	 * @see Boldgrid_Inspirations_Update::get_configs()
	 *
	 * @return string
	 */
	public function get_url() {
		$boldgrid_api_data = get_site_transient( 'boldgrid_api_data' );

		$release_class = new \Boldgrid\Library\Library\ReleaseChannel();

		$release_channel = $release_class->getPluginChannel();

		if ( ! empty( $boldgrid_api_data ) ) {
			$boldgrid_configs = Boldgrid_Inspirations_Update::get_configs();

			// Create the URL address for asset downloads.
			if ( ! empty( $boldgrid_configs['api_key'] ) ) {
				$get_asset_url = $boldgrid_configs['asset_server'] .
					 $boldgrid_configs['ajax_calls']['get_asset'] . '?key=' .
					 $boldgrid_configs['api_key'] . '&id=';
			}

			// Set the BoldGrid Staging download URL address.
			if ( isset( $boldgrid_api_data->result->data->staging->asset_id ) &&
				isset( $get_asset_url ) ) {
					$url = $get_asset_url .
						$boldgrid_api_data->result->data->staging->asset_id;
			}
		}

		// If asset links are not available, then use open access links.
		if ( empty( $url ) ) {
			if ( 'stable' !== $release_channel ) {
				// Other channels.
				$url = 'https://repo.boldgrid.com/boldgrid-staging-' .
					$release_channel . '.zip';
			} else {
				// Stable channel.
				$url = 'https://repo.boldgrid.com/boldgrid-staging.zip';
			}
		}

		return $url;
	}

	/**
	 * Add plugin to active plugins list in wp_options.
	 *
	 * @since 1.5.5
	 *
	 * @param string $plugin_file Plugin file/slug.
	 */
	public function add_active_plugin( $plugin_file ) {
		$active_plugins = get_option( 'active_plugins' );

		if ( ! in_array( $plugin_file, $active_plugins, true ) ) {
			$active_plugins[] = $plugin_file;

			update_option( 'active_plugins', $active_plugins );
		}
	}

	/**
	 * Install and activate the staging plugin.
	 *
	 * @since 1.5.5
	 *
	 * @return bool
	 */
	public function install_plugin() {
		$result = false;

		if ( ! current_user_can( 'install_plugins' ) ) {
			return false;
		}

		$plugin_exists = array_key_exists( $this->plugin_file , get_plugins() );

		if ( isset( $_POST['boldgrid-plugin-install'] ) || ! $plugin_exists ) {
			require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';

			//$this->add_active_plugin( $plugin_file );

			$upgrader = new Plugin_Upgrader( new Plugin_Installer_Skin() );
			$result   = $upgrader->install( $this->get_url() );
		}

		activate_plugin( $this->plugin_file );

		return $result;
	}
}
