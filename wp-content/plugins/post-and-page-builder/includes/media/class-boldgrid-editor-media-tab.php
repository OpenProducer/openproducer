<?php
/**
 * BoldGrid Source Code
 *
 * @package Boldgrid_Editor_Media_Tab
 * @copyright BoldGrid.com
 * @version $Id$
 * @author BoldGrid.com <wpb@boldgrid.com>
 */

/**
 * BoldGrid Media Tab class
 */
class Boldgrid_Editor_Media_Tab {
	/**
	 * A single tabs configuration
	 *
	 * @var array
	 */
	private $configs;

	/**
	 * Paths needed for including other files
	 *
	 * @var array
	 */
	protected $path_configs;

	/**
	 * The directory were assets belong should be prefixed by this path name
	 *
	 * @var string
	 */
	protected $asset_path_prefix;

	/**
	 * Bring in the configurations from the core plugin class
	 *
	 * @param array $configs
	 * @param array $path_configs
	 * @param string $asset_path_prefix
	 */
	public function __construct( $configs, $path_configs, $asset_path_prefix = '' ) {
		$this->set_configs( $configs );
		$this->set_path_configs( $path_configs );
		$this->set_asset_path_prefix( $asset_path_prefix );
	}

	/**
	 * Get $this->configs
	 *
	 * @return array
	 */
	protected function get_configs() {
		return $this->configs;
	}

	/**
	 * Set $this->configs
	 *
	 * @param array $s
	 */
	protected function set_configs( $configs ) {
		$this->configs = $configs;
		return true;
	}

	/**
	 * Get $this->path_configs
	 *
	 * @return array
	 */
	private function get_path_configs() {
		return $this->path_configs;
	}

	/**
	 * Set $this->path_configs
	 *
	 * @return array
	 */
	private function set_path_configs( $path_configs ) {
		$this->path_configs = $path_configs;
		return true;
	}

	/**
	 * Get $this->asset_path_prefix
	 *
	 * @return array
	 */
	private function get_asset_path_prefix() {
		return $this->asset_path_prefix;
	}

	/**
	 * Set $this->asset_path_prefix
	 *
	 * @param array $s
	 */
	private function set_asset_path_prefix( $asset_path_prefix ) {
		$this->asset_path_prefix = $asset_path_prefix;
		return true;
	}

	/**
	 * Add actions to create tabs.
	 */
	public function create() {
		$configs = $this->get_configs();
		add_filter( 'media_upload_tabs', array (
			$this,
			'media_upload_tab_name'
		) );

		add_action( 'media_upload_' . $configs['slug'],
			array (
				$this,
				'media_upload_tab_content'
			) );
	}

	/**
	 * Return the markup for the tab iframe
	 */
	public function print_content() {
		// Get path configs.
		$path_configs = $this->get_path_configs();

		// Get asset path prefix.
		$asset_path_prefix = $this->get_asset_path_prefix();

		$configs = $this->get_configs();

		include $configs['attachments-template'];
		$this->print_loading_graphic();
		include $configs['sidebar-template'];
	}

	/**
	 * Create a vertical tab.
	 *
	 * @param array $tabs
	 */
	public function media_upload_tab_name( $tabs ) {
		$configs = $this->get_configs();
		$newtab = array (
			$configs['slug'] => $configs['title']
		);

		return array_merge( $tabs, $newtab );
	}

	/**
	* Display Loading Graphic.
	*/
	public function print_loading_graphic() {

		// Get path configs.
		$path_configs = $this->get_path_configs();
		?>
		<div class="boldgrid-loading-graphic-wrapper">
			<div class='boldgrid-loading-graphic'>
				<img src="<?php echo esc_attr( plugins_url( '/assets/image/bg-logo.svg',
					 $path_configs['plugin_filename'] ) ) ?>" alt="BoldGrid logo">
				<div class='loading-bar-wrap'>
					<div class="loading-bar"></div>
				</div>
			</div>
		</div>
		<?php
	}

	/**
	 * Create a tabs content
	 */
	public function media_upload_tab_content() {
		add_action( 'admin_enqueue_scripts', array (
			$this,
			'enqueue_header_content'
		) );

		return wp_iframe( array (
			$this,
			'print_content'
		) );
	}
	/**
	 * Register styles/scripts
	 */
	public function enqueue_header_content() {
		// Get the &tab= from the url.
		$tab = ( ! empty( $_REQUEST[ 'tab' ] ) ? $_REQUEST[ 'tab' ] : null );

		wp_enqueue_media();

		wp_enqueue_script( 'custom-header' );

		// Get path configs.
		$path_configs = $this->get_path_configs();

		// Get asset path prefix.
		$asset_path_prefix = $this->get_asset_path_prefix();

		// Styles for MediaTab iFrame.
		wp_register_style( 'media-tab-css-imhwpb',
			plugins_url( $asset_path_prefix . '/assets/css/media-tab.css',
				$path_configs['plugin_filename'] ), array (
				'media-views'
			), BOLDGRID_EDITOR_VERSION );

		wp_enqueue_style( 'media-tab-css-imhwpb' );

		// Media Tab Javascript.
		wp_register_script( 'media-imhwpb',
			plugins_url( $asset_path_prefix . Boldgrid_Editor_Assets::get_minified_js( '/assets/js/media/media' ),
				$path_configs['plugin_filename'] ), array (), BOLDGRID_EDITOR_VERSION );

		$configs = $this->get_configs();

		// Pass Variables into JS.
		wp_localize_script( 'media-imhwpb', 'IMHWPB = IMHWPB || {}; IMHWPB.Globals', array(
			'isIframe' => true,
			'tabs' => $configs['route-tabs'],
			'tab-details' => $configs['tab-details'],
			'admin-url' => get_admin_url(),
		) );

		wp_enqueue_script( 'media-imhwpb' );
	}
}
