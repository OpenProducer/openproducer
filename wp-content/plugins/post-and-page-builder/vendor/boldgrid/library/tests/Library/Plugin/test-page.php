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
 * BoldGrid Library Library Plugin Page Test class.
 *
 * @since 2.12.2
 */
class Test_BoldGrid_Library_Library_Plugin_Page extends WP_UnitTestCase {
	/**
	 * Setup.
	 *
	 * @since 2.12.2
	 */
	public function setUp() {
		$this->config = array(
			'pages'        => array(
				'boldgrid-backup-premium-features',
				'boldgrid-backup-settings',
			),
			'page_notices' => array(
				array(
					'id'      => 'bgbkup_database_encryption',
					'page'    => 'boldgrid-backup-premium-features',
					'version' => '1.12.16',
				),
				array(
					'id'      => 'bgbkup_google_drive',
					'page'    => 'boldgrid-backup-premium-features',
					'version' => '1.12.16',
				),
				array(
					'id'      => 'bgbkup_new_settings_feature',
					'page'    => 'boldgrid-backup-settings',
					'version' => '1.12.16',
				),
			),
		);

		$this->sample_plugin = Plugin\Factory::create( 'boldgrid-backup/boldgrid-backup.php', $this->config );

		$this->mock_plugin = $this->getMockBuilder( \Boldgrid\Library\Library\Plugin\Plugin::class )
			->setMethods( array( 'getPluginData', 'firstVersionCompare' ) )
			->disableOriginalConstructor()
			->getMock();
		$this->mock_plugin->method( 'getPluginData' )
			->will( $this->returnValue( array( 'Version' => '0.0.0' ) ) );
		$this->mock_plugin->method( 'firstVersionCompare' )
			->will( $this->returnValue( true ) );
	}

	/**
	 * Test Construct.
	 *
	 * @since 2.12.2
	 */
	public function test_construct() {
		$plugin = $this->sample_plugin;
		$page   = new \Boldgrid\Library\Library\Plugin\Page( $plugin, 'boldgrid-backup-premium-features' );

		$this->assertTrue( $page instanceof \Boldgrid\Library\Library\Plugin\Page );
	}

	/**
	 * Test getNotices.
	 *
	 * @since 2.12.2
	 */
	public function test_getNotices() {
		$plugin = $this->sample_plugin;
		$page   = new \Boldgrid\Library\Library\Plugin\Page( $plugin, 'boldgrid-backup-premium-features' );

		$this->assertTrue( $page->getNotices()[0] instanceof \Boldgrid\Library\Library\Plugin\Notice );
	}

	/**
	 * Test getNoticeById.
	 *
	 * @since 2.12.2
	 */
	public function test_getNoticeById() {
		$plugin = $this->sample_plugin;
		$page   = new \Boldgrid\Library\Library\Plugin\Page( $plugin, 'boldgrid-backup-premium-features' );

		$expected_id = $this->config['page_notices'][0]['id'];

		$retrieved_notice = $page->getNoticeById( $expected_id );

		$this->assertEquals( $expected_id, $retrieved_notice->getId() );

		$this->assertNull( $page->getNoticeById( 'fake-id' ) );
	}

	/**
	 * Test getSlug.
	 *
	 * @since 2.12.2
	 */
	public function test_getSlug() {
		$plugin = $this->sample_plugin;
		$page   = new \Boldgrid\Library\Library\Plugin\Page( $plugin, 'boldgrid-backup-premium-features' );

		$this->assertEquals( 'boldgrid-backup-premium-features', $page->getSlug() );
	}
}
