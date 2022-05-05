<?php
/**
 * BoldGrid Source Code
 *
 * @package Boldgrid_Inspirations_Ajax
 * @copyright BoldGrid.com
 * @version $Id$
 * @author BoldGrid.com <wpb@boldgrid.com>
 */

/**
 * The BoldGrid Inspiration Ajax class.
 */
class Boldgrid_Inspirations_Ajax {
	/**
	 * Enqueue.
	 *
	 * @since 1.7.0
	 */
	public static function enqueue() {
		$handle = 'inspiration-ajax';
		wp_register_script(
			$handle,
			plugins_url( '/assets/js/ajax/ajax.js', BOLDGRID_BASE_DIR . '/boldgrid-inspirations.php' ),
			array(),
			BOLDGRID_INSPIRATIONS_VERSION,
			true
		);
		wp_localize_script(
			$handle,
			'BoldGridInspirationsAjax',
			array(
				'checkStatusPage' => sprintf(
					wp_kses(
						// translators: 1 The opening anchor tag to the BoldGrid Status page, 2 its closing anchor tag.
						__( 'If the issue persists, then please feel free to check our %1$sBoldGrid Status%2$s page.', 'boldgrid-inspirations' ),
						array( 'a' => array( 'target' => array( '_blank' ), 'href' => array(), ) )
					),
					'<a target="_blank" href="https://www.boldgrid.com/">',
					'</a>'
				),
				'connectionIssue' => esc_html__( 'BoldGrid Connection Issue', 'boldgrid-inspirations' ),
				'pleaseTryAgain'  => esc_html__( 'There was an issue reaching the BoldGrid Connect server. Some BoldGrid features may be temporarily unavailable. Please try again in a moment.', 'boldgrid-inspirations' ),
				'timeout'         => esc_html__( 'Ajax error: timeout. Please try your request again.', 'boldgrid-inspirations' ),
				'tryAgain'        => esc_html__( 'Try Again', 'boldgrid-inspirations' ),
				'unexpected'      => esc_html__( 'Ajax error: Unexpected return. In some cases, trying your request again may help.', 'boldgrid-inspirations' ),
			)
		);
		wp_enqueue_script( $handle );
	}
}
