<?php
/**
 * BoldGrid Source Code
 *
 * @package Boldgrid_Inspirations_Blog
 * @copyright BoldGrid.com
 * @version $Id$
 * @author BoldGrid.com <wpb@boldgrid.com>
 */

/**
 * BoldGrid Blog class.
 *
 * @since 1.4
 */
class Boldgrid_Inspirations_Blog {

	/**
	 * The Blog category id.
	 *
	 * @since 1.4
	 * @var   int
	 */
	public $category_id;

	/**
	 * Configs.
	 *
	 * @since 1.4
	 * @var   array
	 */
	public $configs;

	/**
	 * Constructor.
	 *
	 * @since 1.4
	 *
	 * @param array $configs
	 */
	public function __construct( $configs = array() ) {
		$this->configs = $configs;
	}

	/**
	 * Create the blog category.
	 *
	 * @since 1.4
	 */
	public function create_category() {
		$category = get_category_by_slug( __( 'Blog', 'boldgrid-inspirations' ) );

		if( $category ) {
			$this->category_id = $category->term_id;
		} else {
			$this->category_id = wp_create_category( __( 'Blog', 'boldgrid-inspirations' ) );
		}
	}

	/**
	 * Create the blog menu item.
	 *
	 * @since 1.4
	 *
	 * @param int $menu_id
	 * @param int $menu_order
	 */
	public function create_menu_item( $menu_id, $menu_order ) {
		$data = array(
			'menu-item-title' => __( 'Blog', 'boldgrid-inspirations' ),
			'menu-item-object-id' => $this->category_id,
			'menu-item-db-id' => 0,
			'menu-item-object' => 'category',
			'menu-item-parent-id' => 0,
			'menu-item-type' => 'taxonomy',
			'menu-item-url' => get_category_link( $this->category_id ),
			'menu-item-status' => 'publish',
			'menu-item-position' => $menu_order,
		);

		return wp_update_nav_menu_item( $menu_id, 0, $data );
	}

	/**
	 * Create widgets.
	 *
	 * During an Inspirations Deployment, if we are installing a blog, create a set of widgets and
	 * add them to the sidebar.
	 *
	 * @since 1.4
	 */
	public function create_sidebar_widgets() {
		$sidebar = 'sidebar-1';

		/**
		 * Filter the sidebar to add our new widgets to.
		 *
		 * Not all themes have a 'sidebar-1'.
		 *
		 * @since 1.4
		 *
		 * @param string $sidebar.
		 */
		$sidebar = apply_filters( 'boldgrid_deploy_blog_sidebar', $sidebar );

		$widgets_to_create = $this->configs[ 'new_blog_widgets' ];

		/**
		 * Filter the widgets that we will create.
		 *
		 * @since 1.4
		 *
		 * @param array $widgets_to_create An array of widgets.
		 */
		$widgets_to_create = apply_filters( 'boldgrid_deploy_blog_widgets', $widgets_to_create );

		/*
		 * Empty the sidebar before we start adding widgets to it, otherwise we will end up with
		 * duplicate items in the sidebar after more than one deployment.
		 */
		Boldgrid_Inspirations_Widget::empty_sidebar( $sidebar );

		foreach( $widgets_to_create as $widget ) {
			$key = Boldgrid_Inspirations_Widget::create_widget( $widget['type'], $widget['value'] );

			Boldgrid_Inspirations_Widget::add_to_sidebars( $sidebar, $widget['type'] . '-' . $key );
		}
	}
}
