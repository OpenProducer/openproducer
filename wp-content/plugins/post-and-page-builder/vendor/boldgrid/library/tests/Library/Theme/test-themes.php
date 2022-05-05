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
class Test_BoldGrid_Library_Library_Theme_Themes extends WP_UnitTestCase {

	private
		$themes,
		$wp_themes,
		$expected_themes,
		$active_theme;

	/**
	 * Setup.
	 *
	 * @since 1.7.7
	 */
	public function setUp() {
		global $wpdb;

		$this->themes = new Theme\Themes();
		$this->wp_themes = wp_get_themes();
		$theme_dirs = scandir( ABSPATH . '/wp-content/themes/' );
		$expected_themes = array();
		foreach ( $theme_dirs as $theme_dir ) {
			if ( is_dir( ABSPATH . '/wp-content/themes/' . $theme_dir ) && '..' !== $theme_dir && '.' !== $theme_dir ) {
				$expected_themes[] = $theme_dir;
			}
		}

		$this->active_theme = $wpdb->get_var(
			$wpdb->prepare(
				"SELECT option_value FROM {$wpdb->prefix}options WHERE option_name = %s",
				"stylesheet"
				)
			);

		$this->expected_themes = $expected_themes;
		sort( $this->expected_themes);
	}

	public function test_getList() {
		$stylesheet_list = [];
		$themes_list = $this->themes->get();
		foreach ( $themes_list as $theme ) {
			$stylesheet_list[] = $theme->stylesheet;
		}
		sort( $stylesheet_list );
		$this->assertEquals( $this->expected_themes, $stylesheet_list );
	}

	public function test_getFromStylesheet() {
		$x = 0;
		foreach( $this->expected_themes as $expected_theme ) {
			$theme = $this->themes->getFromStylesheet( $expected_theme );
			$this->assertEquals( $expected_theme, $theme->stylesheet );
			if ( $expected_theme === $theme->stylesheet ) {
				$x++;
			}
		}

		$this->assertCount( $x, $this->expected_themes );

		$this->assertTrue( is_wp_error( $this->themes->getFromStylesheet( 'FakeTheme' ) ) );
	}

	public function test_getActive() {
		$this->assertEquals( $this->active_theme, $this->themes->getActive()->stylesheet );
	}
}