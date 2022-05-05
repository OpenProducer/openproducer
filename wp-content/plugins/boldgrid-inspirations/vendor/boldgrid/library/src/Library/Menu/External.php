<?php
/**
 * Create the BoldGrid Menu in the upper left of the admin screen.
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
* Create the BoldGrid Menu in the upper left of the admin screen.
 *
 * @since 2.4.0
 */
class External {

	/**
	 * Add Filters.
	 *
	 * @since 2.4.0
	 */
	public function __construct() {
		Filter::add( $this );
	}

	/**
	 * Add Menu item configurations.
	 *
	 * @hook: admin_bar_menu
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
		return array(
			'topLevel' => array(
				'id' => 'boldgrid-adminbar-icon',
				'title' => '<span aria-hidden="true" class="boldgrid-icon ab-icon"></span>',
				'href' => 'https://www.boldgrid.com/',
				'meta' => array(
					'class' => 'boldgrid-node-icon',
				),
			),
			'items' => array(
				array(
					'id'     => 'boldgrid-site-url',
					'parent' => 'boldgrid-adminbar-icon',
					'title'  => 'BoldGrid.com',
					'href'   => 'https://www.boldgrid.com/',
					'meta'   => array(
						'class'  => 'boldgrid-dropdown',
						'target' => '_blank',
						'title'  => 'BoldGrid.com',
					),
				),
				array(
					'id'     => 'boldgrid-site-documentation',
					'parent' => 'boldgrid-adminbar-icon',
					'title'  => __( 'Documentation', 'boldgrid-library' ),
					'href'   => 'https://www.boldgrid.com/docs',
					'meta'   => array(
						'class'  => 'boldgrid-dropdown',
						'target' => '_blank',
						'title'  => __( 'Documentation', 'boldgrid-library' ),
					),
				),
				array(
					'id'     => 'boldgrid-central-url',
	 				'parent' => 'boldgrid-adminbar-icon',
					'title'  => 'BoldGrid Central',
					'href'   => 'https://www.boldgrid.com/central',
					'meta'   => array(
						'class'  => 'boldgrid-dropdown',
						'target' => '_blank',
						'title'  => 'BoldGrid Central',
					),
				),
				array(
					'id'     => 'boldgrid-connect-url',
					'parent' => 'boldgrid-adminbar-icon',
					'title'  => 'BoldGrid Connect',
					'href'   => get_admin_url( null, 'options-general.php?page=boldgrid-connect.php' ),
					'meta'   => array(
						'class' => 'boldgrid-dropdown',
						'title' => 'BoldGrid Connect',
					),
				),
				array(
					'id'     => 'boldgrid-feedback-url',
					'parent' => 'boldgrid-adminbar-icon',
					'title'  => __( 'Feedback', 'boldgrid-library' ),
					'href' => 'https://www.boldgrid.com/feedback',
					'meta' => array(
						'class'  => 'boldgrid-dropdown',
						'target' => '_blank',
						'title'  => __( 'Feedback', 'boldgrid-library' ),
					),
				),
			),
		);
	}
}
