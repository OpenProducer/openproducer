<?php
/**
 * BoldGrid Library Dashboard Sort Widgets Class
 *
 * @package Boldgrid\Library
 * @subpackage \Library\License
 *
 * @version 2.9.0
 * @author BoldGrid <wpb@boldgrid.com>
 */

namespace Boldgrid\Library\Library\Dashboard;

use BoldGrid\Library\Library;

/**
 * BoldGrid Library Dashboard Sort Widgets Class.
 *
 * @since 2.9.0
 */
class SortWidgets {
	/**
	 * Constructor.
	 *
	 * @since 2.9.0
	 */
	public function __construct() {
		Library\Filter::add( $this );
	}

	/**
	 * Get configs.
	 *
	 * Configs are stored in library.global.php. They're an array of widgets, along with which
	 * container and priorty they should go.
	 *
	 * @since 2.9.0
	 *
	 * @return array
	 */
	public function getConfigs() {
		return Library\Configs::get( 'dashboardWidgetOrder' );
	}

	/**
	 * Get a widget from the global $wp_meta_boxes.
	 *
	 * As we loop through the global $wp_meta_boxes, we keep track of where within it our widget is.
	 * That locaion info includes the container (normal, side, etc) and priority (high, core, etc).
	 *
	 * This method is primarily used to find a widget within the global array so we can remove it.
	 *
	 * @since 2.9.0
	 *
	 * @global array $wp_meta_boxes
	 *
	 * @param  string $id The id of the widget to find.
	 * @return array
	 */
	public function getWidget( $id ) {
		$widget = array();

		// Example structure of $wp_meta_boxes - https://pastebin.com/uYqDEZgm
		global $wp_meta_boxes;

		// We're only interested in dashboard widgets.
		if ( empty( $wp_meta_boxes['dashboard'] ) ) {
			return $widget;
		}

		// Complicated looping below because of $wp_meta_boxes structure. See example above.
		foreach ( $wp_meta_boxes['dashboard'] as $container => $priorities ) {
			foreach ( $priorities as $priority => $widgets ) {
				foreach ( $widgets as $widget ) {
					if ( $id === $widget['id'] ) {
						$widget = array(
							'container' => $container,
							'priority'  => $priority,
							'widget'    => $widget,
						);

						break 3;
					}
				}
			}
		}

		return $widget;
	}

	/**
	 * Sort the widgets as contained in the user's "meta-box-order_dashboard" meta.
	 *
	 * We are deleting our widgets, and then adding them back in.
	 *
	 * @since 2.9.0
	 */
	public function sortCustomOrder() {
		$userId = get_current_user_id();

		$userWidgetOrder = get_user_meta( $userId, 'meta-box-order_dashboard', true );

		$widgetConfigs = $this->getConfigs();

		// First, delete all of our widgets.
		foreach ( $userWidgetOrder as $container => $widgets ) {
			$widgets = explode( ',', $widgets );

			foreach ( array_keys( $widgetConfigs ) as $id ) {
				if ( ( $key = array_search( $id, $widgets ) ) !== false ) {
					unset( $widgets[ $key ] );
				}
			}

			$userWidgetOrder[ $container ] = implode( ',', $widgets );
		}

		// Then, add them back in.
		foreach ( $widgetConfigs as $id => $configs ) {
			switch( empty( $userWidgetOrder[ $configs['container'] ] ) ) {
				case true:
					$userWidgetOrder[ $configs['container'] ] = $id;
					break;
				case false:
					$userWidgetOrder[ $configs['container'] ] = $id . ',' . $userWidgetOrder[ $configs['container'] ];
					break;
			}
		}
		update_user_meta( $userId, 'meta-box-order_dashboard', $userWidgetOrder );
	}

	/**
	 * Sort the widgets as containted in the global $wp_meta_boxes.
	 *
	 * @since 2.9.0
	 */
	public function sortGlobal() {
		global $wp_meta_boxes;

		foreach ( $this->getConfigs() as $id => $configs ) {
			$widget = $this->getWidget( $id );

			$invalidData = empty( $widget['container'] ) || empty( $widget['priority'] );
			if ( $invalidData ) {
				continue;
			}

			// First, remove the widget from where it is now.
			unset( $wp_meta_boxes['dashboard'][$widget['container']][$widget['priority']][$id] );

			// Then, add it to the correct location.
			$wp_meta_boxes['dashboard'][ $configs['container'] ][ $configs['priority'] ] =
				array( $id => $widget['widget'] ) +
				$wp_meta_boxes['dashboard'][ $configs['container'] ][ $configs['priority'] ];
		}
	}

	/**
	 * Filter the display order of widgets on the dashboard.
	 *
	 * Primary objective is to place BoldGrid widgets at the top.
	 *
	 * If the user has a custom display order (IE they've dragged things around), we'll force our
	 * widgets to the top only once. This way, we can force our widgets to the top, but if they want
	 * to move them lower, they are free to do so.
	 *
	 * @priority 20
	 */
	public function wp_dashboard_setup() {
		$userId = get_current_user_id();

		// Debug.
		// delete_user_meta( $userId, 'meta-box-order_dashboard' );
		// delete_user_meta( $userId, 'bglibDashboardOrder' );

		// Whether the user has dragged widgets around on their dashboard and changed the order.
		$userChangedOrder = get_user_meta( get_current_user_id(), 'meta-box-order_dashboard', true );
		$userChangedOrder = ! empty( $userChangedOrder );

		// Whether bglib has already changed the user's own custom dashboard widget order.
		$hasChangedLibrary = get_user_meta( $userId, 'bglibDashboardOrder', true );
		$hasChangedLibrary = ! empty( $hasChangedLibrary );

		$userChangedOrder && ! $hasChangedLibrary ? $this->sortCustomOrder() : $this->sortGlobal();

		// Flag that we have updated the user's dashboard widget sort order.
		update_user_meta( $userId, 'bglibDashboardOrder', 1 );
	}
}
