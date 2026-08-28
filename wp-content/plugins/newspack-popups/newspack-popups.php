<?php
/**
 * Plugin Name:     Newspack Campaigns (final version, please migrate)
 * Plugin URI:      https://newspack.com
 * Description:     Final version released from the legacy plugin repository. This copy will not receive further updates. Download the current version at https://newspack.com/download-center
 * Author:          Automattic
 * Author URI:      https://newspack.com
 * Text Domain:     newspack-popups
 * Domain Path:     /languages
 * Version:         3.12.2
 *
 * @package         Newspack_Popups
 */

defined( 'ABSPATH' ) || exit;

// Define the plugin file path.
if ( ! defined( 'NEWSPACK_POPUPS_PLUGIN_FILE' ) ) {
	define( 'NEWSPACK_POPUPS_PLUGIN_FILE', __FILE__ );
}

require_once __DIR__ . '/vendor/autoload.php';

// Include the main Newspack Google Ad Manager class.
if ( ! class_exists( 'Newspack_Popups' ) ) {
	include_once __DIR__ . '/includes/class-newspack-popups.php';
}

/**
 * Warn administrators that this build came from the legacy plugin repository.
 */
function newspack_popups_legacy_repo_notice() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	?>
	<div class="notice notice-error">
		<p><strong><?php esc_html_e( 'You are running an outdated version of the Newspack Campaigns plugin.', 'newspack-popups' ); ?></strong></p>
		<p>
			<?php
			printf(
				wp_kses(
					/* translators: 1: URL of the announcement post. 2: URL of the download center. */
					__( 'This is the final version released from the legacy plugin repository, and it will not receive further updates. <a href="%1$s">Read the announcement</a>, then download the current version from the <a href="%2$s">Newspack download center</a>.', 'newspack-popups' ),
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
add_action( 'all_admin_notices', 'newspack_popups_legacy_repo_notice', -99999 );
