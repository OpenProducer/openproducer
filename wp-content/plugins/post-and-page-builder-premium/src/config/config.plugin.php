<?php
/**
 * Plugin configuration file
 *
 * @link http://www.boldgrid.com
 * @since 1.0.0
 *
 * @package PPBP
 */

return array(
	'ajax_calls' => array(
		'get_plugin_version' => '/api/open/get-plugin-version',
		'get_asset' => '/api/open/get-asset',
	),
	'asset_server' => 'https://api.boldgrid.com',
	'plugin_name' => 'post-and-page-builder-premium',
	'plugin_key_code' => 'editor-premium',
	'main_file_path' => BGPPB_PREMIUM_ENTRY,
	'plugin_transient_name' => 'boldgrid_ppbp_version_data',
);
