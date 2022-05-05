<?php
/**
 * BoldGrid Library Ui Dashboard.
 *
 * @package Boldgrid\Library
 *
 * @version 2.10.0
 * @author BoldGrid <wpb@boldgrid.com>
 */

namespace Boldgrid\Library\Library\Ui;

use Boldgrid\Library\Library\Configs;

/**
 * BoldGrid Library Dashboard Class.
 *
 * @since 2.10.0
 */
class Dashboard {
	/**
	 * Cards.
	 *
	 * @since 2.10.0
	 * @var array
	 */
	public $cards = [];

	/**
	 * Additional classes to add to the main container.
	 *
	 * @since 2.12.0
	 * @var string
	 */
	public $class;

	/**
	 * Enqueue scripts.
	 *
	 * @since 2.10.0
	 */
	public static function enqueueScripts() {
		wp_enqueue_style( 'bglib-dashboard',
			Configs::get( 'libraryUrl' ) . 'src/assets/css/dashboard.css',
			[],
			Configs::get( 'libraryVersion' )
		);
	}

	/**
	 * Print Cards.
	 *
	 * @since 2.10.0
	 */
	public function printCards() {
		echo '<div class="bglib-card-container' .
			( ! empty( $this->classes ) ? ' ' . $this->classes : '' ) . '">';

		foreach ( $this->cards as $card ) {
			$card->init();
			$card->printCard();
		}

		echo '</div>';
	}
}
