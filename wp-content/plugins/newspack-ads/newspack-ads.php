<?php
/**
 * Plugin Name:     Newspack Ads (final version, please migrate)
 * Plugin URI:      https://newspack.com
 * Description:     Final version released from the legacy plugin repository. This copy will not receive further updates. Download the current version at https://newspack.com/download-center
 * Author:          Automattic
 * License:         GPL2
 * Version:         3.11.4
 *
 * @package         Newspack
 */

namespace Newspack_Ads;

defined( 'ABSPATH' ) || exit;

define( 'NEWSPACK_ADS_VERSION', '3.11.4' );

// Define NEWSPACK_ADS_PLUGIN_FILE.
if ( ! defined( 'NEWSPACK_ADS_PLUGIN_FILE' ) ) {
	define( 'NEWSPACK_ADS_PLUGIN_FILE', __FILE__ );
}

define( 'NEWSPACK_ADS_ABSPATH', dirname( NEWSPACK_ADS_PLUGIN_FILE ) . '/' );

define( 'NEWSPACK_ADS_BLOCKS_PATH', NEWSPACK_ADS_ABSPATH . 'src/blocks/' );

/**
 * Path to the Composer vendor directory for Newspack Ads.
 * Useful when running with a custom autoloader setup.
 *
 * @constant NEWSPACK_ADS_COMPOSER_ABSPATH
 * @type     string
 * @default  Plugin vendor directory
 * @status   draft
 *
 * @example define( 'NEWSPACK_ADS_COMPOSER_ABSPATH', '/path/to/vendor/' );
 */
if ( ! defined( 'NEWSPACK_ADS_COMPOSER_ABSPATH' ) ) {
	define( 'NEWSPACK_ADS_COMPOSER_ABSPATH', dirname( NEWSPACK_ADS_PLUGIN_FILE ) . '/vendor/' );
}

// Include the main Newspack Ads class.
if ( ! class_exists( 'Newspack_Ads\Core' ) ) {
	include_once __DIR__ . '/includes/class-core.php';
}

/**
 * Warn administrators that this build came from the legacy plugin repository.
 */
function newspack_ads_legacy_repo_notice() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	?>
	<div class="notice notice-error">
		<p><strong><?php esc_html_e( 'You are running an outdated version of the Newspack Ads plugin.', 'newspack-ads' ); ?></strong></p>
		<p>
			<?php
			printf(
				wp_kses(
					/* translators: 1: URL of the announcement post. 2: URL of the download center. */
					__( 'This is the final version released from the legacy plugin repository, and it will not receive further updates. <a href="%1$s">Read the announcement</a>, then download the current version from the <a href="%2$s">Newspack download center</a>.', 'newspack-ads' ),
					[
						'a' => [
							'href' => [],
						],
					]
				),
				esc_url( 'https://newspack.com/newspack-plugins-and-themes-have-a-new-home/' ),
				esc_url( 'https://newspack.com/download-center' )
			);
			?>
		</p>
	</div>
	<?php
}

/*
 * Newspack wizard screens call remove_all_actions() on the notice hooks at priority -9999,
 * so this notice runs ahead of that to stay visible on every admin screen.
 */
add_action( 'all_admin_notices', __NAMESPACE__ . '\\newspack_ads_legacy_repo_notice', -99999 );
