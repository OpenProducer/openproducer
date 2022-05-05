<?php
/**
 * BoldGrid Library Ui Feature.
 *
 * @package Boldgrid\Library
 *
 * @version 2.10.0
 * @author BoldGrid <wpb@boldgrid.com>
 */

namespace Boldgrid\Library\Library\Ui;

/**
 * BoldGrid Library Util Feature Class.
 *
 * @since 2.10.0
 */
class Feature {
	/**
	 * Feature icon.
	 *
	 * Generally, markup for a span, such as a dashicon.
	 *
	 * @since 2.10.0
	 * @var string
	 */
	public $icon;

	/**
	 * Feature title.
	 *
	 * @since 2.10.0
	 * @var string
	 */
	public $title;

	/**
	 * Feature content.
	 *
	 * @since 2.10.0
	 * @var string
	 */
	public $content;

	/**
	 * Init.
	 *
	 * Classes extended this class are expected to write their own init.
	 *
	 * @since 2.10.0
	 */
	public function init() {
	}

	/**
	 * Print this feature.
	 *
	 * @since 2.10.0
	 *
	 * @param  bool  $echo Whether to echo the feature now, or return the markup.
	 * @retrun mixed
	 */
	public function printFeature( $echo = true ) {
		$markup = '<div class="bglib-feature">
			<div class="bglib-feature-icon">
				' . $this->icon . '
			</div>
			<div class="bglib-feature-right">
				<div class="bglib-feature-title">' . esc_html( $this->title ) . '</div>
				<div class="bglib-feature-content">' . $this->content . '</div>
			</div>
		</div>';

		if ( $echo ) {
			echo $markup;
		}

		return $echo ? true : $markup;
	}
}
