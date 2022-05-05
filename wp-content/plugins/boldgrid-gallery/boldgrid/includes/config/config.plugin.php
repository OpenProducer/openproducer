<?php
if ( ! defined( 'WPINC' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit();
}

return array (
	'ajax_calls' => array (
		'get_plugin_version' =>	'/api/open/get-plugin-version',
		'get_asset' =>			'/api/open/get-asset',
	),
	'asset_server' =>			'https://wp-assets.boldgrid.com',
	'plugin_name' => 			'boldgrid-gallery',
	'plugin_key_code' => 		'gallery-wc-canvas',
	'main_file_path' => 		BOLDGRID_GALLERY_PATH . '/wc-gallery.php',
	'plugin_transient_name' => 	'boldgrid_gallery_version_data',
);
