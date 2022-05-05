<?php
/**
 * File: class-boldgrid-editor-bgtfw-template.php
 *
 * Handle template hooks and filters for the boldgrid theme framework.
 *
 * @since      1.6
 * @package    Boldgrid_Editor
 * @subpackage Boldgrid_Editor_BGTFW_Template
 * @author     BoldGrid <support@boldgrid.com>
 * @link       https://boldgrid.com
 */

/**
 * Class: Boldgrid_Editor_BGTFW_Template
 *
 * Handle template hooks and filters for the boldgrid theme framework.
 *
 * @since      1.2
 */
class Boldgrid_Editor_BGTFW_Template {

	/**
	 * Run the setup process.
	 *
	 * @since 1.6
	 */
	public function init() {
		if ( ! is_admin() ) {
			add_filter( 'boldgrid/display_sidebar', array( $this, 'disable_template_sidebar' ) );
		}
	}

	/**
	 * If we've saved the template to a Editor template, disable the sidebar in the framework for our template.
	 *
	 * @since 1.6
	 *
	 * @param  boolean $display Whether or not to display the sidebar.
	 * @return boolean          Whether or not to display the sidebar.
	 */
	public function disable_template_sidebar( $display ) {
		$template_service = Boldgrid_Editor_Service::get( 'templater' );
		$template_slug = get_page_template_slug();

		if ( $template_service->is_custom_template( $template_slug ) ) {
			$display = false;
		}

		return $display;
	}
}
