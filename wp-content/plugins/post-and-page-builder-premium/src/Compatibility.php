<?php
/**
* File: BGPPBP_Compatibility.php
*
* Check if the plugin is compatible.
*
* @since      1.0.0
* @package    BGPPBP_Compatibility
* @author     BoldGrid <support@boldgrid.com>
* @link       https://boldgrid.com
*/

/**
* Class: BGPPBP_Compatibility
*
* Check if the plugin is compatible. Note, this file should be compatible with PHP Version 5.2.
*
* @since 1.0.0
*/
class BGPPBP_Compatibility {

	/**
	 * Required WP version.
	 *
	 * @since 1.0.0
	 * @var string Version Number
	 */
	public static $requiredWPVersion = '4.7';

	/**
	 * Required WP version.
	 *
	 * @since 1.0.0
	 * @var string Version Number
	 */
	public static $requiredPHPVersion = '5.4';

	/**
	 *  Check to see if WordPress version is installed at our required minimum or deactivate.
	 *
	 * @since 1.0.0
	 */
	public function checkVersions() {
		global $wp_version;

		$valid = true;
		if ( version_compare( $wp_version, self::$requiredWPVersion, '<' ) ) {
			add_action( 'admin_init', array( $this, 'deactivatePlugin' ) );
			add_action( 'admin_notices', array( $this, 'invalidWPVersion' ) );
			$valid = false;
		} else if ( version_compare( phpversion(), self::$requiredPHPVersion, '<' )  ) {
			add_action( 'admin_init', array( $this, 'deactivatePlugin' ) );
			add_action( 'admin_notices', array( $this, 'invalidPHPVersion' ) );
			$valid = false;
		}

		return $valid;
	}

	/**
	 * Deactivate the Plugin.
	 *
	 * @since 1.0.0
	 */
	public function deactivatePlugin() {
		deactivate_plugins( BGPPB_PREMIUM_PATH . '/post-and-page-builder-premium.php' );
	}

	/**
	 * Show a notice if the user has an invalid WordPress Version.
	 *
	 * @since 1.0.0
	 */
	public function invalidWPVersion() { ?>
		<div class="notice notice-error">
			<p><?php _e( 'Failed to activate the Post and Page Builder Premium! Your WordPress version is not compatible. This plugin requires at least WordPress ' . self::$requiredWPVersion, 'boldgrid-editor' ); ?></p>
		</div>
		<?php
	}

	/**
	 * Show a notice if the user has an invalid PHP Version.
	 *
	 * @since 1.0.0
	 */
	public function invalidPHPVersion() { ?>
		<div class="notice notice-error">
			<p><?php _e( 'Failed to activate the Post and Page Builder Premium! Your PHP version is not compatible. This plugin requires at least PHP ' . self::$requiredPHPVersion, 'boldgrid-editor' ); ?></p>
		</div>
		<?php
	}

}
