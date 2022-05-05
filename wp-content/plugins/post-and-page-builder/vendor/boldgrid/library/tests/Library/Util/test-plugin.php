<?php
/**
 * BoldGrid Source Code
 *
 * @package Boldgrid_Plugintest
 * @copyright BoldGrid.com
 * @version $Id$
 * @author BoldGrid.com <wpb@boldgrid.com>
 */

/**
 * BoldGrid Library Util Plugin Test class.
 *
 * @since 2.2.1
 */
class Test_BoldGrid_Library_Util_Plugin extends WP_UnitTestCase {

	/**
	 * Test getFiltered.
	 *
	 * @since 2.2.1
	 */
	public function testGetFiltered() {
		$plugin = new Boldgrid\Library\Library\Util\Plugin();

		$allPlugins = get_plugins();

		// This should find all plugins.
		$plugins = $plugin->getFiltered();
		$this->assertSame( $allPlugins, $plugins );
		$plugins = $plugin->getFiltered( 'php' );
		$this->assertSame( $allPlugins, $plugins );

		// Hello plugin is included with WordPress.
		$plugins = $plugin->getFiltered( 'hello' );
		$this->assertSame( 1, count( $plugins ) );

		// No BoldGrid plugins are included with WordPress.
		$plugins = $plugin->getFiltered( 'boldgrid-' );
		$this->assertSame( 0, count( $plugins ) );
	}
}