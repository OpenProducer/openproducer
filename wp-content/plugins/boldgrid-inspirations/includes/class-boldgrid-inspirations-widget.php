<?php
/**
 * BoldGrid Source Code
 *
 * @package Boldgrid_Inspirations_Widget
 * @copyright BoldGrid.com
 * @version $Id$
 * @author BoldGrid.com <wpb@boldgrid.com>
 */

/**
 * BoldGrid Widget class.
 *
 * @since 1.4
 */
class Boldgrid_Inspirations_Widget {

	/**
	 * Create a widget.
	 *
	 * Create a widget based upon the standard WordPress widgets. The option name is widget_$type,
	 * it's an array, and each instance of that widget has a numeric key.
	 *
	 * @since 1.4
	 *
	 * @param string $type  Such as "search" or "recent-posts".
	 * @param mixed  $value The value of your new widget, such as array().
	 * @return int          The key of the new widget.
	 */
	public static function create_widget( $type, $value ) {
		$widget_name = 'widget_' . $type;

		$widgets = get_option( $widget_name );

		$widgets[] = $value;

		update_option( $widget_name, $widgets );

		return max( array_keys( $widgets ) );
	}

	/**
	 * Return the sidebars_widgets option.
	 *
	 * @since 1.4
	 *
	 * @return array
	 */
	public static function get_sidebars_widgets() {
		return get_option( 'sidebars_widgets', array() );
	}

	/**
	 * Update sidebars_widgets.
	 *
	 * @since 1.4
	 *
	 * @param  string $sidebar The sidebar to update, such as 'sidebar-1'.
	 * @param  string $id      The new widget to add, such as 'search-4'.
	 * @return bool            True if update was successful.
	 */
	public static function add_to_sidebars( $sidebar, $id ) {
		$widgets = self::get_sidebars_widgets();

		$widgets[ $sidebar ][] = $id;

		return self::update_sidebars_widgets( $widgets );
	}

	/**
	 * Empty a sidebar within the sidebars_widgets option.
	 *
	 * @since 1.4
	 *
	 * @param string $sidebar The sidebar to empty, such as 'sidebar-1'.
	 */
	public static function empty_sidebar( $sidebar ) {
		$sidebars_widgets = self::get_sidebars_widgets();

		$sidebars_widgets[ $sidebar ] = array();

		self::update_sidebars_widgets( $sidebars_widgets );
	}

	/**
	 * Update the sidebars_widgets option.
	 *
	 * @since 1.4
	 *
	 * @param array $value The new value.
	 */
	public static function update_sidebars_widgets( $value ) {
		return update_option( 'sidebars_widgets', $value );
	}
}
