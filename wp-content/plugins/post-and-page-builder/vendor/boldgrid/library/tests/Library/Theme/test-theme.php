<?php
/**
 * BoldGrid Source Code
 *
 * @package Boldgrid_Plugintest
 * @copyright BoldGrid.com
 * @version $Id$
 * @author BoldGrid.com <wpb@boldgrid.com>
 */

use Boldgrid\Library\Library\Configs;
use Boldgrid\Library\Library\Theme;

/**
 * BoldGrid Library Library Plugin Plugin Test class.
 *
 * @since 2.7.7
 */
class Test_BoldGrid_Library_Library_Theme_Theme extends WP_UnitTestCase {

	private
		$stylesheet,
		$old_theme_url;

	/**
	 * Setup.
	 *
	 * @since 1.7.7
	 */
	public function setUp() {
		// Setup our configs.
		delete_site_transient( 'update_themes' );
		$this->stylesheet = 'twentytwenty';
		$this->old_theme_url = 'https://downloads.wordpress.org/theme/twentytwenty.1.0.zip';
		$this->transient_data = array(
			$this->stylesheet => array(
				'version'      => '1.1',
				'downloaded'   => 482251,
				'last_updated' => new DateTime('2020-02-05'),
			),
		);
	}

	public function test_constructor() {
		$wp_theme = wp_get_theme( $this->stylesheet );
		$theme = new Theme\Theme( $wp_theme );
		$this->assertEquals( $wp_theme, $theme->wp_theme );
		$this->assertEquals( $this->stylesheet, $theme->stylesheet );
	}
}