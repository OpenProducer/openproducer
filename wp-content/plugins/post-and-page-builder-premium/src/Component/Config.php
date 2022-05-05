<?php
/**
* Class: Config
*
* Override the PPB Component configurations.
*
* @since 1.0.0
* @package    Boldgrid\PPBP\Component
* @subpackage Config
* @author     BoldGrid <support@boldgrid.com>
* @link       https://boldgrid.com
*/

namespace Boldgrid\PPBP\Component;

/**
* Class: Config
*
* Override the PPB Component configurations.
*
* @since 1.0.0
*/
class Config {

	/**
	 * Init the class.
	 *
	 * @since 1.0.0
	 */
	public function init() {
		add_filter( 'BoldgridEditor\Config', function ( $configs ) {
			$configs['component_controls'][ 'components' ][ 'wp_boldgrid_component_post' ] = [
				'js_control' => [
					'icon' => '<span class="dashicons dashicons-admin-post"></span>'
				],
			];

			$configs['component_controls'][ 'components' ][ 'wp_boldgrid_component_postlist' ] = [
				'js_control' => [
					'icon' => '<span class="dashicons dashicons-exerpt-view"></span>'
				],
			];

			return $configs;
		} );
	}
}
