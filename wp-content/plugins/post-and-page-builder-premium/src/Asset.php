<?php
/**
* File: Asset.php
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
* Class: Asset
*
* Instantiate the plugin.
*
* @since 1.0.0
*/
class Asset {

	/**
	 * Are we running in dev mode.
	 *
	 * @since 1.0.0
	 *
	 * @var boolean
	 */
	protected $isDevMode = false;

	/**
	 * Setup the dev mode state.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		$this->isDevMode = defined( 'BGEDITOR_PREMIUM_SCRIPT_DEBUG' ) && BGEDITOR_PREMIUM_SCRIPT_DEBUG;
	}

	/**
	 * Bind Hooks.
	 *
	 * @since 1.0.0
	 */
	public function init() {
		add_action( 'boldgrid_editor_scripts_builder', [ $this, 'builderFiles' ] );
		add_action( 'wp_enqueue_scripts', [ $this, 'publicFiles' ], 1010 );
		add_filter( 'boldgrid_editor_before_editor_styles', [ $this, 'addEditorStyles' ] );
	}

	/**
	 * Add styles to the editor iframe.
	 *
	 * @since 1.0.0
	 *
	 * @param string $styles Editor Styles.
	 */
	public function addEditorStyles( $styles ) {
		$styles[] = $this->getWebpackAsset( 'application.min.css' );
		$styles[] = $this->getWebpackAsset( 'editor.min.css' );
		return $styles;
	}

	/**
	 * Enqueue public asset files.
	 *
	 * @since 1.0.0
	 */
	public function publicFiles() {
		wp_enqueue_script(
			'bgpbpp-public',
			$this->getWebpackAsset( 'application.min.js' ),
			[ 'jquery' ],
			BGPPB_PREMIUM_VERSION,
			true );

		wp_enqueue_style( 'bgpbpp-public',
			$this->getWebpackAsset( 'application.min.css' ),
			[],
			BGPPB_PREMIUM_VERSION );
	}

	/**
	 * Enqueue editor asset files.
	 *
	 * @since 1.0.0
	 */
	public function builderFiles() {
		wp_enqueue_script(
			'bgpbpp-editor',
			$this->getWebpackAsset( 'editor.min.js' ),
			[ 'jquery' ],
			BGPPB_PREMIUM_VERSION,
			true );

		wp_enqueue_style( 'bgpbpp-editor',
			$this->getWebpackAsset( 'editor.min.css' ),
			[],
			BGPPB_PREMIUM_VERSION );
	}

	/**
	 * Given a file that can be served from webpack, serve conditionally based on WP constants.
	 *
	 * @since 1.0.0
	 *
	 * @param string $file URL to file.
	 */
	protected function getWebpackAsset( $file ) {
		return ! $this->isDevMode ? plugins_url( "/dist/${file}", BGPPB_PREMIUM_ENTRY )
			: 'http://localhost:4001/' . $file;
	}

}
