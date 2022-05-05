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
use Boldgrid\Library\Library\Plugin;

/**
 * BoldGrid Library Library Plugin Factory Test class.
 *
 * @since 2.12.2
 */
class Test_BoldGrid_Library_Library_Plugin_Factory extends WP_UnitTestCase {

	/**
	 * Plugin Data.
	 *
	 * @since 2.12.2
	 */
	public $plugin_data;

	/**
	 * Setup Function.
	 *
	 * @since 2.12.2
	 */
	public function setUp() {
		$plugin_data = array(
			'Name'        => 'Total Upkeep',
			'PluginURI'   => 'https://www.boldgrid.com/boldgrid-backup/',
			'Version'     => '1.13.2',
			'Description' => 'Automated backups, remote backup to Amazon S3 and Google Drive, stop website crashes before they happen and more. Total Upkeep is the backup solution you need. By BoldGrid.',
			'Author'      => 'BoldGrid',
			'AuthorURI'   => 'https://www.boldgrid.com/',
			'TextDomain'  => 'boldgrid-backup',
			'DomainPath'  => '/languages',
		);

		$configs = new Configs();
	}

	/**
	 * Test Create.
	 *
	 * @since 2.12.2
	 */
	public function test_create() {
		$expected_class = 'Boldgrid\Library\Library\Plugin\Plugin';
		$plugin_from_slug = Plugin\Factory::create( 'akismet' );
		$this->assertTrue( $plugin_from_slug instanceof \Boldgrid\Library\Library\Plugin\Plugin );
		$plugin_from_file = Plugin\Factory::create( 'akismet/akismet.php' );
		$this->assertTrue( $plugin_from_file instanceof \Boldgrid\Library\Library\Plugin\Plugin );
	}

	/**
	 * Test fileFromSlug.
	 *
	 * @since 2.12.2
	 */
	public function test_fileFromSlug() {
		$file = Plugin\Factory::fileFromSlug( 'hello-dolly' );
		$this->assertEquals( 'hello.php', $file );
		$file = Plugin\Factory::fileFromSlug( 'hello' );
		$this->assertEquals( 'hello.php', $file );
	}

	/**
	 * Test slugFromFile.
	 *
	 * @since 2.12.2
	 */
	public function test_slugFromFile() {
		$slug = Plugin\Factory::slugFromFile( 'hello.php' );
		$this->assertEquals( 'hello-dolly', $slug );
	}
}
