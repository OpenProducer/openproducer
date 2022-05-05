<?php
/**
 * BoldGrid Library Reseller Class
 *
 * @package Boldgrid\Library
 * @subpackage \Library
 *
 * @version 1.1
 * @author BoldGrid <wpb@boldgrid.com>
 */

namespace Boldgrid\Library\Library;

/**
 * BoldGrid Library Reseller Class.
 *
 * @since 1.1
 */
class Reseller {

	/**
	 * BoldGrid Central url.
	 *
	 * @since 1.1
	 *
	 * @var string
	 */
	public $centralUrl = 'https://www.boldgrid.com/central/';

	/**
	 * Reseller data.
	 *
	 * @since 1.1
	 *
	 * @var array
	 */
	public $data = array();

	/**
	 * Constructor.
	 *
	 * @since 1.1
	 */
	public function __construct() {
		$this->setData();
		Filter::add( $this );
	}

	/**
	 * Whether or not the reseller has a unique coin url.
	 *
	 * By default, the "purchase more coins" url (reseller_coin_url) is set to be the BoldGrid
	 * Central url (https://www.boldgrid.com/central/). This method returns true if the reseller
	 * has their own coin url.
	 *
	 * @since 2.9.1
	 *
	 * @return bool
	 */
	public function hasCoinUrl() {
		return trailingslashit( $this->centralUrl ) !== trailingslashit( $this->data['reseller_coin_url'] );
	}

	/**
	 * Set data.
	 *
	 * @since 1.1
	 *
	 * @hook: update_option_boldgrid_reseller
	 */
	public function setData() {
		$defaults = array(
				'reseller_coin_url' => $this->centralUrl,
		);

		$this->data = array_merge( $defaults, get_option( 'boldgrid_reseller', array() ) );
	}
}
