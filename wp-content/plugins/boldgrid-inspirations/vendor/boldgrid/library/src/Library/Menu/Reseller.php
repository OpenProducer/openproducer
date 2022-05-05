<?php
/**
* Create the reseller menu in the upper left admin section.
 *
 * @package Boldgrid\Library
 * @subpackage \Library\Menu
 *
 * @version 2.4.0
 * @author BoldGrid <wpb@boldgrid.com>
 */

namespace Boldgrid\Library\Library\Menu;

use Boldgrid\Library\Library\Filter;

/**
* Create the reseller menu in the upper left admin section.
 *
 * @since 2.4.0
 */
class Reseller {

	/**
	 * Reseller DB option.
	 *
	 * @since 2.4.0
	 *
	 * @var array boldgrid_reseller option.
	 */
	protected $resellerOption;

	/**
	 * Add Filters.
	 *
	 * @since 2.4.0
	 */
	public function __construct() {
		$this->resellerOption = get_option( 'boldgrid_reseller' );

		if ( ! empty( $this->resellerOption['reseller_identifier'] ) ) {
			Filter::add( $this );
		}
	}

	/**
	 * Get a specific attribute of our reseller.
	 *
	 * @since 2.8.0
	 *
	 * @param  string $attribute The specific attribute to get.
	 * @return string
	 */
	public function getAttribute( $attribute ) {
		$data = $this->getData();

		return ! empty ( $data[$attribute] ) ? $data[$attribute] : '';
	}

	/**
	 * Get the reseller data.
	 *
	 * @since 2.4.0
	 *
	 * @return array Reseller information.
	 */
	public function getData() {
		$data = $this->resellerOption;

		$data['reseller_identifier'] = ! empty( $data['reseller_identifier'] ) ?
			strtolower( $data['reseller_identifier'] ) : null;

		$data['reseller_website_url'] = ! empty( $data['reseller_website_url'] ) ?
			esc_url( $data['reseller_website_url'] ) : 'https://www.boldgrid.com/';

		$data['reseller_title'] = ! empty( $data['reseller_title'] ) ?
			esc_html( $data['reseller_title'] ) : esc_html( 'BoldGrid.com' );

		$data['reseller_support_url'] = ! empty( $data['reseller_support_url'] ) ?
			esc_url( $data['reseller_support_url'] ) : 'https://www.boldgrid.com/documentation';

		$data['reseller_amp_url'] = ! empty( $data['reseller_amp_url'] ) ?
			esc_url( $data['reseller_amp_url'] ) : 'https://www.boldgrid.com/central';

		return $data;
	}

	/**
	 * Add Menu item configurations.
	 *
	 * @hook: admin_bar_menu
	 * @priority: 15
	 *
	 * @since 2.4.0
	 *
	 * @param WP_Admin_Bar $wpAdminBar Admin Bar.
	 */
	public function addMenu( $wpAdminBar ) {
		Render::adminBarNode( $wpAdminBar, $this->getMenuItems() );
	}

	/**
	 * Get the menu items for this location.
	 *
	 * @since 2.4.0
	 *
	 * @return array Menu Items.
	 */
	protected function getMenuItems() {
		$data = $this->getData();

		return array(
			'topLevel' => array(
				'id' => 'reseller-adminbar-icon',
				'title' => '<span aria-hidden="true" class="' . $data['reseller_identifier'] .
					'-icon ab-icon"></span>',
				'href' => $data['reseller_website_url'],
				'meta' => array(
					'class' => 'reseller-node-icon',
				),
			),
			'items' => array(
				array(
					'id' => 'reseller-site-url',
					'parent' => 'reseller-adminbar-icon',
					'title' => $data['reseller_title'],
					'href' => $data['reseller_website_url'],
					'meta' => array(
						'class' => 'reseller-dropdown',
						'target' => '_blank',
						'title' => $data['reseller_title'],
					),
				),
				array(
					'id' => 'reseller-support-center',
					'parent' => 'reseller-adminbar-icon',
					'title' => esc_html__( 'Support Center', 'boldgrid-library' ),
					'href' => $data['reseller_support_url'],
					'meta' => array(
						'class' => 'reseller-dropdown',
						'target' => '_blank',
						'title' => __( 'Support Center', 'boldgrid-library' ),
					),
				),
				array(
					'id' => 'reseller-amp-login',
					'parent' => 'reseller-adminbar-icon',
					'title' => esc_html__( 'AMP Login', 'boldgrid-library' ),
					'href' => $data['reseller_amp_url'],
					'meta' => array(
						'class' => 'reseller-dropdown',
						'target' => '_blank',
						'title' => __( 'Account Management', 'boldgrid-library' ),
					),
				),
			),
		);
	}
}
