<?php
/**
 * BoldGrid Library Start.
 *
 * @package Boldgrid\Library
 * @subpackage \Library
 *
 * @version 1.0.0
 * @author BoldGrid <wpb@boldgrid.com>
 */

namespace Boldgrid\Library\Library;

/**
 * BoldGrid Library Start Class.
 *
 * This class is responsible for setting up the BoldGrid Library.
 *
 * @since 1.0.0
 */
class Start {

	/**
	 * @var object $configs         Library Configuration Object.
	 * @var object $releaseChannel  Library ReleaseChannel Object.
	 * @var object $pluginInstaller Library Plugin Installer Object.
	 * @var object $key             Library Key Object.
	 */
	private
		$configs,
		$releaseChannel,
		$pluginInstaller,
		$key;

	/**
	 * The BoldGrid Library's text domain.
	 *
	 * @since 2.8.0
	 * @access private
	 * @var string
	 */
	private $textdomain = 'boldgrid-library';

	/**
	 * Initialize class and set class properties.
	 *
	 * @since 1.0.0
	 *
	 * @uses \Boldgrid\Library\Library\Configs()
	 * @uses \Boldgrid\Library\Library\ReleaseChannel()
	 * @uses \Boldgrid\Library\Plugin\Installer()
	 * @uses \Boldgrid\Library\Library\Start::getReleaseChannel()
	 *
	 * @param array $configs Plugin configuration array.
	 */
	public function __construct( $configs = null ) {
		$configs = $this->filterConfigs( $configs );

		$this->configs = new Configs( $configs );

		// Load text domain right after configs are set, otherwise not everything will be translated.
		$this->loadPluginTextdomain();

		$this->releaseChannel = new ReleaseChannel;
		Configs::setItem( 'start', $this );

		if ( Configs::get( 'keyValidate' ) ) {
			$this->setupKeyConnections();
		}

		add_action( 'admin_init' , array( $this, 'loadPluginInstaller' ) );
	}

	/**
	 * Setup the connect key prompts & validation.
	 *
	 * @since 2.4.0
	 *
	 * @uses \Boldgrid\Library\Library\Key()
	 */
	public function setupKeyConnections() {
		$this->key = $this->key ?: new Key( $this->releaseChannel );
	}

	/**
	 * Get releaseChannel class property.
	 *
	 * @since 1.0.0
	 *
	 * @return object $releaseChannel Library\ReleaseChannel object.
	 */
	public function getReleaseChannel() {
		return $this->releaseChannel;
	}

	/**
	 * Get key class property.
	 *
	 * @since 1.0.0
	 *
	 * @return object $key Library\Key object.
	 */
	public function getKey() {
		return $this->key;
	}

	/**
	 * Initialization.
	 *
	 * @since 1.1.4
	 *
	 * @uses \Boldgrid\Library\Library\Plugin\Checker::run()
	 */
	public function init() {
		// Registration class runs Filter::add($this) in __construct.
		$registration = new \Boldgrid\Library\Library\Registration();

		// PostNewKey class runs Filter::add($this) in __construct.
		$postNewKey = new \Boldgrid\Library\Library\Key\PostNewKey();

		// Dashboard's init method instantiates classes, which run Filter::add($this) in __construct.
		$dashboard = new \Boldgrid\Library\Library\Dashboard();
		$dashboard->init();

		// WidgetNotifications class runs Filter::add($this) in __construct.
		$dashboardWidget = new \Boldgrid\Library\Library\Notifications\DashboardWidget();

		// NewsWidget class runs Filter::add($this) in __construct.
		$newsWidget = new \Boldgrid\Library\Library\NewsWidget();

		$pluginChecker = new \Boldgrid\Library\Library\Plugin\Checker();
		$pluginChecker->run();

		Configs::setItem( 'menu-external', new Menu\External() );
		Configs::setItem( 'menu-reseller', new Menu\Reseller() );
		Configs::setItem( 'page-connect', new Page\Connect() );
		Configs::setItem( 'assets', new Asset() );
		new Editor();
	}

	/**
	 * Load the library's text domain.
	 *
	 * @since 2.8.0
	 */
	private function loadPluginTextdomain() {
		load_textdomain( $this->textdomain, $this->configs->get( 'libraryDir' ) . 'languages/' . $this->textdomain . '-' . get_locale() . '.mo' );
	}

	/**
	 * Load the Plugin\Installer class, if exists.
	 *
	 * @since 1.1.7
	 */
	public function loadPluginInstaller() {
		if ( ! did_action( 'Boldgrid\Library\Library\Start::loadPluginInstaller' ) ) {
			do_action( 'Boldgrid\Library\Library\Start::loadPluginInstaller' );

			if ( class_exists( '\Boldgrid\Library\Plugin\Installer' ) ) {
				$this->pluginInstaller = new \Boldgrid\Library\Plugin\Installer(
					Configs::get( 'pluginInstaller' ),
					$this->getReleaseChannel()
				);
			}
		}
	}

	/**
	 * Filter the configuration array.
	 *
	 * @since 2.2.1
	 *
	 * @param  array $configs Configuration array.
	 * @return array
	 */
	public function filterConfigs( $configs ) {
		if ( ! empty( $configs['libraryDir'] ) ) {
			$configs['libraryUrl'] = str_replace(
				ABSPATH,
				get_site_url() . '/',
				$configs['libraryDir']
			);
		}

		return $configs;
	}
}
