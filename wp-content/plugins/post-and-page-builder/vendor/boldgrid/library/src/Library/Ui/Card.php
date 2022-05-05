<?php
/**
 * BoldGrid Library Ui Card.
 *
 * @package Boldgrid\Library
 *
 * @version 2.10.0
 * @author BoldGrid <wpb@boldgrid.com>
 */

namespace Boldgrid\Library\Library\Ui;

/**
 * BoldGrid Library Util Card Class.
 *
 * @since 2.10.0
 */
class Card {
	/**
	 * Footer.
	 *
	 * Generally where the content is going.
	 *
	 * @since 2.10.0
	 * @var string
	 */
	public $footer;

	/**
	 * Icon.
	 *
	 * Generally markup for a span, a dashicon.
	 *
	 * @since 2.10.0
	 * @var string
	 */
	public $icon;

	/**
	 * Features.
	 *
	 * An array of features.
	 *
	 * @since 2.10.0
	 * @var array
	 */
	public $features = [];

	/**
	 * Id.
	 *
	 * @since 2.10.0
	 * @var string
	 */
	public $id;

	/**
	 * Page.
	 *
	 * @since 2.12.0
	 * @var Boldgrid\Library\Library\Plugin\Page
	 */
	public $page;

	/**
	 * Sub title.
	 *
	 * @since 2.10.0
	 * @var string
	 */
	public $subTitle;

	/**
	 * Title.
	 *
	 * @since 2.10.0
	 * @var string
	 */
	public $title;

	/**
	 * Links.
	 *
	 * Used to add links to a sub-footer links div.
	 *
	 * @since 2.12.0
	 * @var string
	 */
	public $links;

	/**
	 * Constructor
	 *
	 * @param Boldgrid\Library\Library\Plugin\Page $page
	 * @since 2.12.0
	 */
	public function __construct( $page = null ) {
		$this->page = $page;
	}

	/**
	 * Determine whether or not we need to show the ribbon.
	 *
	 * @since 2.12.0
	 *
	 * @return bool
	 */
	public function maybeShowRibbon() {
		// Not all cards area assigned to a page.
		return ! empty( $this->page ) &&
			$this->page->getNoticeById( $this->id ) &&
			$this->page->getNoticeById( $this->id )->getIsUnread();
	}

	/**
	 * Print the card.
	 *
	 * @since 2.10.0
	 *
	 * @param  bool  $echo True to print the card, false to return the markup.
	 * @return mixed.
	 */
	public function printCard( $echo = true ) {
		// Before printing, initialize all of the features.
		foreach ( $this->features as $feature ) {
			$feature->init();
			$this->footer .= $feature->printFeature( false );
		}

		// Create the opening tag.
		$markup = '<div class="bglib-card" ';
		if ( ! empty( $this->id ) ) {
			$markup .= 'id="' . esc_attr( $this->id ) . '" ';
		}
		$markup .= '>';

		// If we need to, add a "new" banner.
		if ( $this->maybeShowRibbon() ) {
			$markup .= '<div class="card-ribbon"><span>' . esc_html__( 'NEW!', 'boldgrid-backup' ) . '</span></div>';
		}

		if ( ! empty( $this->title ) ) {
			$markup .= '<div class="bglib-card-title">';

			$markup .= '<p>' . $this->title . '</p>';

			if ( ! empty( $this->subTitle ) ) {
				$markup .= '<div class="bglib-card-subtitle">' . esc_html( $this->subTitle ) . '</div>';
			}

			$markup .= '</div>';
		}

		if ( ! empty( $this->icon ) ) {
			$markup .= '<div class="bglib-card-icon">' . $this->icon . '</div>';
		}

		if ( ! empty( $this->footer ) ) {
			$markup .= '<div class="bglib-card-footer">' . $this->footer . '</div>';
		}

		// Links can be placed in footer, but when displaying multiple cards, if you want the links
		// To be uniform, add them to the $this->links section.
		if ( ! empty( $this->links ) ) {
			$markup .= '
			<div class="bglib-card-links">' .
				$this->links .
			'</div>';
		}

		$markup .= '</div>';

		if ( $echo ) {
			echo $markup;
		} else {
			return $markup;
		}
	}
}
