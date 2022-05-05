<?php
/**
* Class: Boldgrid_Editor_Activate
*
* Plugin Activation hooks.
*
* @since      1.3
* @package    Boldgrid_Editor
* @subpackage Boldgrid_Editor_Activate
* @author     BoldGrid <support@boldgrid.com>
* @link       https://boldgrid.com
*/

/**
* Class: Boldgrid_Editor_Activate
*
* Plugin Activation hooks.
*
* @since      1.3
*/
class Boldgrid_Editor_Activate {

	/**
	 * Run actions that should occur when plugin activated.
	 *
	 * @since 1.3.
	 * @return HTML to be inserted.
	 */
	public static function on_activate() {
		Boldgrid_Editor_Option::update( 'activated_version', BOLDGRID_EDITOR_VERSION );
		Boldgrid_Editor_Option::update( 'has_flushed_rewrite', 0 );
	}

	/**
	 * On plugin deactivation.
	 *
	 * @since 1.6
	 */
	public static function on_deactivate() {
		flush_rewrite_rules();
		Boldgrid_Editor_Preview::delete_post();
	}

	/**
	 * Block the activation of boldgrid-editor.
	 *
	 * @since 1.6.1
	 */
	public static function block_activate() {
		wp_die(
			'BoldGrid Editor has been renamed to Post and Page Builder. You can delete the '.
			'BoldGrid Editor plugin and continue using the Post and Page Builder plugin.',
			'Plugin Activation Failed',
			array(
				'back_link' => true,
			)
		);
	}
}
