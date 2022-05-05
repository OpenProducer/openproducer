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
class Test_BoldGrid_Library_Library_Theme_UpdateData extends WP_UnitTestCase {

	private
		$theme,
		$transient_data,
		$transient_data_props,
		$active_theme;

	/**
	 * Setup.
	 *
	 * @since 1.7.7
	 */
	public function setUp() {
		// Setup our configs.
		delete_transient( 'boldgrid_theme_information' );
		$this->transient_data = array(
			'twentytwenty' => array(
				'version'         => '1.1',
				'downloaded'      => 482251,
				'last_updated'    => new DateTime('2020-02-05'),
				'api_fetch_time'  => false,
				'active_installs' => 1000
			),
		);
	}

	public function test_getResponseData() {
		$updateData  = new Theme\UpdateData( new Theme\Theme( wp_get_theme( 'twentytwenty' ) ) );
		$responseData = $updateData->getResponseData();
		$responseData_props = array_keys( ( array ) $responseData );
		sort( $responseData_props );
		$expected_data = array_keys( $this->transient_data['twentytwenty'] );
		sort( $expected_data );
		$this->assertEquals( $expected_data, $responseData_props );
	}

	public function test_getInformationTransient() {
		$updateData  = new Theme\UpdateData( new Theme\Theme( wp_get_theme( 'twentytwenty' ) ) );
		$this->assertFalse( $updateData->getInformationTransient() );

		set_transient( 'boldgrid_theme_information', array(), 60 );
		$this->assertFalse( $updateData->getInformationTransient() );

		delete_transient( 'boldgrid_theme_information' );
	}
}