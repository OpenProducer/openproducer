<?php
/**
 * Class: Boldgrid_Editor_Media
 *
 * Init processes needed for Editor Media.
 *
 * @since      1.2
 * @package    Boldgrid_Editor
 * @subpackage Boldgrid_Editor_Media
 * @author     BoldGrid <support@boldgrid.com>
 * @link       https://boldgrid.com
 */

/**
 * Class: Boldgrid_Editor_Media
 *
 * Init processes needed for Editor Media.
 *
 * @since      1.2
 */
class Boldgrid_Editor_Media {

	/**
	 * Create Tabs based on configurations
	 *
	 * @since 1.0
	 */
	public function create_tabs( $configs, $is_bg_theme ) {
		$tabs = $configs['tabs'];

		// Create each tab specified from the configuraiton.
		foreach ( $tabs as $tab ) {
			$media_tab_class = 'Boldgrid_Editor_Media_Tab';

			if ( isset( $tab['content-class'] ) ) {
				$media_tab_class = $tab['content-class'];
			}

			$tab['is-boldgrid-theme'] = $is_bg_theme;

			$media_tab = new $media_tab_class( $tab, $configs, '/' );

			$media_tab->create();
		}
	}
}