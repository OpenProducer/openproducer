<?php
/**
 * BoldGrid Source Code
 *
 * @package Boldgrid_Inspirations_Customizer
 * @copyright BoldGrid.com
 * @version $Id$
 * @author BoldGrid.com <wpb@boldgrid.com>
 */

/**
 * The BoldGrid Inspirations Customizer class.
 */
class Boldgrid_Inspirations_Customizer {
	/**
	 * Add hooks.
	 */
	public function add_hooks() {
		if ( is_customize_preview() ) {
			// If in admin add CSS and JS to dashboard for widget and styling.
			add_action(
				'customize_controls_print_styles',
				array(
					$this, 'remove_change_themes'
				), 999
			);
		}
	}

	/**
	 * This function adds some styles to the WordPress Customizer.
	 */
	public function remove_change_themes() {
		?>
		<style>
			.button.change-theme {
				display: none;
			}
		</style>
		<?php
	}
}
