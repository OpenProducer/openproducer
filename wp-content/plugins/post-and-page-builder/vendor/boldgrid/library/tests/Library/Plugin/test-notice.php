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
 * BoldGrid Library Library Plugin Notice Test class.
 *
 * @since 2.12.0
 */
class Test_BoldGrid_Library_Library_Plugin_Notice extends WP_UnitTestCase {
	/**
	 * Setup.
	 *
	 * @since 2.12.2
	 */
	public function setUp() {
		$this->sample_plugin       = Plugin\Factory::create( 'boldgrid-backup/boldgrid-backup.php' );
		$this->sample_notice_array = array(
			'id'       => 'bgbkup_database_encryption',
			'page'     => 'boldgrid-backup-premium-features',
			'version'  => '1.12.6',
			'isUnread' => true,
		);

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
	 * Test construct.
	 *
	 * @since 2.12.2
	 */
	public function test_construct() {
		$plugin        = $this->sample_plugin;
		$sample_notice = $this->sample_notice_array;

		$notice = new Plugin\Notice( $plugin, $sample_notice );
		$this->assertTrue( $notice instanceof \Boldgrid\Library\Library\Plugin\Notice );
		update_option( 'boldgrid_plugin_page_notices', array( $sample_notice ) );
		$notice = new Plugin\Notice( $plugin, $sample_notice );
		$this->assertTrue( $notice instanceof \Boldgrid\Library\Library\Plugin\Notice );
	}

	/**
	 * Test getId.
	 *
	 * @since 2.12.2
	 */
	public function test_getId() {
		$plugin        = $this->sample_plugin;
		$sample_notice = $this->sample_notice_array;

		$notice = new Plugin\Notice( $plugin, $sample_notice );
		$this->assertEquals( $sample_notice['id'], $notice->getId() );
	}

	/**
	 * Test getPageSlug.
	 *
	 * @since 2.12.2
	 */
	public function test_getPageSlug() {
		$plugin        = $this->sample_plugin;
		$sample_notice = $this->sample_notice_array;

		$notice = new Plugin\Notice( $plugin, $sample_notice );
		$this->assertEquals( $sample_notice['page'], $notice->getPageSlug() );
	}

	/**
	 * Test setPageSlug.
	 *
	 * @since 2.12.2
	 */
	public function test_setPageSlug() {
		$plugin        = $this->sample_plugin;
		$sample_notice = $this->sample_notice_array;

		$notice = new Plugin\Notice( $plugin, $sample_notice );
		$notice->setPageSlug( 'newslug' );
		$this->assertEquals( 'newslug', $notice->getPageSlug() );
	}

	/**
	 * Test getVersion.
	 *
	 * @since 2.12.2
	 */
	public function test_getVersion() {
		$plugin        = $this->sample_plugin;
		$sample_notice = $this->sample_notice_array;

		$notice = new Plugin\Notice( $plugin, $sample_notice );
		$this->assertEquals( $sample_notice['version'], $notice->getVersion() );
	}

	/**
	 * Test setVersion.
	 *
	 * @since 2.12.2
	 */
	public function test_setVersion() {
		$plugin        = $this->sample_plugin;
		$sample_notice = $this->sample_notice_array;

		$notice = new Plugin\Notice( $plugin, $sample_notice );
		$notice->setVersion( '0.0.0' );
		$this->assertEquals( '0.0.0', $notice->getVersion() );
	}

	/**
	 * Test MaybeShow.
	 *
	 * @since 2.12.2
	 */
	public function test_maybeShow() {
		$sample_notice = $this->sample_notice_array;
		$notice        = new Plugin\Notice( $this->mock_plugin, $sample_notice );

		$this->assertTrue( $notice->maybeShow() );
	}

	/**
	 * Test getIsUnread.
	 *
	 * @since 2.12.2
	 */
	public function test_getIsUnread() {
		$sample_notice = $this->sample_notice_array;
		$notice        = new Plugin\Notice( $this->mock_plugin, $sample_notice );

		$this->assertTrue( $notice->getIsUnread() );

		$no_show_plugin = $this->getMockBuilder( \Boldgrid\Library\Library\Plugin\Plugin::class )
			->setMethods( array( 'getPluginData', 'firstVersionCompare' ) )
			->disableOriginalConstructor()
			->getMock();
		$no_show_plugin->method( 'getPluginData' )
			->will( $this->returnValue( array( 'Version' => '0.0.0' ) ) );
		$no_show_plugin->method( 'firstVersionCompare' )
			->will( $this->returnValue( false ) );

		$read_notice = new Plugin\Notice( $no_show_plugin, $sample_notice );
		$this->assertFalse( $read_notice->getIsUnread() );
	}

	/**
	 * Test setIsUnread.
	 *
	 * @since 2.12.2
	 */
	public function test_setIsUnread() {
		$sample_notice = $this->sample_notice_array;
		$notice        = new Plugin\Notice( $this->mock_plugin, $sample_notice );

		$notice->setIsUnread( false );

		$this->assertFalse( $notice->getIsUnread() );
	}

	/**
	 * Test getFromOptions.
	 *
	 * @since 2.12.2
	 */
	public function test_getFromOptions() {
		$sample_notice_array = $this->sample_notice_array;
		$second_notice_array = array(
			'id'       => 'bgbkup_fake_notice',
			'page'     => 'boldgrid-backup-premium-features',
			'version'  => '1.12.6',
			'isUnread' => true,
		);

		$notice          = new Plugin\Notice( $this->sample_plugin, $second_notice_array );
		$expected_result = array( $second_notice_array, 1 );
		update_option(
			'boldgrid_plugin_page_notices',
			array( $sample_notice_array, $second_notice_array )
		);

		$this->assertEquals( $expected_result, $notice->getFromOptions( 'bgbkup_fake_notice' ) );
	}

	/**
	 * Test getPlugin.
	 *
	 * @since 2.12.2
	 */
	public function test_getPlugin() {
		$sample_notice = $this->sample_notice_array;
		$notice        = new Plugin\Notice( $this->sample_plugin, $sample_notice );

		$this->assertEquals( $this->sample_plugin, $notice->getPlugin() );
	}

	/**
	 * Test setPlugin.
	 *
	 * @since 2.12.2
	 */
	public function test_setPlugin() {
		$sample_notice = $this->sample_notice_array;
		$notice        = new Plugin\Notice( $this->sample_plugin, $sample_notice );

		$notice->setPlugin( $this->mock_plugin, $sample_notice );
		$this->assertEquals( $this->mock_plugin, $notice->getPlugin() );
	}
}
