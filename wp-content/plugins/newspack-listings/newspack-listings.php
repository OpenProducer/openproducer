<?php
/**
 * Plugin Name:     Newspack Listings (final version, please migrate)
 * Plugin URI:      https://newspack.com
 * Description:     Final version released from the legacy plugin repository. This copy will not receive further updates. Download the current version at https://newspack.com/download-center
 * Author:          Automattic
 * Author URI:      https://newspack.com
 * Text Domain:     newspack-listings
 * Domain Path:     /languages
 * Version:         3.6.3
 *
 * @package         Newspack_Listings
 */

defined( 'ABSPATH' ) || exit;

// Define plugin constants.
if ( ! defined( 'NEWSPACK_LISTINGS_PLUGIN_FILE' ) ) {
	define( 'NEWSPACK_LISTINGS_FILE', __FILE__ );
	define( 'NEWSPACK_LISTINGS_PLUGIN_FILE', plugin_dir_path( NEWSPACK_LISTINGS_FILE ) );
	define( 'NEWSPACK_LISTINGS_URL', plugin_dir_url( NEWSPACK_LISTINGS_FILE ) );
	define( 'NEWSPACK_LISTINGS_VERSION', '3.6.3' );
}

// Include plugin resources.
require_once NEWSPACK_LISTINGS_PLUGIN_FILE . '/vendor/autoload.php';
require_once NEWSPACK_LISTINGS_PLUGIN_FILE . '/includes/utils.php';
require_once NEWSPACK_LISTINGS_PLUGIN_FILE . '/includes/class-settings.php';
require_once NEWSPACK_LISTINGS_PLUGIN_FILE . '/includes/class-core.php';
require_once NEWSPACK_LISTINGS_PLUGIN_FILE . '/includes/class-blocks.php';
require_once NEWSPACK_LISTINGS_PLUGIN_FILE . '/includes/class-block-patterns.php';
require_once NEWSPACK_LISTINGS_PLUGIN_FILE . '/includes/class-api.php';

// Enable experimental/in-progress self-serve listings functionality.
require_once NEWSPACK_LISTINGS_PLUGIN_FILE . '/includes/class-featured.php';
require_once NEWSPACK_LISTINGS_PLUGIN_FILE . '/includes/class-products.php';

// CLI importer files.
require_once NEWSPACK_LISTINGS_PLUGIN_FILE . '/includes/importer/importer-utils.php';
require_once NEWSPACK_LISTINGS_PLUGIN_FILE . '/includes/importer/class-importer.php';

// Migration utilities.
require_once NEWSPACK_LISTINGS_PLUGIN_FILE . '/includes/migration/class-migration.php';

/**
 * Warn administrators that this build came from the legacy plugin repository.
 */
function newspack_listings_legacy_repo_notice() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	?>
	<div class="notice notice-error">
		<p><strong><?php esc_html_e( 'You are running an outdated version of the Newspack Listings plugin.', 'newspack-listings' ); ?></strong></p>
		<p>
			<?php
			printf(
				wp_kses(
					/* translators: 1: URL of the announcement post. 2: URL of the download center. */
					__( 'This is the final version released from the legacy plugin repository, and it will not receive further updates. <a href="%1$s">Read the announcement</a>, then download the current version from the <a href="%2$s">Newspack download center</a>.', 'newspack-listings' ),
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
add_action( 'all_admin_notices', 'newspack_listings_legacy_repo_notice', -99999 );
