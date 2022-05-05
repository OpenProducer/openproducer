<?php
/**
 * BoldGrid Source Code
 *
 * @copyright BoldGrid.com
 * @version $Id$
 * @author BoldGrid.com <wpb@boldgrid.com>
 */

namespace Boldgrid\Inspirations\Premium;

/**
 * The BoldGrid Attribution class.
 *
 * Handles the "Built with BoldGrid" attribution.
 *
 * @since SINCEVERSION
 */
class Boldgrid_Attribution {
	/**
	 * Add hooks.
	 *
	 * This class does not handle any business logic, only the controls.
	 *
	 * @since SINCEVERSION
	 */
	public function addHooks() {
		add_action( 'customize_register', array( $this, 'showControl' ) );

		// @see Crio_Premium::define_customizer_hooks()
		add_action( 'customize_save_after', array( $this, 'saveSetting' ), 998 );
	}

	/**
	 * Save the setting on customizer save.
	 *
	 * @since SINCEVERSION
	 *
	 * @see Crio_Premium_Customizer::pre_attribution()
	 */
	public function saveSetting() {
		$theme_mod = get_theme_mod( 'hide_boldgrid_attribution' );
		add_action( 'customize_save_after', function() use ( $theme_mod ) {
			set_theme_mod( 'hide_boldgrid_attribution', $theme_mod );
		}, 1000 );
	}

	/**
	 * Show the control to hide it.
	 *
	 * Once, this was a privilege only for Crio Pro. When Crio was added to Inspirations, this control
	 * was made available to all "premium" users.
	 *
	 * @since SINCEVERSION
	 *
	 * @see Crio_Premium_Customizer::add_attribution_controls
	 */
	public function showControl() {
		remove_action( 'customize_controls_print_styles', array( 'Boldgrid_Framework_Customizer_Footer', 'customize_attribution' ), 999 );
	}
}
