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
 * BoldGrid Util Option Test class.
 *
 * @since 1.1.4
 */
class Test_BoldGrid_Util_Option extends WP_UnitTestCase {

	/**
	 * Test getFiltered.
	 *
	 * @since 1.1.4
	 */
	public function testDeletePluginTransients() {
		set_site_transient( 'boldgrid_plugins', 'car' );
		set_site_transient( 'boldgrid_wporg_plugins', 'bus' );
		set_site_transient( 'update_plugins', 'van' );

		$this->assertSame( get_site_transient('boldgrid_plugins'), 'car' );
		$this->assertSame( get_site_transient('boldgrid_wporg_plugins'), 'bus' );
		$this->assertSame( get_site_transient('update_plugins'), 'van' );

		Boldgrid\Library\Util\Option::deletePluginTransients();

		$this->assertSame( get_site_transient('boldgrid_plugins'), false );
		$this->assertSame( get_site_transient('boldgrid_wporg_plugins'), false );
		$this->assertSame( get_site_transient('update_plugins'), false );
	}
}