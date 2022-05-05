<?php
/**
 * @link    http://www.boldgrid.com
 * @since   1.0.0
 * @package BoldGrid\PPBP
 *
 *          @wordpress-plugin
 *          Plugin Name: Post and Page Builder Premium
 *          Plugin URI: https://www.boldgrid.com/wordpress-page-builder-by-boldgrid/
 *          Description: Premium extension for the Post and Page Builder.
 *          Version: 1.0.5
 *          Author: BoldGrid
 *          Author URI: https://www.boldgrid.com/
 *          License: GPL-2.0+
 *          License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 *          Text Domain: post-and-page-buider-premium
 *          Domain Path: /languagesD
 */

if ( ! defined( 'WPINC' ) ) {
	die();
}

if ( ! defined( 'BGPPB_PREMIUM_VERSION' ) ) {
	define( 'BGPPB_PREMIUM_VERSION', implode( get_file_data( __FILE__, array( 'Version' ), 'plugin' ) ) );
}

if ( ! defined( 'BGPPB_PREMIUM_PATH' ) ) {
	define( 'BGPPB_PREMIUM_PATH', dirname( __FILE__ ) );
}

if ( ! defined( 'BGPPB_PREMIUM_ENTRY' ) ) {
	define( 'BGPPB_PREMIUM_ENTRY', __FILE__ );
}

// Activation.
function bgppb_clear_transient() {
	do_action( 'Boldgrid\Library\License\clearTransient' );
}

register_activation_hook( __FILE__, 'bgppb_clear_transient' );
register_deactivation_hook( __FILE__, 'bgppb_clear_transient' );

// Verify Versions & Build Status, run plugin if valid.
require BGPPB_PREMIUM_PATH . '/src/Compatibility.php';
$bgppbpCompatibility = new BGPPBP_Compatibility();
if ( $bgppbpCompatibility->checkVersions() ) {
	require_once BGPPB_PREMIUM_PATH . '/autoload.php';

	$block = new \Boldgrid\PPBP\Development();
	if ( $block->checkValidBuild() ) {
		$main = new \Boldgrid\PPBP\Main();
		$main->init();
	}
}
