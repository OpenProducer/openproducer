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
 * BoldGrid Library Reseller Test class.
 *
 * @since 1.1
 */
class Test_BoldGrid_Libarary_Reseller extends WP_UnitTestCase {

	/**
	 * Test default reseller data.
	 */
	public function testDefaults() {
		$coinUrl = 'https://boldgrid.com/reseller-coin-url';

		delete_option( 'boldgrid_reseller' );

		$reseller = new Boldgrid\Library\Library\Reseller();

		// With no reseller data, check that the defaults are being returned.
		$this->assertSame( $reseller->centralUrl, $reseller->data['reseller_coin_url'] );

		update_option( 'boldgrid_reseller', array(
			'reseller_coin_url' => $coinUrl,
		));

		/*
		 * @todo The Reseller class is supposed to hook into update_option
		 * boldgrid_reseller, but it is not playing well with phpunit. Don't
		 * rely on that filter to run, manually reset the data.
		 */
		$reseller->setData();

		// With reseller data set, ensure defaults are not returned.
		$this->assertSame( $coinUrl, $reseller->data['reseller_coin_url'] );
	}
}