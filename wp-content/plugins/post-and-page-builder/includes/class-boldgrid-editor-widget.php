<?php
/**
 * Class: class-boldgrid-editor-widget.php
 *
 * Register widget areas.
 *
 * @since      1.6
 * @package    Boldgrid_Editor
 * @subpackage Boldgrid_Editor_Widget
 * @author     BoldGrid <support@boldgrid.com>
 * @link       https://boldgrid.com
 */

/**
 * Class: Boldgrid_Editor_Widget
 *
 * Register widget areas.
 *
 * @since      1.6
 */
class Boldgrid_Editor_Widget {

	/**
	 * Bind actions.
	 *
	 * @since 1.6
	 */
	public function init() {
		$this->register_siderbars();
		add_action( 'boldgrid_editor_sidebar', array( $this, 'sidebar' ) );
	}

	/**
	 * Print sidebar location.
	 *
	 * @since 1.6
	 */
	public function sidebar() {
		dynamic_sidebar( 'boldgrid-editor-sidebar' );
	}

	/**
	 * Register sidebars.
	 *
	 * @since 1.6
	 */
	public function register_siderbars() {
		$config = Boldgrid_Editor_Service::get( 'config' );
		foreach( $config['widget']['areas'] as $sidebar ) {
			register_sidebar( $sidebar );
		}
	}

}
