<?php
/**
* File: Development.php
*
* Check if the plugin can load.
*
* @since      1.0.0
* @package    Boldgrid\PPBP
* @author     BoldGrid <support@boldgrid.com>
* @link       https://boldgrid.com
*/

namespace Boldgrid\PPBP;

/**
* Class: Development
*
* Check if the plugin can load.
*
* @since 1.0.0
*/
class Development {

	/**
	 * Github URL.
	 *
	 * @since 1.0.0
	 * @var string URL.
	 */
	protected static $gitUrl = 'https://github.com/BoldGrid/post-and-page-builder-premium';

	/**
	 * Download URL for the plugin.
	 * @var string URL.
	 */
	protected static $downloadUrl = 'https://www.boldgrid.com/central';

	/**
	 * Prevent loading the plugin without a full build.
	 *
	 * @since 1.0.0
	 *
	 * @return boolean Whether or not we prevented loading.
	 */
	public function checkValidBuild() {
		$valid = true;
		if ( ! file_exists( BGPPB_PREMIUM_PATH . '/dist/' ) ) {
			add_action( 'admin_notices', array( $this, 'showNotice' ) );

			$valid = false;
		}

		return $valid;
	}


	/**
	 * Show a notice informming the user that activation was prevented.
	 *
	 * @since 1.0.0
	 */
	public function showNotice() { ?>
	<div class="notice notice-error"><p>
			<?php
			printf(
				esc_html__(
					'It looks like your running an incomplete development build of %1$sPost and Page Builder Premium%2$s. Please download the stable version from %3$s. If your a developer, please see the %4$s page for more information on contributing.',
					'boldgrid-editor'
				),
				'<strong>',
				'</strong>',
				'<a target="_blank" href="' . self::$downloadUrl . '">' . self::$downloadUrl . '</a>',
				'<a target="_blank" href="' . self::$gitUrl . '">GitHub</a>'
			);
			?>
		</p></div>
			<?php
	}
}
