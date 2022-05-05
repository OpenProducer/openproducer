<?php
/**
* File: Main.php
*
* Instantiate the plugin.
*
* @since      1.0.0
* @package    BoldGrid
* @subpackage PPBP
* @author     BoldGrid <support@boldgrid.com>
* @link       https://boldgrid.com
*/

namespace Boldgrid\PPBP;

/**
* Class: Main
*
* Instantiate the plugin.
*
* @since 1.0.0
*/
class Main {

	/**
	 * Configurations for the plugin.
	 *
	 * @since 1.0.0
	 *
	 * @var array Plugin Configs.
	 */
	protected $configs;

	/**
	 * Bind Hooks.
	 *
	 * @since 1.0.0
	 */
	public function init() {
		$this->configs = $this->setupPluginConfigs();

		$asset = new Asset();
		$asset->init();

		$config = new Component\Config();
		$config->init();

		$this->setupWidgets();
		$this->setupUpdater();
	}

	/**
	 * Merge all plugin configurations.
	 *
	 * @since 1.0.0
	 *
	 * @var array Configurations
	 */
	protected function setupPluginConfigs() {
		$configs = include __DIR__ . '/config/config.plugin.php';
		$local = __DIR__ . '/config/config.local.php';

		if ( file_exists( $local ) ) {
			$localConfigs = include $local;
			$configs = array_merge( $configs, $localConfigs );
		}

		return $configs;
	}

	/**
	 * Register all widget types
	 *
	 * @since 1.0.0
	 */
	protected function setupWidgets() {
		add_action( 'widgets_init', function () {
			register_widget( '\\Boldgrid\\PPBP\\Component\\Single' );
			register_widget( '\\Boldgrid\\PPBP\\Component\\PostList' );
		} );
	}

	/**
	 * Bind the update class.
	 *
	 * @since 1.0.0
	 */
	protected function setupUpdater() {
		$isCron = ( defined( 'DOING_CRON' ) && DOING_CRON );
		$isWpcli = ( defined( 'WP_CLI' ) && WP_CLI );

		if ( $isCron || $isWpcli || is_admin() ) {
			$update = new Update( $this->configs );
			add_action( 'init', [ $update, 'add_hooks' ] );
		}
	}
}
