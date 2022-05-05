<?php
/**
 * BoldGrid Library Notifications Dashboard Widget Class
 *
 * @package Boldgrid\Library
 * @subpackage \Library\License
 *
 * @version 2.9.0
 * @author BoldGrid <wpb@boldgrid.com>
 */

namespace Boldgrid\Library\Library\Notifications;

use Boldgrid\Library\Library;

/**
 * BoldGrid Library Notifications Dashboard Widget Class.
 *
 * This class is responsible for rendering the "BoldGrid Notifications" widget on the WordPress
 * dashboard.
 *
 * @since 2.10.0
 */
class DashboardWidget {
	/**
	 * Initialize class and set class properties.
	 *
	 * @since 2.10.0
	 */
	public function __construct() {
		Library\Filter::add( $this );
	}

	/**
	 * Load scripts on the admin_enqueue_scripts hook.
	 *
	 * @since 2.10.0
	 */
	public function admin_enqueue_scripts() {
		Library\Ui\Dashboard::enqueueScripts();
	}

	/**
	 * Print the "BoldGrid Notifications" WordPress Dashboard widget.
	 *
	 * @since 2.10.0
	 */
	public function printWidget() {
		// Failsafe. Abort if needed.
		if ( ! class_exists( 'Boldgrid\Library\Library\Ui\Card' ) ) {
			echo '<div class="notice notice-warning inline"><p>' .
				esc_html__( 'Unable to load BoldGrid Notifications.', 'boldgrid-library' ) .
				'</p></div>';

			return false;
		}

		$card = new \Boldgrid\Library\Library\Ui\Card();

		// Add all of our active plugins.
		$activePlugins = Library\Plugin\Plugins::getActive();
		foreach( $activePlugins as $plugin ) {
			$card->features[] = $this->getFeaturePlugin( $plugin );
		}

		// Add a "feature" for free / premium.
		$card->features[] = $this->getFeatureKey();

		$card->printCard();
	}

	/**
	 * Get the "feature" for our BoldGrid Connect Key.
	 *
	 * @since 2.10.0
	 *
	 * @return \Boldgrid\Library\Library\Ui\Feature
	 */
	public function getFeatureKey() {
		$feature = new Library\Ui\Feature();

		/*
		 * Get our license string: "None", "Free", "Premium".
		 *
		 * Be default, the license string is based on a single product's license. Instead of using
		 * this logic, below we are trying to figure out a key's license (and not a product's license).
		 *
		 * To get the license, we need to check if a particular product is premium. To do that, we
		 * will set our product to be the plugin that is loading the library.
		 */
		$license = new Library\License();
		$key     = $license->getApiKey();
		if ( empty( $key ) ) {
			$licenseString = 'None';
		} else {
			$product = Library\Configs::getFileSlug();
			// Call isPermium(), which sets the license string.
			$license->isPremium( $product );
			$licenseString = $license->getLicenseString();
		}

		$feature->title = esc_html__( 'BoldGrid Connect Key', 'boldgrid-backup' );

		switch( $licenseString ) {
			case 'None':
				$feature->icon    = '<span class="dashicons dashicons-admin-network"></span>';
				$feature->content = '<div class="notice notice-warning inline">
					<p>
						<a href="' . admin_url( 'options-general.php?page=boldgrid-connect.php' ) . '">' .
							esc_html__( 'Please install your Connect Key', 'boldgrid-library' ) .
						'</a>
					</p>
				</div>';
				break;
			case 'Free':
				$feature->icon    = '<span class="dashicons dashicons-admin-network boldgrid-orange"></span>';
				$feature->content = '<div class="notice notice-warning inline">
					<p>
						<a href="' . esc_url( Library\Configs::get( 'learnMore' ) ) . '">' .
							esc_html__( 'Learn about the advanced features of a Premium Key.', 'boldgrid-library' ) .
						'</a>
					</p>
				</div>';
				break;
			case 'Premium':
				$feature->icon    = '<span class="dashicons dashicons-admin-network boldgrid-orange"></span>';
				$feature->content = esc_html__( 'Premium Connect Key Installed!', 'boldgrid-library' );
				break;
		}

		return $feature;
	}

	/**
	 * Get the "feature" for a plugin.
	 *
	 * @since 2.10.0
	 *
	 * @param  \Boldgrid\Library\Library\Plugin\Plugin A plugin object.
	 * @param  \Boldgrid\Library\Library\Plugin\Plugin The plugin's parent plugin (optional).
	 * @return \Boldgrid\Library\Library\Ui\Feature    A feature object.
	 */
	public function getFeaturePlugin( Library\Plugin\Plugin $plugin, $parentPlugin = null ) {
		$isParentPlugin = is_null( $parentPlugin );

		// Get the markup for the plugin's icon.
		$icons = $plugin->getIcons();
		if ( empty( $icons ) ) {
			$icon = '<span class="dashicons dashicons-admin-plugins"></span>';
		} else {
			$iconUrl = ! empty( $icons['1x'] ) ? $icons['1x'] : reset( $icons );
			$icon = '<img src="' . esc_url( $iconUrl ) . '" />';
		}

		$feature = new Library\Ui\Feature();

		$feature->title = $plugin->getData( 'Name' );

		$feature->icon = $icon;

		if ( $plugin->hasUpdate() ) {
			$feature->content = '<div class="notice notice-warning inline"><p>' . wp_kses(
				sprintf(
					__( '%1$s is out of date. %2$sFix this%3$s.', 'boldgrid-backup' ),
					$plugin->getData( 'Name' ),
					'<a href="' . esc_url( admin_url( 'update-core.php' ) ) . '">',
					'</a>'
				),
				[ 'a' => [ 'href' => [] ] ]
			) . '</p></div>';
		}

		/**
		 * Allow items to be filtered.
		 *
		 * @since 2.10.0
		 *
		 * @param \Boldgrid\Library\Library\Ui\Feature    The feature object.
		 * @param \Boldgrid\Library\Library\Plugin\Plugin The plugin object.
		 */
		$feature = apply_filters( 'Boldgrid\Library\Notifications\DashboardWidget\getFeaturePlugin\\' . $plugin->getSlug(), $feature, $plugin );

		/*
		 * If this plugin has child plugins (IE a premium plugin), then allow those children to add
		 * notices to the parent.
		 */
		$childPlugins = $plugin->getChildPlugins();
		foreach ( $childPlugins as $childPlugin ) {
			if ( ! $childPlugin->getIsInstalled() ) {
				continue;
			}

			$childFeature      = $this->getFeaturePlugin( $childPlugin, $plugin );
			$feature->content .= $childFeature->content;
		}

		// The parent plugin makes the final call, as in whether or not there are issues to report.
		if ( $isParentPlugin && empty( $feature->content ) ) {
			$feature->content = '<p>' . __( 'No issues to report!', 'boldgrid-library' ) . '</p>';
		}

		return $feature;
	}


	/**
	 * Setup our dashboard widget.
	 *
	 * @since 2.10.0
	 *
	 * @link https://codex.wordpress.org/Dashboard_Widgets_API
	 */
	public function wp_dashboard_setup() {
		wp_add_dashboard_widget(
			'boldgrid-notifications',
			__( 'BoldGrid Notifications', 'boldgrid-library' ),
			array( $this, 'printWidget' )
		);
	}
}
