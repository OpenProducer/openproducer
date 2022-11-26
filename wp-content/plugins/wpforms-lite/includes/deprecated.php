<?php
// phpcs:ignoreFile
/**
 * To be compatible with both WP 4.9 (that can run on PHP 5.2+) and WP 5.3+ (PHP 5.6+)
 * we need to rewrite some core WP classes and tweak our own skins to not use PHP 5.6 splat operator (...$args)
 * that were introduced in WP 5.3 in \WP_Upgrader_Skin::feedback().
 * This alias is a safeguard to those developers who decided to use our internal class WPForms_Install_Silent_Skin,
 * which we deleted.
 *
 * @since 1.5.6.1
 */
class_alias( 'WPForms\Helpers\PluginSilentUpgraderSkin', 'WPForms_Install_Silent_Skin' );

/**
 * Legacy `WPForms_Addons` class was refactored and moved to the new `WPForms\Pro\Admin\Pages\Addons` class.
 * This alias is a safeguard to those developers who use our internal class WPForms_Addons,
 * which we deleted.
 *
 * @since 1.6.7
 */
class_alias( wpforms()->is_pro() ? 'WPForms\Pro\Admin\Pages\Addons' : 'WPForms\Lite\Admin\Pages\Addons', 'WPForms_Addons' );

/**
 * This alias is a safeguard to those developers who decided to use our internal class WPForms_Smart_Tags,
 * which we deleted.
 *
 * @since 1.6.7
 */
class_alias( wpforms()->is_pro() ? 'WPForms\Pro\SmartTags\SmartTags' : 'WPForms\SmartTags\SmartTags', 'WPForms_Smart_Tags' );

/**
 * This alias is a safeguard to those developers who decided to use our internal class \WPForms\Providers\Loader,
 * which we deleted.
 *
 * @since 1.7.3
 */
class_alias( '\WPForms\Providers\Providers', '\WPForms\Providers\Loader' );

/**
 * Legacy `\WPForms\Admin\Notifications` class was refactored and moved to the new `\WPForms\Admin\Notifications\Notifications` class.
 * This alias is a safeguard to those developers who use our internal class \WPForms\Admin\Notifications,
 * which we deleted.
 *
 * @since 1.7.5
 */
class_alias( '\WPForms\Admin\Notifications\Notifications', '\WPForms\Admin\Notifications' );

/**
 * Legacy `\WPForms\Migrations` class was refactored and moved to the new `\WPForms\Migrations\Migrations` class.
 * This alias is a safeguard to those developers who use our internal class \WPForms\Migrations, which we deleted.
 *
 * @since 1.7.5
 */
class_alias( '\WPForms\Migrations\Migrations', '\WPForms\Migrations' );

if ( wpforms()->is_pro() ) {
	/**
	 * Legacy `\WPForms\Pro\Migrations` class was refactored and moved to the new `\WPForms\Pro\Migrations\Migrations` class.
	 * This alias is a safeguard to those developers who use our internal class \WPForms\Migrations, which we deleted.
	 *
	 * @since 1.7.5
	 */
	class_alias( '\WPForms\Pro\Migrations\Migrations', '\WPForms\Pro\Migrations' );
}

/**
 * Get notification state, whether it's opened or closed.
 *
 * @deprecated 1.4.8
 *
 * @since 1.4.1
 *
 * @param int $form_id         Form ID.
 * @param int $notification_id Notification ID.
 *
 * @return string
 */
function wpforms_builder_notification_get_state( $form_id, $notification_id ) {

	_deprecated_function( __FUNCTION__, '1.4.8 of the WPForms addon', 'wpforms_builder_settings_block_get_state()' );

	return wpforms_builder_settings_block_get_state( $form_id, $notification_id, 'notification' );
}

/**
 * Convert bytes to megabytes (or in some cases KB).
 *
 * @deprecated 1.6.2
 *
 * @since 1.0.0
 *
 * @param int $bytes Bytes to convert to a readable format.
 *
 * @return string
 */
function wpforms_size_to_megabytes( $bytes ) {

	_deprecated_function( __FUNCTION__, '1.6.2 of the WPForms plugin', 'size_format()' );

	return size_format( $bytes );
}

/**
 * Check the current PHP version and display a notice if on unsupported PHP.
 *
 * @deprecated 1.7.7 Unnecessary function since we check the PHP version earlier in the main plugin file.
 *
 * @since 1.4.0.1
 * @since 1.5.0 Raising this awareness of old PHP version message from 5.2 to 5.3.
 */
function wpforms_check_php_version() {

	_deprecated_function( __METHOD__, '1.7.7 of the WPForms plugin' );

	// Display for PHP below 5.6.
	if ( version_compare( PHP_VERSION, '5.5', '>=' ) ) {
		return;
	}

	// Display for admins only.
	if ( ! is_super_admin() ) {
		return;
	}

	// Display on Dashboard page only.
	if ( isset( $GLOBALS['pagenow'] ) && $GLOBALS['pagenow'] !== 'index.php' ) {
		return;
	}

	// Display the notice, finally.
	\WPForms\Admin\Notice::error(
		'<p>' .
		sprintf(
			wp_kses( /* translators: %1$s - WPForms plugin name; %2$s - WPForms.com URL to a related doc. */
				__( 'Your site is running an outdated version of PHP that is no longer supported and may cause issues with %1$s. <a href="%2$s" target="_blank" rel="noopener noreferrer">Read more</a> for additional information.', 'wpforms-lite' ),
				[
					'a' => [
						'href'   => [],
						'target' => [],
						'rel'    => [],
					],
				]
			),
			'<strong>WPForms</strong>',
			'https://wpforms.com/docs/supported-php-version/'
		) .
		'<br><br><em>' .
		wp_kses(
			__( '<strong>Please Note:</strong> Support for PHP 5.5 will be discontinued in 2020. After this, if no further action is taken, WPForms functionality will be disabled.', 'wpforms-lite' ),
			[
				'strong' => [],
				'em'     => [],
			]
		) .
		'</em></p>'
	);
}
