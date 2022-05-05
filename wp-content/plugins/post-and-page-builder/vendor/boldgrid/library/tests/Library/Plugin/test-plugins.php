<?php
/**
 * BoldGrid Source Code.
 *
 * @package Boldgrid_Plugintest
 * @copyright BoldGrid.com
 * @version $Id$
 * @author BoldGrid.com <wpb@boldgrid.com>
 */

use Boldgrid\Library\Library\Configs;
use Boldgrid\Library\Library\Plugin;

/**
 * BoldGrid Library Library Plugin Plugin Test class.
 *
 * @since 2.7.7
 */
class Test_BoldGrid_Library_Library_Plugin_Plugins extends WP_UnitTestCase {

	private
		$plugins,
		$expected_plugins,
		$expected_active,
		$expected_plugin_slugs;

	/**
	 * Setup.
	 *
	 * @since 1.7.7
	 */
	public function setUp() {
		$plugin_dirs                 = scandir( ABSPATH . '/wp-content/plugins/' );
		$this->expected_plugins      = array();
		$this->expected_plugin_slugs = array();
		foreach ( $plugin_dirs as $plugin_dir ) {
			if ( is_dir( ABSPATH . '/wp-content/plugins/' . $plugin_dir ) && '..' !== $plugin_dir && '.' !== $plugin_dir ) {
				$this->expected_plugin_slugs[] = $plugin_dir;
				$this->expected_plugins[]      = $plugin_dir . '/' . $plugin_dir . '.php';
			} elseif ( is_file( ABSPATH . '/wp-content/plugins/' . $plugin_dir ) && 'index.php' !== $plugin_dir && false !== strpos( $plugin_dir, '.php' ) ) {
				$this->expected_plugins[] = $plugin_dir;
				$file_contents            = file_get_contents( WP_PLUGIN_DIR . '/' . $plugin_dir );
				$lines                    = explode( "\n", $file_contents );
				foreach ( $lines as $line ) {
					if ( false !== strpos( $line, '@package' ) ) {
						$package                       = strtolower( explode( ' ', $line )[3] );
						$this->expected_plugin_slugs[] = str_replace( '_', '-', $package );
					}
				}
			}
		}

		sort( $this->expected_plugins );
		activate_plugin( 'akismet/akismet.php' );

		$this->expected_active = array( 'akismet/akismet.php' );

		$this->plugins = new Plugin\Plugins();
	}

	/**
	 * Test getAllPlugins.
	 *
	 * @since SINCEVERSIOn
	 */
	public function test_getAllPlugins() {
		$all_plugins      = $this->plugins->getAllPlugins();
		$all_plugins_list = array();
		foreach ( $all_plugins as $plugin ) {
			$all_plugins_list[] = $plugin->getFile();
		}

		sort( $all_plugins_list );
		$this->assertEquals( $this->expected_plugins, $all_plugins_list );
	}

	/**
	 * Test getBySlug.
	 *
	 * @since 2.12.2
	 */
	public function test_getBySlug() {
		$all_plugins = $this->plugins->getAllPlugins();
		$x           = 0;

		foreach ( $this->expected_plugin_slugs as $expected_plugin_slug ) {
			$plugin      = $this->plugins->getBySlug( $all_plugins, $expected_plugin_slug );
			$plugin_slug = isset( $plugin ) ? $plugin->getSlug() : null;
			$this->assertEquals( $expected_plugin_slug, $plugin_slug );
			if ( $expected_plugin_slug === $plugin->getSlug() ) {
				$x++;
			}
		}

		$this->assertCount( $x, $this->expected_plugins );

		$this->assertTrue( is_wp_error( $this->plugins->getBySlug( $all_plugins, 'FakeFake' ) ) );
	}

}
