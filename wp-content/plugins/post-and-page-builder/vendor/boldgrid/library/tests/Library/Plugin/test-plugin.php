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
 * @since 2.12.2
 */
class Test_BoldGrid_Library_Library_Plugin_Plugin extends WP_UnitTestCase {
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
			->setMethods( array( 'getPluginData', 'getFirstVersion' ) )
			->disableOriginalConstructor()
			->getMock();
		$this->mock_plugin->method( 'getPluginData' )
			->will( $this->returnValue( array( 'Version' => '0.0.0' ) ) );
		$this->mock_plugin->method( 'getFirstVersion' )
			->will( $this->returnValue( '1.0.0' ) );
	}

	/**
	 * Test firstVersionCompare.
	 *
	 * @since 2.12.2
	 */
	public function test_firstVersionCompare() {
		$version_compare = $this->mock_plugin->firstVersionCompare( '1.1.0', '<=' );
		$this->assertTrue( $version_compare );
	}

	/**
	 * Test firstVersionCompare.
	 *
	 * @since 2.12.2
	 */
	public function test_getActivateUrl() {
		$plugin            = $this->sample_plugin;
		$expected_base_url = 'http://example.org/wp-admin/plugins.php?action=activate&amp;plugin=boldgrid-backup%2Fboldgrid-backup.php';
		$is_correct        = strpos( $plugin->getActivateUrl(), $expected_base_url );

		$this->assertNotFalse( $is_correct );
	}

	/**
	 * Test getIcons.
	 *
	 * @since 2.12.2
	 */
	public function test_getIcons() {
		$plugin = $this->sample_plugin;
		$icons  = $plugin->getIcons();

		$this->assertTrue( is_array( $icons ) );
	}

	/**
	 * Test getChildPlugins.
	 *
	 * @since 2.12.2
	 */
	public function test_getChildPlugins() {
		$plugin        = $this->sample_plugin;
		$child_plugins = $plugin->getChildPlugins();

		$this->assertTrue( is_array( $child_plugins ) );
	}

	/**
	 * Test getData.
	 *
	 * @since 2.12.2
	 */
	public function test_getData() {
		$plugin  = $this->mock_plugin;
		$version = $plugin->getData( 'Version' );

		$this->assertEquals( '0.0.0', $version );
	}

	/**
	 * Test getInstallUrl.
	 *
	 * @since 2.12.2
	 */
	public function test_getInstallUrl() {
		$plugin            = $this->sample_plugin;
		$expected_base_url = $plugin->getInstallUrl();
		$is_correct        = strpos( $plugin->getInstallUrl(), $expected_base_url );

		$this->assertNotFalse( $is_correct );
	}

	/**
	 * Test getIsInstalled.
	 *
	 * @since 2.12.2
	 */
	public function test_getIsInstalled() {
		$plugin       = Plugin\Factory::create( 'akismet/akismet.php' );
		$is_installed = $plugin->getIsInstalled();

		$this->assertTrue( $is_installed );
	}

	/**
	 * Test getFile.
	 *
	 * @since 2.12.2
	 */
	public function test_getFile() {
		$plugin = $this->sample_plugin;
		$file   = $plugin->getFile();

		$this->assertEquals( 'boldgrid-backup/boldgrid-backup.php', $file );
	}

	/**
	 * Test getNewVersion.
	 *
	 * @since 2.12.2
	 */
	public function test_getNewVersion() {
		$plugin      = $this->sample_plugin;
		$new_version = $plugin->getNewVersion();

		$this->assertEquals( '0', $new_version );
	}

	/**
	 * Test getPluginData.
	 *
	 * @since 2.12.2
	 */
	public function test_getPlugindata() {
		$plugin      = Plugin\Factory::create( 'akismet/akismet.php', $this->config );
		$plugin_data = $plugin->getPluginData();
		$text_domain = $plugin_data['TextDomain'];

		$this->assertEquals( 'akismet', $text_domain );
	}

	/**
	 * Test getPluginsChecked.
	 *
	 * @since 2.12.2
	 */
	public function test_getPluginsChecked() {
		$plugin          = $this->sample_plugin;
		$plugins_checked = $plugin->getPluginsChecked();

		$this->assertTrue( is_array( $plugins_checked ) );
	}

	/**
	 * Test getPages.
	 *
	 * @since 2.12.2
	 */
	public function test_getPages() {
		$plugin = $this->sample_plugin;
		$pages  = $plugin->getPages();

		foreach ( $pages as $page ) {
			$this->assertTrue( $page instanceof \Boldgrid\Library\Library\Plugin\Page );
		}
	}

	/**
	 * Test getPageBySlug.
	 *
	 * @since 2.12.2
	 */
	public function test_getPageBySlug() {
		$plugin = $this->sample_plugin;
		$page   = $plugin->getPageBySlug( 'boldgrid-backup-premium-features' );

		$this->assertEquals( 'boldgrid-backup-premium-features', $page->getSlug() );

		$page = $plugin->getPageBySlug( 'non-existant-page' );

		$this->assertNull( $page );
	}

	/**
	 * Test hasUpdate.
	 *
	 * @since 2.12.2
	 */
	public function test_hasUpdate() {
		$plugin     = $this->sample_plugin;
		$has_update = $plugin->hasUpdate();

		$this->assertFalse( $has_update );
	}

	/**
	 * Test getPluginData.
	 *
	 * @since 2.12.2
	 */
	public function test_isActive() {
		$plugin    = Plugin\Factory::create( 'akismet/akismet.php', $this->config );
		$is_active = $plugin->isActive();
		$this->assertFalse( $is_active );

		activate_plugin( $plugin->getFile() );
		$is_active = $plugin->isActive();
		$this->assertTrue( $is_active );
	}
}
